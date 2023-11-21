<?php

namespace Mailing\Controllers;

use Base\Controllers\BaseController;
use DataView\Libraries\DataViewConstructor;
use Mail\Libraries\MailLibrary;
use Mailing\Libraries\MailingLibrary;
use Mailing\Models\MailingModel;
use Mailing\Models\TemplateModel;

class Mailing extends BaseController 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->MailLibrary = new MailLibrary();
        $this->MailingModel = new MailingModel();
        $this->MailingLibrary = new MailingLibrary();
        $this->datas->context = 'mailing';
    }

    public function EmailSended()
    {
        return redirect()->to(base_url('mailing/emails'))->with('success', "L'email a bien été envoyé.");
    }

    public function Email($id_email=null)
    {
        $param = $this->MailingGetParam();

        $this->datas = object_merge($this->datas, $this->MailLibrary->convert_param('new', $param)); // important
        $this->datas->context_sub = 'mailing';
        $this->datas->button_send = $this->MailLibrary->button_send($param->url_output, $param->js_function_send);
        $this->datas->button_save = $this->MailLibrary->button_save($param->url_output, $param->js_function_save);
        $this->datas->titleView = 'Mailing';
        $this->datas->type = 'new';
        $this->datas->navigation = $this->MailLibrary->button_documentation() . $this->MailLibrary->button_menu();

        return view('Mailing\mailing', (array) $this->datas);
    }

    public function EmailList()
    {
        $DataView = new DataViewConstructor();
        
        $columns = [
            "datetime" => ["Date d'envoi/réception", true, 'desc'],
            "sender_mail" => ["Expéditeur", false],
            "to_mail" => ["Destinataire", false],
            "subject" => ["Sujet", true],
            // "message" => ["Message", true],
        ];
        
        $order = $DataView->GetOrderDefault($columns);
        $emails = $this->MailingModel->EmailsGet($order, $this->request);
        $pager = $this->MailingModel->EmailsPagerGet();
        if(!empty($this->request) && !empty($this->request->getGet('itemSearch')) && !empty($pager) && $pager->getTotal()==1) :
            return redirect()->to(base_url("mailing/email/view/" . $emails[0]->id_email));
        endif;

        $this->datas->context_sub = 'email';
        $this->datas->emails = $emails;
        $this->datas->pager = $pager;
        $this->datas->columns = $columns;
        $this->datas->nb_emails = !empty($pager) ? $pager->getTotal() : count($emails);
        $this->datas->titleView = "Module Mailing - Liste des emails envoyés";
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);

        return view('Mailing\email-list', (array) $this->datas);

    }

    private function MailingGetParam()
    {
        // param for module Mail
        $param = (object) [];
        $param->email_table = $this->t_email;
        $param->js_function_send = 'mailing_send(this)';
        $param->js_function_save = 'mailing_save(this)';
        $param->sender = generic_crm_email();
        $param->templates = $this->MailLibrary->templatesGet();
        $param->url_output = base_url('mail/save');

        // get imported recipients
        $post = (object) $this->request->getPost();
        if(!empty($post->mailing)) :
            $mailing = database_decode($post->mailing);
            $param->cci_selected = $this->MailingGetRecipients($mailing);
            $param->hidden_input = (object) ['post' => json_encode($mailing)];
        endif;

        return $param;
    }

    private function MailingGetRecipients($datas)
    {
        $recipients = [];
        foreach($datas as $data) :
            $recipients[] = $this->MailLibrary->RecipientGetByProfil($data->id_contact_profil);
        endforeach;
        $recipients = array_values(array_unique($recipients, SORT_REGULAR));

        return $recipients;
    }

    public function MailingSendTest()
    {
        $params = (object) $this->request->getPost();
        $post = (object) [];
        $post->id_demande = $params->id_demande ?? null;
        $results = $this->MailingLibrary->EmailSendByTemplate($params->ref, $post, 'isTest');
        if(empty($results)) :
            $status = 'warning';
            $message = "Aucun email n'a pu être envoyé. Veuillez vérifier que la demande existe bien et que l'adresse email du demandeur est bien encodé.";
        else :
            $status = !empty($results[0]->isSended) ? 'success' : 'warning';
            $message = !empty($results[0]->isSended) ? "L'email test a été envoyé à l'adresse : " . $results[0]->tos[0] . "." : "Erreur lors de l'envoi de l'email : " . $results[0]->error;
            endif;
        return redirect()->to($_SERVER["HTTP_REFERER"])->with($status, $message);
    }

    public function MailingSendTestModal($id_template)
    {
        $TemplateModel = new TemplateModel();
        $template = $TemplateModel->TemplateGet($id_template);

        $data = (object) [];
        $data->ref = $template->ref;
        $body = view('Mailing\template/send_test_modal', (array) $data);

        $result = (object) [];
        $result->body = $body;
        
        echo json_encode($result);
    }
}
