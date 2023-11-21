<?php

namespace Mail\Controllers;

use Base\Controllers\BaseController;
use Components\Libraries\FileLibrary;

use Mail\Config\Globals;
use Mail\Models\MailModel;
use Mail\Libraries\MailLibrary;

class Mail extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->file_l = new FileLibrary();
        $this->MailModel = new MailModel();
        $this->MailLibrary = new MailLibrary();

        $this->datas->context = 'mail';

        // $config = new \Mail\Config\Validation();
    }
    
    public function index()
    {
        $this->datas->titleView = t("Menu module Mail", __NAMESPACE__);

        return view($this->module . '\mail/menu', (array) $this->datas);
    }
    
    public function template_get($id_template)
    {
        $template = $this->MailModel->templateGet($id_template);
        echo json_encode($template);
    }


    public function email_list()
    {
        $this->datas->emails = $this->MailModel->email_get_list();
        $this->datas->navigation = $this->MailLibrary->button_documentation() . $this->MailLibrary->button_menu();
        $this->datas->titleView = "Liste des emails";

        // $this->layout_library->set_title('Email list');
        // $this->layout_library->set_subtitle(
        //     'Email list', 
        //     '<a role="button" href="' . base_url('mail') . '" class="btn btn-dark"> Back </a>');

        return view($this->module . '\mail/list', (array) $this->datas);
    }

    public function documentation()
    {
        $this->datas->titleView = t("Mail documentation", __NAMESPACE__);
        $this->datas->navigation = $this->MailLibrary->button_menu();
        
        return view($this->module . '\mail/documentation', (array) $this->datas);
    }

    // public function view($type)
    // {
    //     $this->datas->type = $type;
    //     $this->datas->navigation = $this->MailLibrary->button_documentation() . $this->MailLibrary->button_menu();

    //     return view($this->module . '\view_example', (array) $this->datas);
    // }
    
    // private function get_filtered_post($post)
    // {
    //     foreach($post as $key=>$element) :
    //         if(is_array($element) && !empty($element)) : 
    //             $filtered = array_filter($this->request->getPost($key));
    //             if(!empty($filtered)) : $post[$key] = $filtered; 
    //             else : $post[$key] = null;
    //             endif;
    //         elseif(is_string($element) && isset($element)) : $post[$key] = $element;
    //         endif;
    //     endforeach;

    //     return $post;
    // }

    private function EmailSetParam($post)
    {
        $post->created_at = date("Y-m-d H:i:s");
        $post->created_by = session('loggedUserId');
        $ids_attach_selected = !empty($post->ids_attach_selected) ? $post->ids_attach_selected : [];
        $post->attachs = $this->MailLibrary->EmailSendSetAttach($ids_attach_selected);
        $post->tos = $this->MailLibrary->EmailSendSetRecipient('to', $post);
        $post->ccs = $this->MailLibrary->EmailSendSetRecipient('cc', $post);
        $post->ccis = $this->MailLibrary->EmailSendSetRecipient('cci', $post);
        $post->senderEmail = $this->MailLibrary->EmailSendSetSender($post->sender);
        $post->senderName = fullname($post->sender->name, $post->sender->lastname);
        $post->reply = !empty($post->reply) ? $this->MailLibrary->EmailSendSetSender($post->reply) : null;
        $post->subject = $this->MailLibrary->EmailSendSetSubject($post);
        $post->message = $this->MailLibrary->EmailSendSetMessage($post);

        return $post;
    }

    public function EmailSave()
    {
        $post = database_decode($this->request->getPost());
        if(empty($post->isSended)) $post = $this->EmailSetParam($post);

        $id_email = $this->MailModel->EmailSave($post);

        echo json_encode($id_email);
    }    

    public function EmailSend()
    {
        $validation = \Config\Services::validation();
        if($validation->run($this->request->getPost(), $this->module . 'Email') == true) :
            $post = database_decode($this->request->getPost());
            $post = $this->EmailSetParam($post);
            $data = $this->MailLibrary->EmailSend($post);
            if(!empty($data->isSended)) :
                $data = $this->MailLibrary->EmailSaveSetRecipientOptionNull($data);
                // $id_email = $this->MailModel->EmailSave($data);
            endif;
        else :
            $data = (object) [];
            $data->isValid = 0;
            $data->invalid_fields = $validation->getErrors();
        endif;
    
        echo json_encode($data);
    }
}

