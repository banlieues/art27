<?php

namespace Mail\Libraries;

use CodeIgniter\Files\File;
use Base\Libraries\BaseLibrary;

use Mail\Models\MailModel;

class MailLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $globals_module = new \Mail\Config\Globals();
        foreach($globals_module as $global_module=>$value) $this->$global_module = $value;

        $this->MailModel = new MailModel();
    }

    public function EmailSendSetSender($sender)
    {
        $data = [];
        if(isset($sender->name)) $data[] = $sender->name;
        if(isset($sender->lastname)) $data[] = $sender->lastname;
        if(isset($sender->email)) $data[] = '<' . $sender->email . '>';
        $string = implode(' ', $data);

        return $string;
    }

    public function EmailSendSetRecipient($recip_type, $post)
    {
        $recips = [];
        if(isset($post->{$recip_type . '_text'})) :
            foreach($post->{$recip_type . '_text'} as $string) :
                if(!is_string($string)) $string = json_encode($string);
                $recips[] = extract_email($string);
            endforeach;
        endif;
        if(isset($post->{$recip_type . '_selected'})) :
            foreach($post->{$recip_type . '_selected'} as $string) :
                if(!is_string($string)) $string = json_encode($string);
                $recips[] = extract_email($string);
            endforeach;
        endif;

        return $recips;
    }

    public function EmailSendSetSubject($post)
    {
        $text = [];
        if(!empty($post->subject)) $text[] = $post->subject;
        if(!empty($post->reference)) $text[] = $post->reference;

        return implode(' ', $text);
    }

    public function EmailSendSetMessage($post)
    {
        //debugd($post);
        // images inside message
        $message = $this->ImagesFileConvertToBase64($post->message);

        if(!empty($post->isSignature) && !empty($post->signature)) :
            if(strpos($message, '<blockquote') != false) :
                $position = strpos($message, '<blockquote');
                $message = substr_replace($message, '<div style="margin: 10px 0;">' . $post->signature . '</div>', $position, 0);
            else :
                $message = $message . $post->signature;
            endif;                   
        endif;

        $message = nl2br($message);
 
//debugd($message);
        return $message;
    }

    public function EmailSendSetAttach($ids_attach_selected=[])
    {
        $name = 'attachment_upload';
        $ids_attach_upload = [];
        if(!empty($this->request->getFiles()[$name])) :
            $ids_attach_upload = $this->attachments_upload_and_save_by_name($name);
        endif;

        $ids_attach = array_values(array_filter(array_merge($ids_attach_selected, $ids_attach_upload)));
        $datas = [];
        $pk = get_primary_key($this->t_file);
        if(!empty($ids_attach)) :
            $attachs = $this->db->table($this->t_file)->whereIn($pk, $ids_attach)->get()->getResult();
            foreach($attachs as $attach) :
                $datas[] = PATH_DOCU_DEMANDE . $attach->url_file;
            endforeach;
        endif;

        return $datas;
    }

    public function EmailSave($post,$id_demande=0)
    {
        $this->MailModel->EmailSave($post,$id_demande);
    }

    public function EmailSend($post)
    {
        if(empty(trim($post->senderEmail))) return (object) ['error' => "L'email n'a pu être envoyé : l'adresse de l'expéditeur n'est pas renseigné."];

        $post->isValid = 1;
        $config = !empty($post->config) ? $post->config : new \Custom\Config\Email();
        $email = \Config\Services::email();
        $email->initialize((array) $config);
        $email->clear(TRUE);

        // sender
        $email->setFrom($post->senderEmail, $post->senderName);

        // reply to
        if(!empty($post->reply)) $email->setReplyTo($post->reply);

        // recipient
        if(!empty($post->tos)) :
            foreach($post->tos as $to) :
                $url_permise = [$this->prod_url, "$this->prod_url/"];
                if(!in_array(base_url(), $url_permise)):
                    $to = sessionUser()->email;
                endif;
                $email->setTo($to);
            endforeach;
        endif;
        if(!empty($post->ccs)) foreach($post->ccs as $cc) $email->setCC($cc);
        if(!empty($post->ccis)) foreach($post->ccis as $cci)  $email->setBCC($cci);

        // content
        $email->setSubject($post->subject);

        // attachment
        if(!empty($post->attachs)) :
            foreach($post->attachs as $attach) :
                $email->attach($attach);
            endforeach;
        endif;

        if(isset($post->message)) :
            $email->setMessage($post->message);
        endif;

        if($email->send()) :
            $data = $this->EmailSaveSetRecipientOptionNull($post);
            $data->isSended = 1;
            $data->created_at = date('Y-m-d H:i:s');
        else :
            $data = (object) [];
            $data->error = $email->printDebugger();
        endif;
        
        return $data;
    } 

    private function ImagesFileConvertToBase64($message)
    {
        preg_match_all('/src="([^"]*)" id_attach="([^"]*)"/i', $message, $images);
        if(empty($images)) return $message;

        $FileLibrary = new \Components\Libraries\FileLibrary();
        $srcs = $images[1];
        $ids_file = $images[2];

        $i = 0;
        foreach($ids_file as $id_file) :
            $base64 = $FileLibrary->ImageFileToBase64($id_file);
            // debugd($base64);
            $message = str_replace($srcs[$i], $base64, $message);
            $i++;
        endforeach;

        return $message;
    }

    public function RecipientGetByProfil($id_profil)
    {
        return $this->MailModel->RecipientGetByProfil($id_profil);
    }

    public function templatesGet()
    {
        return $this->MailModel->templatesGet();
    }

    public function get_files_in_server_folder($relative_url)
    {
        $files = [];
        foreach(glob($relative_url . '/*') as $file):
            $files[] = base_url($file);
        endforeach;

        return $files;
    }
    
    public function get_attach_by_type($type, $attachs)
    {
        $datas = [];
        foreach($attachs as $attach) :
            $file = new \CodeIgniter\Files\File($attach->realPath);
            $url = str_replace(WRITEPATH, 'writable/', $attach->realPath);
            if(file_exists($url)) :
                $data = (object) [];
                $data->id_attach = $attach->id_attach;
                $data->url = urlencode(base64_encode($url));
                $data->name = $attach->url_file;
                if($type=='doc' && preg_match('/^image/', $file->getMimeType())==false) $datas[] = $data;
                elseif($type=='img' && preg_match('/^image/', $file->getMimeType())!=false) $datas[] = $data;
            endif;
        endforeach;

        return $datas;
    }

    public function get_email_info_view($table, $id_email)
    {
        $query = $this->db->table($table);
        $emails = $query->where('id_email', $id_email)->get()->getResult();
        $data = (object) [];

        if(isset($emails[0])) :
            $email = $emails[0];
            $email = database_decode($email);
            if(!empty($email->sender->id_user)) :
                $email->sender = $this->MailModel->user_get_by_id($email->sender->id_user);
            endif;
            if(!empty($email->ids_attach_selected)) :
                $attachs = $this->db->table($this->t_attach)->whereIn('id_attach', $email->ids_attach_selected)->get()->getResult();
                $email->attachs_doc = $this->get_attach_by_type('doc', $attachs);
                $email->attachs_img = $this->get_attach_by_type('img', $attachs);
            endif;
            // if(!empty($email->attachment_selected)) :
            //     $email->attachment_doc = $this->get_docs_from_array($email->attachment_selected);
            //     $email->attachment_img = $this->get_imgs_from_array($email->attachment_selected);
            // endif;
        
            $data->email = $email;           
        else :
            $data->error = "L'email recherché n'a pas été trouvé.";
        endif;

        return view('Mail\Views\mail\info', (array) $data);
    }

    public function attachments_upload_and_save_by_name($name)
    {
        $files = $this->request->getFiles();
        
        $ids_attach = [];
        if(!empty($files[$name])) :
            foreach ($files[$name] as $file) :
                $ids_attach[] = $this->attachment_upload_and_save($file);
            endforeach;
        endif;

        return $ids_attach;
    }

    public function attachment_upload_and_save($file)
    {
        $FileLibrary = new \Components\Libraries\FileLibrary();
        $id_file = $FileLibrary->FileUpload($file);

        return $id_file;
    }

    public function upload($reference, $config = null)
    {      
        $config['file_name'] = urlencode(iconv('UTF-8', 'ASCII//TRANSLIT', $_FILES[$reference]['name']));
        $config['encrypt_name'] = true;
        $config['upload_path'] = FCPATH . $this->attach_route;
        if (!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0777, true);
        $config['allowed_types'] = isset($config['allowed_types']) ? 
            $config['allowed_types'] : 
            'gif|jpg|png|doc|docx|odt|pdf|xls|xlsx|ods|ppt|pptx|odp';
        

        $this->upload->initialize($config);
        if ($this->upload->do_upload($reference)) :
            $post = $this->upload->data();
            // $post = $this->set_field_to_null_if_empty($this->attach_table, $post);
            $id_attach = $this->MailModel->insert_attachment($post);
            return $id_attach;
        else :
            _print($this->upload->display_errors());
            return false;
        endif;      
    }

    public function EmailSaveSetRecipientOptionNull($post)
    {
        foreach(['to', 'cc', 'cci'] as $recip) $post->{$recip . '_option'} = null;
        $post->recip_option_text = null;
        $post->sender_option = null;
        $post->reply_option = null;
        $post->attachment_option = null;
        
        return $post;
    }

    public function sendersDefault()
    {
        return $this->MailModel->sendersDefault();
    }

    public function get_recip_option_text()
    {
        return $this->MailModel->get_recip_option_text();
    }

    public function replace_mail_tag($string, $tags)
    {
        $tags = array_filter($tags);
        foreach($tags as $key=>$value) $string = str_replace('[' . $key . ']', $value, $string);

        return $string;
    }

    public function button_delete($id_email)
    {
        return '
            <a role="button" class="btn btn-sm btn-danger mx-1 d-inline" href="' . base_url('mail/example/email_delete/' . $id_email) . '">
                Supprimer le brouillon
            </a>
        ';
    }

    private function set_param_recipient($recip_type, $param)
    {
        if(!isset($param->{$recip_type . '_selected'})) $param->{$recip_type . '_selected'} = [];
        if(!isset($param->{$recip_type . '_text'})) $param->{$recip_type . '_text'} = [];
        if(!isset($param->{$recip_type . '_unselected'})) $param->{$recip_type . '_unselected'} = [];

        $title = '';
        switch($recip_type) :
            case 'reply' : $title = 'Réponse à'; break;
            case 'to' : $title = 'À'; break;
            case 'cc' : $title = 'Cc'; break;
            case 'cci' : $title = 'Cci'; break;
        endswitch;
        $param = [
            'recip_type' => $recip_type,
            'title' => $title,
            'recip_selected' => $param->{$recip_type . '_selected'},
            'recip_unselected' => $param->{$recip_type . '_unselected'},
            'recip_text' => $param->{$recip_type . '_text'},
        ];

        return $param;
    }
    
    public function delete_folder($dirname)
    {
        array_map('unlink', glob($dirname . '/*.*'));
        rmdir($dirname);
    }

    private function convert_param_to_form($param)
    {
        $param = object_filter($param);
        // DEFAULT
        if(empty($param->sender_option)) $param->sender_option = $this->MailModel->sendersDefault();
        if(empty($param->reply_option)) $param->reply_option = $this->MailModel->replysDefault();
        if(empty($param->recip_option_text)) $param->recip_option_text = $this->MailModel->get_recip_option_text();

        // RECIPIENT
        foreach(['to', 'cc', 'cci'] as $recip):
            if(!isset($param->{$recip . '_selected'})) $param->{$recip . '_selected'} = [];
            if(!isset($param->{$recip . '_unselected'})) $param->{$recip . '_unselected'} = [];
            if(isset($param->{$recip . '_option'})) :
                $param->{$recip . '_unselected'} = array_of_objects_diff($param->{$recip . '_option'}, $param->{$recip . '_selected'});
            endif;
            unset($param->{$recip . '_option'});
        endforeach;

        // ATTACHMENTS
        if(empty($param->attachment_selected)) $param->attachment_selected = [];
        if(empty($param->attachment_unselected)) $param->attachment_unselected = [];
        if(empty($param->attachment_option)) $param->attachment_option = [];

        $param->attachment_option = array_unique(array_merge(
            $param->attachment_selected, 
            $param->attachment_unselected, 
            $param->attachment_option
        ));

        // $param->attachment_doc_option = $this->get_docs_from_array($param->attachment_option);
        // if(!empty($param->attachment_selected)) $param->attachment_doc = $this->get_docs_from_array($param->attachment_selected);
        // $param->attachment_img_option = $this->get_imgs_from_array($param->attachment_option);
        // if(!empty($param->attachment_selected)) $param->attachment_img = $this->get_imgs_from_array($param->attachment_selected);

        unset($param->attachment_selected);
        unset($param->attachment_unselected);

        $param->param_to = $this->set_param_recipient('to', $param);
        $param->param_cc = $this->set_param_recipient('cc', $param);
        $param->param_cci = $this->set_param_recipient('cci', $param);
        $param->user_session = $this->MailModel->user_get_by_id(session('loggedUserId'));

        // if(isset($param->templates)) $views->templates = $param->templates;
        if(!isset($param->signature)) $param->signature = signature();
        if(isset($param->ids_attach_selected)) :
            $query = $this->db->table($this->t_attach);
            $attachs = $query->whereIn('id_attach', $param->ids_attach_selected)->get()->getResult();
            $param->attachs_doc = $this->get_attach_by_type('doc', $attachs);
            $param->attachs_img = $this->get_attach_by_type('img', $attachs);
        endif;

        return $param;
    }

    public function button_menu()
    {
        return '
            <a role="button" href="' . base_url('mail') . '" class="btn btn-sm btn-dark ms-2">
                Mail module menu
            </a>
        ';
    }
    
    public function button_documentation()
    {
        return '
            <a role="button" href="' . base_url('mail/documentation') . '" class="btn btn-sm btn-secondary ms-2">
                Documentation
            </a>
        ';
    }

    public function convert_param($type, $param)
    {
        $param = $this->convert_param_by_type($type, $param);
        $param = $this->convert_param_to_form($param);

        return $param;
    }

    private function convert_param_by_type($type, $param)
    {
        switch($type) :
            case 'new' : 
                $param->error = $this->is_error_param_new($param);
            break;
            case 'reply' :
                $param->error = $this->is_error_param_previous($param);
                if(isset($param->to_selected)) array_unshift($param->to_selected, $param->sender_old);
                else $param->to_selected[0] = $param->sender_old;
                $param->subject = 'Re: ' . $param->subject;
                $param->message = view('Mail\mail/message_previous', (array) $param);
            break;
            case 'forward' :
                $param->error = $this->is_error_param_previous($param);
                $param->to_unselected[] = $param->sender_old;
                $param->message = view('Mail\mail/message_previous', (array) $param);
                $param->subject = 'Fwd: ' . $param->subject;
                break;
        endswitch;

        return $param;
    }

    public function button_send($url_output, $js_function_send)
    {
        $html = '
            <button type="button" class="btn btn-sm btn-success mb-1"
                id="mailSend"
                form="mailForm" 
                onclick="' . $js_function_send . '"
                output-url="' . $url_output . '"
                >
                Envoyer
            </button>
        ';
        return $html;
    }    
    
    public function button_save($url_output, $js_function_save)
    {
        $html = '
            <button type="button" class="btn btn-sm btn-dark mb-1"
                id="mailSave"
                form="mailForm"
                onclick="waiting_start(this); ' . $js_function_save . '"
                output-url="' . $url_output . '"
                >
                Enregistrer le brouillon
            </button>
        ';
        return $html;
    }

    private function is_error_param_new($param)
    {
        $errors = [];
        if(!isset($param->url_output)) $errors[] = '$param->url_output : La méthode de sortie pour sauvegarder le brouillon ou l\'email envoyé';
        if(!isset($param->js_function_send)) $errors[] = '$param->js_function_send : Méthode js pour l\'envoi du mail';
        if(!isset($param->js_function_save)) $errors[] = '$param->js_function_save : Méthode js pour la sauvegarde du brouillon';

        if(count($errors)>0) return $errors;
        else return false;
    }

    private function is_error_param_previous($param)
    {
        $errors = [];
        if(!isset($param->url_output)) $errors[] = '$param->url_output : La méthode de sortie pour sauvegarder le brouillon ou l\'email envoyé';
        if(!isset($param->js_function_send)) $errors[] = '$param->js_function_send : Méthode js pour l\'envoi du mail';
        if(!isset($param->js_function_save)) $errors[] = '$param->js_function_save : Méthode js pour la sauvegarde du brouillon';

        if(!isset($param->sender_old)) $errors[] = '$param->sender_old : L\'expéditeur du mail à répondre.';
        if(!isset($param->subject)) $errors[] = '$param->subject : Le sujet du mail à répondre.';
        if(!isset($param->message)) $errors[] = '$param->message : Le contenu du mail à répondre.';
        if(!isset($param->send_datetime)) $errors[] = '$param->send_datetime\ : La date de réception du mail à répondre.';
        if(isset($param->emodels)) $errors[] = '$param->templates : Les modèles d\'email ne sont pas autorisés pour les mails à transférer.';

        if(count($errors)>0) return $errors;
        else return false;
    }
}