<?php

namespace Mail\Controllers;

use Base\Controllers\BaseController;

use Mail\Libraries\MailLibrary;
use Mail\Models\MailModel;

class Example extends BaseController

{
    protected $module = 'Mail';
    protected $t_email = 'email';

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->MailLibrary = new MailLibrary();
        $this->MailModel = new MailModel();

        $this->datas->context = 'mail';
    }

    public function get_param_new()
    {
        // DOCUMENTATION

        // ------ REQUIRED -------

        if(empty($param)) $param = (object) [];

        // METHOD URL FOR OUTPUT
        $param->email_table = $this->t_email;
        $param->url_output = base_url('mail/example/email/save');
        $param->js_function_send = 'module_mail_send_example(this)';
        $param->js_function_save = 'module_mail_save_example(this)';

        // ------ OPTIONAL -------
              
        // SENDERS
        // DEFAULT : $param->sender_option = $this->MailModel->SendersDefault()
        $param->sender_option[] = (object) [
            'name' => 'Banlieues - Test',
            'lastname' => '',
            'email' => 'frameworker@banlieues.be',
            // possible to add extra keys
        ];
              
        // REPLY TO
        // DEFAULT : $param->reply_option = $this->MailModel->ReplysDefault()
        $param->reply_option[] = (object) [
            'name' => '',
            'lastname' => 'Banlieues - Test',
            'email' => 'frameworker@banlieues.be',
            // possible to add extra keys
        ];

        // RECIPIENTS SELECTED
        $param->to_selected = [
            (object) [
                'name' => 'Prénom C1',
                'lastname' => 'Nom C1',
                'email' => 'c1@mail.be',
                // possible to add extra keys
            ],            
            (object) [
                'name' => 'Prénom C2',
                'lastname' => 'Nom C2',
                'email' => 'c2@mail.be',
                // possible to add extra keys
            ],
        ];
        $param->to_selected[] = $this->MailModel->SenderGetByUser(session('loggedUserId'));

        // RECIPIENTS DESELECTED
        $param->to_unselected = [
            (object) [
                'name' => 'Prénom NC1',
                'lastname' => 'Nom NC1',
                'email' => 'non_coche1@mail.be',
                // possible to add extra keys
            ],            
            (object) [
                'name' => 'Prénom NC2',
                'lastname' => 'Nom NC2',
                'email' => 'non_coche2@mail.be',
                // possible to add extra keys
            ],
        ];

        // RECIPIENTS OPTIONAL
        // DEFAULT : $param->recip_option_text) = $this->MailModel->get_recip_option_text()

        // REFERENCE
        // $param->reference = '[#reference#]';

        // SUBJECT
        $param->subject = 'Le sujet';

        // MESSAGE
        $param->message = 'Ceci est le message.';

        // EMODELS
        $param->templates = [
            (object) [
                'id_template' => 1,
                'label' => 'Modèle 1',
                'subject' => 'Bonjour',
                'message' => 'Ceci est le message du modèle d\'email.',
            ],            
            (object) [
                'id_template' => 2,
                'label' => 'Modèle 2',
                'subject' => 'Re-bonjour',
                'message' => 'Ceci est un autre message de modèle d\'email.',
            ],
        ];

        // ATTACHMENTS
        $param->attachment_selected = [
            'docs/Homegrade_broFR_AcheterUnLogement.pdf',
            'images/login/Header-brochures_modif-e1558466393827.jpg',
        ];
        $param->attachment_unselected = [
            'docs/Homegrade_broFR_Chauffage.pdf',
            'images/login/Stephanie-O-20.jpg',
        ]; 

        // SIGNATURE
        // DEFAULT : $param->isSignature = 1;
        // DEFAULT : $param->signature = signature();
        // $param->signature = '<img src="https://blog.neocamino.com/wp-content/uploads/2013/05/signature-523237_1920.jpg" width=25%/>';

        // HIDDEN INPUT
        $param->hidden_input = (object) [
            'id_hidden_1' => 'hidden_value_1',
            'id_hidden_2' => 'hidden_value_2',
        ];

        return $param;
    }

    // public function email_new()
    // {
    //     $param = $this->get_param_new();

    //     $result = (object) [];
    //     $result->form = $this->MailLibrary->view_new($param); // important
    //     $result->button_send = $this->MailLibrary->button_send($param->url_output, $param->js_function_send);
    //     $result->button_save = $this->MailLibrary->button_save($param->url_output, $param->js_function_save);
        
    //     echo json_encode($result);
    // }
    
    // public function email_reply()
    // {
    //     $param = $this->get_param_reply();

    //     $result = (object) [];
    //     $result->form = $this->MailLibrary->view_reply($param); // important
    //     $result->button_send = $this->MailLibrary->button_send($param->url_output, $param->js_function_send);
    //     $result->button_save = $this->MailLibrary->button_save($param->url_output, $param->js_function_save);
        
    //     echo json_encode($result);
    // }
    
    public function email_view($type)
    {
        $param = $this->{'get_param_' . $type}();

        // $result = (object) [];
        $this->datas = object_merge($this->datas, $this->MailLibrary->convert_param($type, $param)); // important
        $this->datas->button_send = $this->MailLibrary->button_send($param->url_output, $param->js_function_send);
        $this->datas->button_save = $this->MailLibrary->button_save($param->url_output, $param->js_function_save);
        $this->datas->type = $type;
        $this->datas->navigation = $this->MailLibrary->button_documentation() . $this->MailLibrary->button_menu();
        $this->datas->titleView = t("Mail $type Example", __NAMESPACE__);

        return view($this->module . '\mail/index', (array) $this->datas);

        // $result->form = view('Mail\mail/form', (array) $param); 
        // $result->button_send = $this->MailLibrary->button_send($param->url_output, $param->js_function_send);
        // $result->button_save = $this->MailLibrary->button_save($param->url_output, $param->js_function_save);
        
        // echo json_encode($result);
    }
    
    private function get_param_reply()
    {
        // DOCUMENTATION

        // ------ REQUIRED -------

        if(empty($param)) $param = (object) [];

        // METHOD URL FOR OUTPUT
        $param->email_table = $this->t_email;
        $param->url_output = base_url('mail/example/email/save');        
        $param->js_function_send = 'module_mail_send_example(this)';
        $param->js_function_save = 'module_mail_save_example(this)';

        // PREVIOUS SEND DATETIME
        $param->send_datetime = '2001-01-01 11:11:11';

        // PREVIOUS SENDERS
        $param->sender_old = (object) [
            'name' => 'Ancien expéditeur',
            'lastname' => 'Vieux',
            'email' => 'tamhau@esoha.be',
            // possible to add extra keys          
        ];

        // REFERENCE
        $param->reference = '[#reference#]';

        // SUBJECT
        $param->subject = 'Le sujet précédent';

        // MESSAGE
        $param->message = 'Ceci est le message précédent.';

        // ------ OPTIONAL -------

        // SENDER
        // DEFAULT : $param->sender_option = $this->MailModel->SendersDefault();
        $param->sender_option[] = (object) [
            'name' => 'Banlieues - Test',
            'lastname' => '',
            'email' => 'frameworker@banlieues.be',
            // possible to add extra keys
        ];

        // REPLY
        // DEFAULT : $param->reply_option = $this->MailModel->ReplysDefault();
        $param->reply_option[] = (object) [
            'name' => 'Banlieues - Test',
            'lastname' => '',
            'email' => 'frameworker@banlieues.be',
            // possible to add extra keys
        ];

        // RECIPIENTS
        $param->to_selected[] = $this->MailModel->SenderGetByUser(session('loggedUserId'));
        $param->to_unselected = [           
            (object) [
                'name' => 'Prénom NC2',
                'lastname' => 'Nom NC2',
                'email' => 'non_coche2@mail.be',
                // possible to add extra keys
            ],
        ];
        
        // RECIPIENTS OPTIONAL
        // DEFAULT : $param->recip_option_text) = $this->MailModel->get_recip_option_text()

        // ATTACHMENTS
        $param->attachment_selected = [
           'docs/Homegrade_broFR_AcheterUnLogement.pdf',
            'images/login/Header-brochures_modif-e1558466393827.jpg',
        ];
        $param->attachment_unselected = [
            'docs/Homegrade_broFR_Chauffage.pdf',
            'images/login/Stephanie-O-20.jpg',
        ]; 

        // SIGNATURE
        // DEFAULT : $param->isSignature = 1;
        // DEFAULT : $param->signature = signature();
        $param->signature = '<img src="https://blog.neocamino.com/wp-content/uploads/2013/05/signature-523237_1920.jpg" width=25%/>';

        // EMODELS NOT ALLOWED
        // DEFAULT : if(isset($param->templates)) ERROR !

        // HIDDEN INPUT
        $param->hidden_input = (object) [
            'id_hidden_1' => 'hidden_value_1',
            'id_hidden_2' => 'hidden_value_2',
        ];

        return $param;
    }

    // public function email_forward()
    // {
    //     $param = $this->get_param_forward();
    //     $views = $this->MailLibrary->view_forward($param); // important

    //     echo json_encode($views);
    // }
        
    private function get_param_forward()
    {
        // DOCUMENTATION

        // ------ REQUIRED -------

        if(empty($param)) $param = (object) [];

        // METHOD URL FOR OUTPUT
        $param->url_output = base_url('mail/example/email/save');        
        $param->js_function_send = 'module_mail_send_example(this)';
        $param->js_function_save = 'module_mail_save_example(this)';

        // PREVIOUS SEND DATETIME
        $param->send_datetime = '2001-01-01 11:11:11';

        // PREVIOUS SENDERS
        $param->sender_old = (object) [
            'name' => 'Ancien expéditeur',
            'lastname' => 'Vieux',
            'email' => 'tamhau@esoha.be',
            // possible to add extra keys       
        ];

        // REFERENCE
        $param->reference = '[#reference#]';

        // SUBJECT
        $param->subject = 'Le sujet précédent';

        // MESSAGE
        $param->message = 'Ceci est le message précédent.';

        // ------ OPTIONAL -------

        // SENDER
        // DEFAULT : $param->sender_option = $this->MailModel->SendersDefault()
        $param->sender_option[] = $param->sender_option[] = (object) [
            'name' => 'Banlieues - Test',
            'lastname' => '',
            'email' => 'frameworker@banlieues.be',
            // possible to add extra keys
        ];

        // REPLY
        // DEFAULT : $param->reply_option = $this->MailModel->ReplysDefault();
        $param->reply_option[] = (object) [
            'name' => 'Banlieues - Test',
            'lastname' => '',
            'email' => 'frameworker@banlieues.be',
            // possible to add extra keys
        ];

        // RECIPIENTS
        $param->to_selected[] = $this->MailModel->SenderGetByUser(session('loggedUserId'));
        $param->to_unselected = [
            (object) [
                'name' => 'Prénom NC1',
                'lastname' => 'Nom NC1',
                'email' => 'non_coche1@mail.be',
                // possible to add extra keys
            ],            
            (object) [
                'name' => 'Prénom NC2',
                'lastname' => 'Nom NC2',
                'email' => 'non_coche2@mail.be',
                // possible to add extra keys
            ],
        ];
        
        // RECIPIENTS OPTIONAL
        // DEFAULT : $param->recip_option_text) = $this->MailModel->get_recip_option_text()

        // ATTACHMENTS
        $param->attachment_selected = [
            'docs/Homegrade_broFR_AcheterUnLogement.pdf',
            'images/login/Stephanie-O-20.jpg',
        ];
        $param->attachment_unselected = [
            'docs/Homegrade_broFR_Chauffage.pdf',
            'images/login/Header-brochures_modif-e1558466393827.jpg',
        ]; 

        // SIGNATURE
        // DEFAULT : $param->isSignature = 1;
        // DEFAULT : $param->signature = signature();
        $param->signature = '<img src="https://blog.neocamino.com/wp-content/uploads/2013/05/signature-523237_1920.jpg" width=25%/>';

        // EMODELS NOT ALLOWED
        // DEFAULT : if(isset($param->templates)) ERROR !

        // HIDDEN INPUT
        $param->hidden_input = (object) [
            'id_hidden_1' => 'hidden_value_1',
            'id_hidden_2' => 'hidden_value_2',
        ];

        return $param;
    }
    
    public function email_edit_modal($id_email)
    {
        $email = $this->MailModel->email_get_by_id($id_email);

        $param = $email;
        $param->user_session = $this->MailModel->user_get_by_id(session('loggedUserId'));
        $param->url_output = base_url('mail/example/email/save');
        $param->js_function_send = 'module_mail_send_example(this, ' . $id_email . ')';
        $param->js_function_save = 'module_mail_save_example(this, ' . $id_email . ')';

        $data = $this->MailLibrary->convert_param('new', $param); // important
        $data->button_send = $this->MailLibrary->button_send($param->url_output, $param->js_function_send, $id_email);
        $data->button_save = $this->MailLibrary->button_save($param->url_output, $param->js_function_save, $id_email);
        $data->button_delete = $this->MailLibrary->button_delete($id_email);

        echo view('Mail\mail/modal', (array) $data); // important
    }

    // public function output_data($id_email)
    // {
    //     $data = (object) [];
    //     $data->email = $this->MailModel->email_get_by_id($id_email);

    //     echo view('Mail\mail\info', (array) $data);
    // }

    public function EmailSave()
    {
        $data = database_decode($this->request->getPost());
        $id_email = $this->MailModel->EmailSave($data);

        echo $id_email;
    }

    public function email_info_modal($id_email)
    {
        $view = $this->MailLibrary->get_email_info_view($this->t_email, $id_email);
        echo $view;
    } 

    public function email_delete_modal($id_email)
    {
        $email = $this->MailModel->email_get_by_id($id_email);
        if(!empty($email->sender->id_user)) $email->sender = $this->MailModel->user_get_by_id($email->sender->id_user);

        $data = (object) [];
        $data->email = $email;
        $data->button_delete_confirm = $this->button_delete_confirm($id_email);

        echo view('Mail\mail/delete', (array) $data);
    } 

    private function button_delete_confirm($id_email)
    {
        return '
            <a role="button" class="btn btn-sm btn-danger" 
                href="' . base_url('mail/example/email_delete/' . $id_email) . '"
                >
                Supprimer le brouillon
            </a>
        ';
    }

    public function email_delete($id_email)
    {
        $this->db->transStart();
        $this->MailModel->email_delete($id_email);
        $this->db->transComplete();
        if ($this->db->transStatus() == FALSE) :
            return redirect()->to($_SERVER['HTTP_REFERER'])->with('warning', "L'email n'a pas pu être supprimé.");
        else :
            return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', "L'email a bien été supprimé.");
        endif;
    } 

    public function email_archive_modal($id_email)
    {
        $email = $this->MailModel->email_get_by_id($id_email);
        if(!empty($email->sender->id_user)) $email->sender = $this->MailModel->user_get_by_id($email->sender->id_user);

        $data = (object) [];
        $data->email = $email;
        $data->button_archive_confirm = $this->button_archive_confirm($id_email);

        echo view('Mail\mail/archive', (array) $data);
    } 

    private function button_archive_confirm($id_email)
    {
        return '
            <a role="button" class="btn btn-danger" 
                href="' . base_url('mail/example/email_archive/' . $id_email) . '"
                >
                Archiver l\'email
            </a>
        ';
    }

    public function email_archive($id_email)
    {
        $post = (object) [];
        $post->isArchive = 1;

        $this->db->transStart();
        $this->MailModel->email_update_by_id($id_email, $this->t_email, $post);
        $this->db->transComplete();
        if ($this->db->transStatus() == FALSE) :
            return redirect()->to($_SERVER['HTTP_REFERER'])->with('warning', "L'email n'a pas pu être archivé.");
        else :
            return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', "L'email a bien été archivé.");
        endif;
    } 
}