<?php

namespace Mailing\Controllers;

use Base\Controllers\BaseController;
use DataView\Libraries\DataViewConstructor;
use Mail\Models\MailModel;
use Mailing\Libraries\MailingLibrary;
use Mailing\Models\TemplateModel;

class Template extends BaseController 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->MailModel = new MailModel();
        $this->MailingLibrary = new MailingLibrary();
        $this->TemplateModel = new TemplateModel();

        $this->datas->context = 'mailing';
    }
    
    public function EmailSendTestNotificationNew()
    // to send the email to the real recipient : $isTest="noTest"
    {
        $params = ['id_demande' => 122275];
        $isTest = 'noTest';

        $Outlook = new \Outlook\Libraries\Myoutlook_lib();
        $result = $Outlook->mails_notification_new($params, $isTest);
        debugd($result);
    }
        
    public function EmailSendTestNotificationAssign()
    // to send the email to the real recipient : $isTest="noTest"
    {
        $params = ['id_demande' => 122275, 'id_charger' => 2];
        $isTest = 'isTest';

        $Outlook = new \Outlook\Libraries\Myoutlook_lib();
        $result = $Outlook->mail_assign_notification($params, $isTest);
        debugd($result);
    }
    
    public function EmailSendTestNotification()
    // to send the email to the real recipient : $isTest="noTest"
    {
        $params = ['id_demande' => 122275, 'id_message' => 1];
        // $isTest = 'isTest';

        $Outlook = new \Outlook\Libraries\Myoutlook_lib();
        $result = $Outlook->mails_notification($params);
        debugd($result);
    }

    public function mails_notification_new($params, $isTest='noTest')
    {
        $MailingLibrary = new \Mailing\Libraries\MailingLibrary();
        $demande = $MailingLibrary->DemandeGet($params['id_demande']);
        if(empty($params['id_demande'])) return 'Le mail ne peut pas être envoyé : il manque le paramètre id_demande.';

        $post = (object) [];
        $post->id_demande = $params['id_demande'];

        $datas = [];
        $datas[] = $MailingLibrary->EmailSendByTemplate('confirm', $post, $isTest);
        if(!empty($demande->assign_id) && !in_array($demande->assign_id, [session('loggedUserId'), 25])) :
            $datas[] = $MailingLibrary->EmailSendByTemplate('assign', $post, $isTest);
        endif;

        $result = [];
        foreach($datas as $data) $result = array_merge($result, $data);

        return $result;
    }
        
    // public function mails_notification($params, $isTest='noTest')
    // {
    //     //cette fonction s'execute à chaque ajout d'un email à une demande
    //     $MailingLibrary = new \Mailing\Libraries\MailingLibrary();
    //     $demande = $MailingLibrary->DemandeGet($params['id_demande']);
    //     $mails_by_demande = $this->MailingLibrary->EmailsGetByDemandeOutMessage($params['id_demande'], $params['id_message']);

    //     $post = (object) [];
    //     $post->id_demande = $params['id_demande'];
    //     $datas = [];
    //     if(empty($mails_by_demande)) :
    //         $datas[] = $MailingLibrary->EmailSendByTemplate('confirm', $post, $isTest);
    //         if($demande->assign_mail != CRMAIL) :
    //             $datas[] = $MailingLibrary->EmailSendByTemplate('update_assign', $post, $isTest);
    //         endif;
    //     else :
    //         if($demande->assign_mail != CRMAIL) :
    //             $datas[] = $MailingLibrary->EmailSendByTemplate('update_assign', $post, $isTest);
    //         endif;
    //         if($demande->suiveur_mail != CRMAIL || $demande->suiveur_mail_default != CRMAIL) :
    //             $datas[] = $MailingLibrary->EmailSendByTemplate('update_suiveur', $post, $isTest);
    //         endif;
    //     endif;

    //     $result = [];
    //     foreach($datas as $data) $result = array_merge($result, $data);

    //     return $result;
    // }

    // public function mail_assign_notification($params, $isTest='noTest')
    // {
    //     //lorsque on change la personnée chargée et/ou la personne suiveur
    //     if(empty($params['id_demande']) || empty($params['id_charger'])) return false;
    
    //     $MailingLibrary = new \Mailing\Libraries\MailingLibrary();
    //     $demande = $MailingLibrary->DemandeGet($params['id_demande']);
    //     if(!empty($demande->assign_id) && !in_array($demande->assign_id, [session('loggedUserId'), 25])) :
    //         $post = (object) [];
    //         $post->id_demande = $params['id_demande'];
    //         $result = $MailingLibrary->EmailSendByTemplate('assign', $post, $isTest);
    //         return  $result;
    //     endif;
    // }

    public function index()
    {
        return redirect()->route('mailing/templates');
    }

    public function template_delete($id_template)
    {
        $this->db->transStart();
        $this->TemplateModel->template_delete($id_template);
        $this->db->transComplete();
        if ($this->db->transStatus() == FALSE) :

            return redirect()->to(base_url('mailing/templates'))->with('warning', "Le modèle d'email n'a pas pu être supprimé.");

        else :

            return redirect()->to(base_url('mailing/templates'))->with('success', "Le modèle d'email a bien été supprimé.");

        endif;
    }

    public function template_delete_modal($id_template)
    {
        $template = $this->TemplateModel->TemplateGet($id_template);

        $result = (object) [];
        $result->body = '
            <p> Vous êtes sur le point de supprimer le modèle d\'email </p>
            <p class="text-center"><strong>' . $template->label . '</strong></p>
            <p>Confirmez cette action irréversible.</p>
        ';
        $result->header = "Supprimer un modèle d'email";
        $result->footer = '
            <a role="button" class="btn btn-sm btn-danger"
                href="' . base_url('mailing/template/delete/' . $id_template) . '"
                > 
                Confirmer 
            </a>
        ';

        echo json_encode($result);
    }
    
    public function TemplateGet($id_template, $lang=null)
    {
        $template = $this->MailModel->TemplateGet($id_template);
        $template = $this->MailingLibrary->EmailSendConvertTemplateByLang($template, $lang);

        echo json_encode($template);
    }

    public function template_validation() 
    {
        $validation = \Config\Services::validation();
        $validation->setRule('ref', 'ref', 'required');
        $validation->setRule('label', 'label', 'required');
    }

    public function TemplateView($id_template=null)
    {
        $validation = \Config\Services::validation();

        if(!$this->Autorisation->is_autorise('email_template_c') && !$id_template) :
            return redirect()->to(base_url('mailing/templates'))->with('danger', "Vous n'êtes pas autorisé à créer de fiche modèle d'email.");
        elseif($validation->run($this->request->getPost(), 'MailingTemplate') == TRUE) :
            $post = database_decode($this->request->getPost());
            $message = !empty($id_template) ? "Le modèle d'email a bien été mis à jour." : "Le modèle d'email a bien été créé.";

            $id_template = $this->TemplateModel->TemplateSave($post, $id_template);

            return redirect()->to(base_url("mailing/template/view/$id_template"))->with('success', $message);
        endif;

        $template = !empty($id_template) ? $this->TemplateModel->TemplateGet($id_template) : null;
        $form_type = !empty($id_template) ? 'details' : 'new';
        
        $this->datas->controls = $this->MailingLibrary->form_get('template', $form_type, $template);
        $this->datas->id_template = !empty($id_template) ? $id_template : null;
        // $this->datas->navigation = $this->MailingLibrary->get_button_return() . $this->get_button_update($id_template);
        $this->datas->titleView = !empty($id_template) ? "Modèles d'email : $template->label" : "Nouveau modèle d'email";

        return view('Mailing\template-view', (array) $this->datas);
    }

    // private function get_button_update()
    // {
    //     return '
    //         <button form="templateUpdateForm" class="btn btn-sm btn-success ms-2"> 
    //             Enregistrer les modifications 
    //         </button>
    //     ';
    // }

    private function get_button_insert()
    {
        return '
            <button form="emodelNewForm" class="btn btn-sm btn-success ms-2"> 
                Sauvegarder le nouveau modèle d\'email 
            </button>
        ';
    }
    
    private function get_button_send_test($id_template)
    {
        return '
            <button type="button" class="btn btn-sm btn-primary ms-2"
                onclick="send_test_modal(this, ' . $id_template . ')"
                > 
                Envoi d\'un email test 
            </button>
        ';
    }
    
    public function TemplateList()
    {
        $DataView = new DataViewConstructor();

        $columns = [
            "updated_at" => ["Date de mise à jour", true, 'desc'],
            "author_lastname" => ["Mise à jour par", true],
            "ref" => ["Référence", true],
            "label" => ["Label", true],
            "is_activated" => ["Actif", true],
            "action" => ["", false],
        ];
        
        $order = $DataView->GetOrderDefault($columns);
        $templates = $this->TemplateModel->TemplatesGet($order, $this->request);
        $pager = $this->TemplateModel->TemplatesPagerGet();
        if(!empty($this->request) && !empty($this->request->getGet('itemSearch')) && !empty($pager) && $pager->getTotal()==1) :
            return redirect()->to(base_url("mailing/template/view/" . $templates[0]->id_template));
        endif;

        $this->datas->columns = $columns;
        $this->datas->context_sub = 'template';
        $this->datas->dataView = $DataView;
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
        $this->datas->nb_templates = !empty($pager) ? $pager->getTotal() : count($templates);
        $this->datas->pager = $pager;
        $this->datas->templates = $templates;
        $this->datas->titleView = "Liste des modèles d'email";

        return view('Mailing\template-list', (array) $this->datas);
    }

    public function TemplateDelete($id_template)
    {
        if(!$this->Autorisation->is_autorise('email_template_c')) return redirect()->to(base_url('mailing/templates'))->with('danger', "Vous n'êtes pas autorisé à supprimer de fiche modèle d'email.");

        $this->db->transStart();
        $this->TemplateModel->TemplateDelete($id_template);
        if($this->db->transComplete() == false) :
            $flashType = 'error';
            $flashMessage = "Echec lors de la suppression du modèle d'email...";
            // flashdata('emodelDelete', 'error', "Echec lors de la suppression du modèle d'email...");
        else :
            $flashType = 'success';
            $flashMessage = "Le modèle d'email a bien été supprimé.";
            // flashdata('emodelDelete', 'success', "Le modèle d'email a bien été supprimé.");
        endif;

        return redirect()->to(base_url('mailing/templates'))->with($flashType, $flashMessage);
    }
}
