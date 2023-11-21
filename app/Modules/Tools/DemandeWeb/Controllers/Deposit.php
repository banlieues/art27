<?php

namespace DemandeWeb\Controllers;

use Base\Controllers\BaseController;
use DataView\Libraries\DataViewConstructor;
use DemandeWeb\Libraries\DemandeLibrary;
use DemandeWeb\Models\DemandeModel;

class Deposit extends BaseController 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        // if(!$this->fh_dao->get_autorisation("winterface2"))
        // die("Vous n'avez pas l'autorisation de voir le dépot Web");

        // $this->mysql = new MysqlLibrary();
        // $this->mysql->check_database();
        
        $this->DWModel = new DemandeModel();
        $this->DWLibrary = new DemandeLibrary();

        $this->datas->context = 'demande_web';
    }

    public function urls_file_convert_string_to_json_array()
    {
        $deposits = $this->db->table($this->t_deposit)->select('id_deposit, contact_name, contact_lastname, urls_file, gf_date_created')->where('urls_file is not null')->where('gf_date_created<"2022-05-11 20:08:58"')->get()->getResult();
        foreach($deposits as $deposit) :
            $post = (object) [];
            $post->urls_file = json_encode([$deposit->urls_file]);
            $this->db->table($this->t_deposit)->where('id_deposit', $deposit->id_deposit)->update($post);
        endforeach;
    }

    public function DepositList()
    {
        $DataView = new DataViewConstructor();

        // $this->session->setFlashdata('warning', $this->deposits_flashdata_import_get());

        $columns = [
            "created_at" => ["Date de demande", true, 'asc'],
            "contact_lastname" => ["Nom", true],
            "subject" => ["Sujet", true],
            "language" => ["Langue", true],
            "urls_file" => ['<span title="Pièces jointes">PJ</span>', false],
            "action" => ["", false],
        ];

        $order = $DataView->GetOrderDefault($columns);
        $deposits = $this->DWModel->DepositsGet($order, $this->request);
        $pager = $this->DWModel->DepositsPagerGet();

        $this->datas->deposits = $deposits;
        $this->datas->pager = $pager;
        $this->datas->columns = $columns;
        $this->datas->nb_deposits = !empty($pager) ? $pager->getTotal() : count($deposits);
        $this->datas->titleView = "Liste des demandes non traitées des formulaires en ligne";
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);

        return view('DemandeWeb\deposit-list', (array) $this->datas);
    }

    private function deposits_flashdata_import_get()
    {
        $nb_deposit_new = $this->db->table($this->t_deposit)->countAllResults();
        $nb_deposit_processed = $this->db->table($this->t_deposit)->where('is_deleted', 1)->countAllResults();
        $s_new = ($nb_deposit_new<=1) ? '' : 's';
        $s_pro = ($nb_deposit_processed<=1) ? '' : 's';
        $a_pro = ($nb_deposit_processed<=1) ? 'a' : 'ont';
        
        $html = "Il y a eu $nb_deposit_new demande$s_new importée$s_new dont $nb_deposit_processed $a_pro été traitée$s_pro.";

        return $html;
    }

    private function count_deposit_new_month()
    {
        $now = date('Y-m-d H:i:s');
        $begin = date('Y-m-01 00:00:00');
        $this->db->where('gf_date_created between "' . $begin . '" and "' . $now . '"');
        $nb = $this->db->table($this->t_deposit)->countAllResults();

        return $nb;
    }

    private function count_deposit_processed_month()
    {
        $now = date('Y-m-d H:i:s');
        $begin = date('Y-m-01 00:00:00');

        $q = $this->db->table($this->t_deposit);
        $q->where('gf_date_created between "' . $begin . '" and "' . $now . '"');
        $q->where('is_deleted', 1);
        $nb = $q->countAllResults();

        return $nb;
    }

    // public function DepositsGet()
    // {
    //     $deposits = $this->DWModel->DepositsGet();
    //     foreach($deposits as $deposit) :
    //         $deposit->comment = nl2br($deposit->comment);
    //     endforeach;
        
    //     $json = (object) [];
    //     $json->data = $deposits;

    //     echo json_encode($json);
    // }

    public function DepositSetWorker($type, $id_deposit)
    {
        $deposit = $this->DWModel->DepositGet($id_deposit);

        if($type=='on' && empty($deposit->id_user_on_work)) :
            $this->DWModel->DepositSetWorker($id_deposit, session('loggedUserId'));
        elseif($type=='off' && $deposit->id_user_on_work==session('loggedUserId')) :
            $this->DWModel->DepositSetWorker($id_deposit);
        endif;
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

    public function DepositView($id_deposit)
    {
        $deposit = $this->DWModel->DepositGet($id_deposit);

        $result = (object) [];
        if(!isset($deposit)) :
            $this->session->setFlashdata('warning', "Aucune demande trouvée à partir de l'ID $id_deposit");
        else :
            $this->datas->infos = $this->DWLibrary->get_info($id_deposit);
            // debugd($data->infos);
            $this->datas->id_deposit = $id_deposit;
            $this->datas->profils = $this->DWLibrary->ContactProfilGetDublon($deposit);
            if(!empty($deposit->address_street)) $this->datas->buildings = $this->DWLibrary->BuildingGetDublon($deposit);
            $this->datas->demandes = $this->DWLibrary->DemandeGetDublon($deposit);
            $this->datas->js_brugis = view('Components\js/brugis-tamo');
            $this->datas->titleView = "Détails de la nouvelle demande";
            if(!empty($deposit->id_user_on_work) && $deposit->id_user_on_work!=session('loggedUserId')) :
                $this->session->setFlashdata('warning', fontawesome('cogs') . ' 
                La demande est en cours de traitement par ' . $deposit->user_on_work);
            endif;

            $result->dialog_size = 'xl';
            $result->header = "Détails de la nouvelle demande";
            $result->body = $this->DepositGetAlertWorker($deposit) . view('DemandeWeb\deposit-info', (array) $this->datas);
            $result->footer = $this->button_delete_modal($id_deposit) . $this->button_deposit_import($id_deposit);
            $result->close_button = $this->button_close_worker_off($id_deposit);
        endif;

        return view('DemandeWeb\deposit-view', (array) $this->datas);
    }

    public function DepositInfoModal($id_deposit)
    {
        $deposit = $this->DWModel->DepositGet($id_deposit);

        $result = (object) [];
        if(!isset($deposit)) :
            $result->header = "Erreur";
            $result->body = "Aucune demande trouvée à partir de l'ID $id_deposit";
            $result->footer = "";
        else :
            $this->datas->infos = $this->DWLibrary->get_info($id_deposit);
            // debugd($data->infos);
            $this->datas->id_deposit = $id_deposit;
            $this->datas->profils = $this->DWLibrary->ContactProfilGetDublon($deposit);
            if(!empty($deposit->address_street)) $this->datas->buildings = $this->DWLibrary->BuildingGetDublon($deposit);
            $this->datas->demandes = $this->DWLibrary->DemandeGetDublon($deposit);
            $this->datas->js_brugis = view('Components\js/brugis-tamo');
            
            $result->dialog_size = 'xl';
            $result->header = "Détails de la nouvelle demande";
            $result->body = $this->DepositGetAlertWorker($deposit) . view('DemandeWeb\deposit-info', (array) $this->datas);
            $result->footer = $this->button_delete_modal($id_deposit) . $this->button_deposit_import($id_deposit);
            $result->close_button = $this->button_close_worker_off($id_deposit);
        endif;

        echo json_encode($result);
    }

    public function DublonsBuildingGet($id_deposit, $id_contact=null, $id_contact_profil=null)
    {
        $deposit = $this->DWModel->DepositGet($id_deposit);

        $this->datas->buildings = $this->DWLibrary->BuildingGetDublon($deposit, $id_contact, $id_contact_profil);
        $this->datas->js_brugis = view('Components\js/brugis-tamo');
        
        $viewpath = $deposit->id_request_type==2 ? 'deposit-info-building-accomp' : 'deposit-info-building-question';
        echo view("DemandeWeb\\$viewpath", (array) $this->datas);
    }

    public function DublonsProfilGet($id_deposit, $id_building)
    {
        $deposit = $this->DWModel->DepositGet($id_deposit);

        $this->datas->profils = $this->DWLibrary->ContactProfilGetDublon($deposit, $id_building);

        echo view('DemandeWeb\deposit-info-profil', (array) $this->datas);
    }

    public function DublonsDemandeGet($id_deposit, $id_contact_profil, $id_building)
    {
        $id_contact_profil = ($id_contact_profil == 0) ? null : $id_contact_profil;
        $id_building = ($id_building == 0) ? null : $id_building;
        $deposit = $this->DWModel->DepositGet($id_deposit);

        $this->datas->demandes = $this->DWLibrary->DemandeGetDublon($deposit, $id_contact_profil, $id_building);

        echo view('DemandeWeb\deposit-info-demande', (array) $this->datas);
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

    public function deposit_delete_modal($id_deposit)
    {
        $deposit = $this->DWModel->DepositGet($id_deposit);

        $result = (object) [];
        $result->header = "Supprimer une demande";
        $result->body = "
            Vous êtes sur le point de retirer du dépôt la demande de <br><br>
            <div class='text-center font-weight-bold'> $deposit->contact_name $deposit->contact_lastname <small> (ID : $id_deposit) </small> </div> <br>
            Veuillez confirmer votre action.
        ";
        $result->footer = $this->button_delete_confirm($id_deposit);
        $result->close_button = $this->button_close_worker_off($id_deposit);
        
        echo json_encode($result);
    }

    public function DepositDelete($id_deposit)
    {
        $this->db->transStart();
        $this->DWModel->DepositDelete($id_deposit);
        if($this->db->transComplete() == false) :
            $messagetype = 'warning';
            $message = "La demande n'a pas pu être retirée du dépôt.";
        else :
            $messagetype = 'success';
            $message = "La demande a bien été retirée du dépôt.";
        endif;

        return redirect()->to(base_url('demande/web/deposits'))->with($messagetype, $message);
    }

    private function button_delete_modal($id_deposit)
    {
        $html = '
            <button type="button" class="btn btn-sm btn-danger mx-1" 
                onclick="deposit_delete_modal(this, ' . $id_deposit . ');"
                > 
                Retirer la demande du dépôt
            </button>
        ';
        return $html;
    }

    private function button_delete_confirm($id_deposit)
    {
        $html = '
            <a role="button" class="btn btn-sm btn-danger mx-1" href="' . base_url('demande/web/deposit/delete/' . $id_deposit) . '"> 
                Confirmer
            </a>
        ';
        return $html;
    }

    // private function button_doublon($id_deposit)
    // {
    //     $html = '
    //         <button type="button" class="btn btn-warning mx-1" onclick="modal_doublon(' . $id_deposit . ')"> 
    //             Doublon(s)
    //         </button>
    //     ';
    //     return $html;
    // }

    private function button_back_to_list()
    {
        $html = '
            <a role="button" class="btn btn-sm btn-dark mx-1" href="' . base_url('demande/web/deposits') . '"> 
                Retour à la liste
            </a>
        ';
        return $html;
    }

    private function button_deposit_import($id_deposit)
    {
        $html = '
            <button class="btn btn-sm btn-' . $this->themes->demande_web->color . ' mx-1" form="depositForm"> 
                Nouvelle demande
            </button>
        ';
        
        return $html;
    }
}

