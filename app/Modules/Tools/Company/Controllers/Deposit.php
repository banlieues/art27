<?php

namespace Company\Controllers;

use Base\Controllers\BaseController;
use Base\Libraries\FileLibrary;
use Base\Libraries\ListLibrary;
use Company\Libraries\CompanyLibrary;
use Company\Libraries\MysqlLibrary;
use Company\Models\CompanyModel;
use Company\Models\DepositModel;
use Components\Libraries\FormLibrary;
use DataView\Libraries\DataViewConstructor;
use Translator\Libraries\TranslatorLibrary;
// use Layout\Libraries\LayoutLibrary;

class Deposit extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        // $mysql = new MysqlLibrary();
        // $mysql->check_database();

        $this->CompanyModel = new CompanyModel();
        $this->CompanyLibrary = new CompanyLibrary();
        $this->DepositModel = new DepositModel();
        $this->FormLibrary = new FormLibrary(__NAMESPACE__);

        $this->datas->context = 'company';
    }

    public function index()
    {
        return redirect()->to(base_url('company/deposits'));
    }

    
    public function DepositSetWorker($type, $id_deposit)
    {
        $deposit = $this->DepositModel->DepositGet($id_deposit);

        if($type=='on' && empty($deposit->id_user_on_work)) :
            $this->DepositModel->DepositSetWorker($id_deposit, session('loggedUserId'));
        elseif($type=='off' && $deposit->id_user_on_work==session('loggedUserId')) :
            $this->DepositModel->DepositSetWorker($id_deposit);
        endif;
    }

    public function DepositList()
    {
        $DataView = new DataViewConstructor();

        $columns = [
            "gf_date_created" => ["Date de demande", true, 'desc'],
            "label" => ["Dénomination", true],
            "address_city" => ["Ville", true],
            "comment" => ["Notes", false],
            "action" => ["", false],
        ];

        $order = $DataView->GetOrderDefault($columns);
        $deposits = $this->DepositModel->DepositsGet($order, $this->request);
        $pager = $this->DepositModel->DepositsPagerGet();

        $this->datas->columns = $columns;
        $this->datas->context_sub = 'deposit';
        $this->datas->deposits = $deposits;
        $this->datas->nb_deposits = !empty($pager) ? $pager->getTotal() : count($deposits);
        $this->datas->pager = $pager;
        $this->datas->titleView = t("Ready to renov", __NAMESPACE__) . ' - ' . t("Dépot des demandes", __NAMESPACE__);
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);

        return view('Company\deposit-list', (array) $this->datas);
    }
    
    private function DepositGetAlertWorker($deposit)
    {
        $html = '';
        if(!empty($deposit->id_user_on_work) && $deposit->id_user_on_work!=session('loggedUserId')) :
            $html = '
                <div class="alert alert-warning mb-2"> 
                    ' . fontawesome('cogs') . ' 
                    La demande est en cours de traitement par ' . $deposit->user_on_work . '
                </div>
            ';
        endif;

        return $html;
    }

    public function DepositModalComment($id_deposit)
    {
        $company = $this->DepositModel->DepositGet($id_deposit);
        $view = '';
        $view .= '<h6 class="fw-bold"> Notes </h6>';
        $view .= !empty($company->comment) ? nl2br($company->comment) : '';

        echo $view;
    }

    public function DepositInfoModal($id_deposit)
    {
        $deposit = $this->DepositModel->DepositGet($id_deposit);
        
        $result = (object) [];
        if(!isset($deposit)) :
            $result->header = "Erreur";
            $result->body = "Aucune demande trouvée à partir de l'ID $id_deposit";
            $result->footer = "";
        else :
            $data = (object) [];
            $data->posts = $this->CompanyLibrary->DepositGetInfo($id_deposit);
            $data->titles = $this->CompanyLibrary->deposit_info_titles_get();

            $result->dialog_size = 'xl';
            $result->header = "Détails de la demande : $deposit->label";
            $result->body = $this->DepositGetAlertWorker($deposit) . view('Company\deposit-view', (array) $data);
            $result->footer = 
                $this->button_delete_modal($id_deposit) . 
                $this->button_import_create_modal($id_deposit) . 
                $this->button_import_update_modal($id_deposit)
            ;
            $result->close_button = $this->button_close_worker_off($deposit->id_deposit);
        endif;

        echo json_encode($result);
    }

    public function DepositDeleteModal($id_deposit)
    {
        $deposit = $this->DepositModel->DepositGet($id_deposit);

        $result = (object) [];
        $result->header = "Retirer la demande du dépôt";
        $result->body = "
            Vous êtes sur le point de retirer du dépôt la demande de <br><br>
            <div class='text-center font-weight-bold'> $deposit->label </div> <br>
            Veuillez confirmer votre action.
        ";
        $result->footer = $this->button_delete_confirm($id_deposit);
        $result->close_button = $this->button_close_worker_off($id_deposit);
        
        echo json_encode($result);
    }

    public function DepositToCompanyUpdateModal()
    {
        $post = (object) $this->request->getPost();

        $company = $this->CompanyModel->CompanyGet($post->id_company);
        $deposit = $this->DepositModel->DepositGet($post->id_deposit);

        $result = (object) [];
        $result->header = "Fusion d'une nouvelle demande avec une fiche entreprise existante";
        $result->body = '
            Vous êtes sur le point de fusionner la nouvelle demande <br>
            <div class="text-center font-weight-bold"> ' . $deposit->label . '</div> <br>
            avec une fiche entreprise existante  <br>
            <div class="text-center"> 
                <span class="font-weight-bold"> ' . $company->label . ' </span> 
                <small> (ID : ' . $post->id_company . ') </small> 
            </div> <br>
            Veuillez confirmer votre action.
        ';

        $inputs_hidden = $this->FormLibrary->get_inputs_hidden_from_post($post);

        $result->footer =  '
            <form method="post" id="depositDublonForm" 
                action="' . base_url('company/deposit/to/company/update/' . $post->id_company) . '?redirect=deposits"
                >
                ' . $inputs_hidden . '
                <button class="btn btn-sm btn-success mx-1"> 
                    Confirmer et <br> retourner au dépot
                </button>
            </form>
            <form method="post" id="depositDublonForm" 
                action="' . base_url('company/deposit/to/company/update/' . $post->id_company) . '?redirect=company"
                >
                ' . $inputs_hidden . '
                <button class="btn btn-sm btn-success mx-1"> 
                    Confirmer et <br> aller à la fiche
                </button>
            </form>';
        $result->close_button = $this->button_close_worker_off($deposit->id_deposit);

        echo json_encode($result);
    }

    public function DepositToCompanyModal($id_deposit)
    {
        $deposit = $this->DepositModel->DepositGet($id_deposit);

        $result = (object) [];
        $result->header = "Ajouter une nouvelle fiche entreprise";
        $result->body = '
            Vous êtes sur le point de créer une nouvelle fiche entreprise<br><br>
            <div class="text-center font-weight-bold"> ' . $deposit->label . ' </div> <br>
            Veuillez confirmer votre action.
        ';
        $result->footer = '
            <a class="btn btn-sm btn-success mx-1" 
                href="' . base_url("company/deposit/to/company/$id_deposit?redirect=deposits") . '"
                > 
                Confirmer et <br> retourner au dépot
            </a>
            <a class="btn btn-sm btn-success mx-1" 
                href="' . base_url("company/deposit/to/company/$id_deposit?redirect=company") . '"
                > 
                Confirmer et <br> aller à la fiche entreprise
            </a>
        ';
        $result->close_button = $this->button_close_worker_off($id_deposit);
         
        echo json_encode($result);
    }

    private function button_close_worker_off($id_deposit)
    {
        return '
            <button type="button" 
                class="btn btn-sm btn-outline-secondary modal-close" 
                data-bs-dismiss="modal" 
                onclick="set_worker(\'off\', ' . $id_deposit . ');"
                >
                Annuler
            </button>
        ';
    }

    public function DepositDelete($id_deposit)
    {
        $this->db->transStart();
        $this->DepositModel->DepositDelete($id_deposit);
       
        if ($this->db->transComplete() == FALSE) :
            $messagetype = 'warning';
            $message = "La demande n'a pas pu être retirée du dépôt.";
        else :
            $messagetype = 'success';
            $message = "La demande a bien été retirée du dépôt.";
        endif;

        return redirect()->to(base_url('company/deposits'))->with($messagetype, $message);
    }

    private function button_delete_modal($id_deposit)
    {       
        $html = '';
        if($this->Autorisation->is_autorise('company_d')):
            $html .= '
                <button type="button" class="btn btn-sm btn-danger mx-1" 
                    onclick="
                        set_worker(\'on\', ' . $id_deposit . ');
                        deposit_delete_modal(this, ' . $id_deposit . ');
                    "
                    > 
                    Retirer la demande du dépôt
                </button>
            ';
        endif;

        return $html;
    }

    private function button_delete_confirm($id_deposit)
    {
        $html = '
            <a role="button" class="btn btn-sm btn-danger mx-1" href="' . base_url('company/deposit/delete/' . $id_deposit) . '"> 
                Confirmer
            </a>
        ';
        return $html;
    }

    private function button_back_to_list()
    {
        $html = '
            <a role="button" class="btn btn-sm btn-sm btn-dark mx-1" href="' . base_url('company/deposits') . '"> 
                Retour à la liste
            </a>
        ';
        return $html;
    }

    private function button_import_create_modal($id_deposit)
    {
        $html = '';
        if($this->Autorisation->is_autorise('company_c')) :
            $html .= '
                <button type="button" class="btn btn-sm btn-info mx-1"
                    button-type="create-modal" 
                    onclick="deposit_to_company_modal(this, ' . $id_deposit . ');"
                    > 
                    Créer une nouvelle fiche
                </button>
            ';
        endif;

        return $html;
    }

    private function button_import_update_modal($id_deposit)
    {
        $html = '
            <button type="button" class="btn btn-sm btn-info mx-1 d-none"
                button-type="update-modal"
                onclick="deposit_to_company_update_modal(this, ' . $id_deposit . ');"  
                > 
                Fusionner les données
            </button>
        ';

        return $html;
    }

    public function DepositToCompanyUpdate($id_company)
    {
        $post = (object) $this->request->getPost();
        $id_deposit = $post->id_deposit;
        unset($post->id_company);
        unset($post->id_deposit);

        $this->db->transStart();
        $id_company = $this->CompanyModel->CompanyUpdate($id_company, $post);
        if ($this->db->transComplete() === FALSE) :
            $messagetype = 'warning';
            $message = "Échec lors de la fusion de la nouvelle demande avec la fiche entreprise existante.";
        else :
            $messagetype = 'success';
            $message = "La fiche entreprise a bien été fusionnée avec les données de la nouvelle demande.";
            $this->DepositModel->DepositDelete($id_deposit);
        endif;

        $redirect = 'deposits';
        if(!empty($this->request->getGet('redirect')) && $this->request->getGet('redirect')=='company') $redirect = "company/view/$id_company";
        return redirect()->to(base_url("company/$redirect"))->with($messagetype, $message);        
    }

    public function DepositToCompany($id_deposit)
    {
        $this->db->transStart();
        $id_company = $this->DepositModel->DepositToCompany($id_deposit);
        if ($this->db->transComplete() === FALSE) :
            $messagetype = 'warning';
            $message = "Échec lors de la validation de la demande.";
        else :
            $messagetype = 'success';
            $message = "La demande a bien été validée et insérée dans le CRM.";
        endif;

        $redirect = 'deposits';
        if(!empty($this->request->getGet('redirect')) && $this->request->getGet('redirect')=='company') $redirect = "company/view/$id_company";
        return redirect()->to(base_url("company/$redirect"))->with($messagetype, $message);
    }
}

