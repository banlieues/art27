<?php 

namespace Mailing\Libraries;

use Base\Libraries\BaseLibrary;
use Components\Libraries\FormLibrary;
use Components\Libraries\ListLibrary;
use Components\Libraries\JsonLibrary;
use Mail\Libraries\MailLibrary;
use Mailing\Libraries\DemandeLibrary;
use Mailing\Models\MailingModel;
use Mailing\Models\TemplateModel;

class MailingLibrary extends BaseLibrary
{
    public function __construct()
    {   
        parent::__construct(__NAMESPACE__);

        $this->json_library = new JsonLibrary(__NAMESPACE__);
        $this->form_library = new FormLibrary(__NAMESPACE__);
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
        
        $this->DemandeLibrary = new DemandeLibrary();
        $this->MailingModel = new MailingModel();
        $this->MailLibrary = new MailLibrary();
        $this->TemplateModel = new TemplateModel();
    }

    public function EmailSendByTemplate($template_ref, $template_param=null, $isTest='noTest')
    // to send the email to the real recipient : $isTest="noTest"
    {
        $template = $this->TemplateModel->TemplateGetByRef($template_ref);
        if($template->is_activated==0 && $isTest=='noTest') return 'send email is desactivated';

        $params = [];
        if(!empty($template_param->id_demande)) :
            $params = $this->DemandeLibrary->EmailSendGetParamsByDemande($template_ref, $template_param->id_demande);
        endif;

        $results = [];
        $i = 0;
        foreach($params as $param) :
            $param = object_merge($param, $template_param);
            $results[$i] = $this->EmailSendOneByTemplate($template, $param, $isTest);
            $results[$i]->param = $param;
            $i++;
        endforeach;

        return $results;
    }

    public function EmailsGetByDemandeOutMessage($id_demande, $id_message)
    {
        return $this->DemandeLibrary->DemandeGet($id_demande);
    }

    public function DemandeGet($id_demande)
    {
        return $this->DemandeLibrary->DemandeGet($id_demande);
    }

    public function get_button_return()
    {
        return '
            <a role="button" class="btn btn-sm btn-dark ms-2" href="' . base_url('mailing/templates') . '">
                Revenir à la liste
            </a>
        ';
    }

    public function get_languages()
    {
        $list = (object) [];
        $list->table = $this->t_list_lang;
        $langs = $this->ListLibrary->get_datas_from_table_list($list);

        return $langs;
    }
    
    public function EmailSendOneByTemplate($template, $param, $isTest='noTest')
    {
        $id_demande=0;
        if(isset($param->id_demande))
        {
            $id_demande=$param->id_demande;
        }
        $template_lang = $this->EmailSendConvertTemplateByLang($template, $param->lang);
        $email_param = $this->EmailSendConvertTemplateToEmail($template_lang, $param);
        $sender = generic_crm_email();

        $data = (object) [];
        $data->senderEmail = $this->MailLibrary->EmailSendSetSender($sender);
        $data->senderName = fullname($sender->name, $sender->lastname);

        $data->tos[] = $isTest=='noTest' ? $param->recipient : sessionUser()->email;

        $data->isSignature = 1;
        $data->signature = signature();
        $data->subject = $isTest=='noTest' ? $this->MailLibrary->EmailSendSetSubject($email_param) : 'Test - ' .  $this->MailLibrary->EmailSendSetSubject($email_param);
        $data->message = $this->MailLibrary->EmailSendSetMessage($email_param);
        $data->attachs = $param->attachs ?? [];
        $data->isEmailAuto = 1;
        $data->mail_template = $template->ref;
        
        $result = $this->MailLibrary->EmailSend($data);

        if($isTest=='noTest' && !empty($result)):
            if(!empty($result->isSended)) :
                $id_email = $this->MailLibrary->EmailSave($result, $id_demande);
                //debugd($result);
                $result->id_email = $id_email;
            elseif(!empty($result->id_reminder)):
                $this->send_email_with_token_failed($result->id_reminder);
            endif;
        endif;

        return $result;
    }

    public function EmailSendConvertTemplateByLang($template, $lang=null)
    {
        $langs = $this->get_languages();
        $lang_array = [];
        foreach($langs as $l) $lang_array[] = mb_strtolower($l->label_fr);

        $mail = (object) [];
        $mail->subject = isset($template->subject_ref) ? $template->subject_ref . ' ' : '';
        if(isset($lang) && in_array($lang, $lang_array)) :
            $mail->subject .= $template->{'subject_' . $lang};
            $mail->message = $template->{'hello_' . $lang} . '<br><br>' . $template->{'content_' . $lang} . '<br><br>' . $template->{'greetings_' . $lang};
        else :
            shuffle($lang_array);
            $mail->message = '';
            $i=1;
            foreach ($lang_array as $l):
                $mail->subject .= $template->{'subject_' . $l};
                $mail->message .= $template->{'hello_' . $l} . '<br><br>' . $template->{'content_' . $l} . '<br><br>' . $template->{'greetings_' . $l};
                if($i<count($lang_array)):
                    $mail->subject .= ' / ';
                    $mail->message .= '<br><hr><br>';
                endif;
                $i++;
            endforeach;
        endif;

        return $mail;
    }

    private function EmailSendConvertTemplateToEmail($template, $param, $delimiter_start='[$', $delimiter_end=']')
    {
        if(isset($param->tags)):
            foreach ($param->tags as $key => $value) :
                $template->subject = str_replace($delimiter_start . $key . $delimiter_end, $value, $template->subject);
                $template->message = str_replace($delimiter_start . $key . $delimiter_end, $value, $template->message);
            endforeach;
        endif;
        $template->recipient = $param->recipient;

        unset($param->tags);
        $template->param = $param;

        return $template;
    }

    // public function EmailSendSetSender($post, $isTest)
    // { 
    //     $post->sender = 'Homegrade <' . CRMAIL . '>';
    //     $post->sender_object = (object) ['name' => 'Homegrade', 'lastname' => '', 'email' => CRMAIL];
        
    //     $post->sender = $this->MailLibrary->EmailSendSetSender($post->sender_object);

    //     if($isTest=='isTest') :
    //         $post->tos[0] = $this->UserModel->get_user_email_by_id(session('loggedUserId'));
    //     elseif($isTest=='noTest') :
    //         $post->tos[0] = $post->recipient;
    //     endif;

    //     return $post;
    // }

    // private function send_email_insert($result)
    // {
    //     $param = $result->param;
    //     $email = $result->email;

    //     $post = (object) [];
    //     $post->is_homegrade = 1;
    //     $post->created_by = session('Y-m-d H:i:s');
    //     $post->updated_by = session('Y-m-d H:i:s');
    //     $post->send_datetime = $email->send_datetime;
    //     $post->subject = $email->subject;
    //     $post->body_preview = $email->message;
    //     $post->body_content = $email->message;
    //     // $post->sender_mail = $data->from_mail;
    //     // $post->send_name = $data->from_name;
    //     $post->sender_mail = $email->sendFromEmail;
    //     $post->send_name = $email->sendFrom;
    //     $post->to_mail = $email->sendTo;

    //     $data = database_encode($this->t_email, $post);
    //     $this->db->set($data)->insert($this->t_email);
    //     $id_email = $this->db->getInsertID();

    //     $post = (object) [];
    //     $post->is_homegrade = 1;
    //     $post->id_email = $id_email;
    //     $post->created_by = $data->created_by;
    //     $post->updated_by = $data->updated_by;

    //     $data = database_encode($this->t_demande_email, $post);
    //     $this->db->set($data)->insert($this->t_demande_email);
    //     $this->db->getInsertID();

    //     return $id_email;
    // }

    private function send_email_with_token_failed($id_reminder)
    {
        $this->db->where('id_reminder', $id_reminder)->delete($this->t_reminder);
    }

    // public function variablesConvert($string, $param, $delimiter_start='[$', $delimiter_end=']')
    // {
    //     if(empty($string)) return '';

    //     foreach($param as $key=>$value) :
    //         $string = str_replace($delimiter_start . $key . $delimiter_end, $value, $string);
    //     endforeach;

    //     return $string;
    // }

    // public function send_email_outlook($mail)
    // {
    //     $this->load->library('email');

    //     $config['mailtype'] = 'html';
    //     $this->email->initialize($config);
    // 	$this->email->table($mail->sendFromEmail, $mail->sendFrom);
	// 	$this->email->to($mail->sendTo);
	// 	$this->email->subject($mail->subject);
	// 	$this->email->message($mail->message . signature());

    //     $param = $mail->param;
    //     unset($mail->param);

    //     $result = (object) [
    //         'status' => 'error', 
    //         'message' => '',
    //         'email' => $mail,
    //         'param' => $param
    //     ];
	// 	if(!$this->email->send()) :
    //         $result->message .= 'L\'email n\'a pu être envoyé à ';
    //     else :
    //         $result->status = 'success';
    //         $result->message .= 'L\'email a pu être envoyé à ';
    //         $result->email->send_datetime =  date("Y-m-d H:i:s");
    //     endif;
    //     $result->message .= htmlspecialchars('<' . $mail->sendTo . '>') . ' associé à la personne ' . $param->person . ' (ID : ' . $param->id_person . '). <br>';
        
    //     return $result;
	// }

    public function form_get($form, $form_type, $post=null)
    {
        $lang = service('request')->getLocale();

        $controls = (object) [];
        $structure = $this->json_library->getForm('template');

        foreach($structure as $ref=>$field) :
            $field = $this->form_library->get_form_control_field($field, $ref, $this->module, $form_type);
            $list = $this->ListLibrary->get_list_by_ref($ref);
            $controls->$ref = $this->form_library->get_form_control($field, $post, $list);
        endforeach;

        return $controls;
    }

    public function templates_get()
    {
        return $this->MailingModel->templates_get();
    }
}
    