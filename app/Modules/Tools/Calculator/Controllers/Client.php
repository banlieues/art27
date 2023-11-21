<?php

namespace Calculator\Controllers;

use Base\Controllers\BaseController;
use Bien\Models\BienModel;
use Calculator\Libraries\AdminLibrary;
use Calculator\Libraries\ClientLibrary;
use Calculator\Libraries\GroupLibrary;
use Calculator\Models\AdminModel;
use Calculator\Models\ClientModel;
use Calculator\Models\DemandeModel;
use Components\Libraries\ComponentOrderBy;
use Components\Libraries\FileLibrary;
use Components\Libraries\PhpdocxLibrary;
use Contact\Models\ContactModel;
use DataView\Libraries\DataViewConstructor;
use Tesorus\Libraries\TesorusLibrary;

class Client extends BaseController
{
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);

        $this->TesorusLibrary = new TesorusLibrary();
        $this->AdminLibrary = new AdminLibrary();
        $this->AdminModel = new AdminModel();
        $this->ClientModel = new ClientModel();
        $this->ClientLibrary = new ClientLibrary();
        $this->GroupLibrary = new GroupLibrary();

        $this->datas->context = 'calculator';
        $this->datas->context_sub = 'devis';
    }

    public function DevisNavNew()
    {
        $post = database_decode($this->request->getPost());
        $post->id_work = '##' . $post->i . '##';
        $view = view('Calculator\devis-nav-ouvrage', ['work' => $post]);

        echo $view;
    }

    public function DevisList() 
    {   
        $OrderBy = new ComponentOrderBy();
        $mes_demandes = $this->request->getGet("mes_demandes") ?? 1;
        $orderBy = $OrderBy->getOrderBy("date", $this->request);
        $orderDirection = $OrderBy->getOrderDirection("DESC", $this->request);

        $fieldsOrder=
        [
            "id_demande" => ["ID Demande", true],
            "date" => ["Créée le", true],
            "type" => ["Type", true],
            "statut" => ["Statut", true],
            "nom_createur" => ["Créateur", true],
            "nom_encharge" => ["En charge", true],
            "sujet" => ["Sujet", true],
            "contact_associee" => ["Demandeur", true],
            "bien_associe" => ["Bien", true],
            "devis" => ["", false],
        ];
        
        $DemandeModel = new DemandeModel();
        $demandes = $DemandeModel->DemandesGet($mes_demandes, $this->request, $orderBy, $orderDirection);
        $pager = $DemandeModel->pager;

        $this->datas->demandes = $demandes;
        $this->datas->pager = $pager;
        $this->datas->nbDemandes = $pager->getTotal();
        $this->datas->itemSearch = $this->request->getGet("itemSearch");
        $this->datas->titleView = "Liste des demandes";
        $this->datas->statut_demandes = $DemandeModel->statut_demande();
        $this->datas->id_statut_demande = $this->request->getGet("statut_demande");
        $this->datas->mes_demandes = $mes_demandes;
        $this->datas->homegrade = $this->request->getGet("homegrade");
        $this->datas->getTh = $OrderBy->orderTh($fieldsOrder, $orderBy, $orderDirection, $this->request);

        return view('Calculator\demande-list', (array) $this->datas);
    }

    public function DevisWorkModal()
    {
        if($this->request->getPost()) :
            $post = database_decode($this->request->getPost());
            $id_work = $post->id_work;
            $work = $post->works->{$id_work};
            $work->id_work = $id_work;
            $data = [];
            foreach($work->groups as $id_group=>$group) :
                $group = object_merge($group, $this->AdminModel->GroupGet($id_group));
                $group->id_work = $id_work;
                $data[] = $group;
            endforeach;
            $work->groups = $data;
            $groups_by_them = $this->ClientModel->GroupsGetByThem($work->id_them);
            $work->groups_html = view('Calculator\devis-work-modal-groups', [
                'groups' => $groups_by_them,
                'ids_group_selected' => array_column($data, 'id_group'),
            ]);
            $this->datas->work = $work;
        endif;

        $result = (object) [];
        $result->body = view('Calculator\devis-work-modal', (array) $this->datas);
        $result->footer = '
            <button type="button"
                class="btn btn-sm btn-' . $this->themes->calculator->color . ' modal-close disabled"
                data-bs-dismiss="modal"
                disabled
                form="WorkForm"
                onclick="work_edit(this);"
                >
                Enregistrer l\'ouvrage
            </button>
        ';
        echo json_encode($result);
    }

    public function DevisGroups()
    {
        if(!$this->request->getPost()) return false;

        $id_them = $this->request->getPost('id_them');
        $this->datas->groups = $this->ClientModel->GroupsGetByThem($id_them);

        $html = view('Calculator\devis-work-modal-groups', (array) $this->datas);

        echo $html;
    }

    public function DevisWork()
    {
        $result = (object) [];
        if($this->request->getPost()) :
            $post = database_decode($this->request->getPost());
            $i = 0;
            foreach($post->ids_group as $id_group) :
                $group = $this->ClientModel->GroupWorkGet($post->id_work, $id_group);
                if(isset($post->work) && !empty($post->work->groups)) :
                    foreach($post->work->groups as $group_old) :
                        if($id_group==$group_old->id_group) :
                            $group = array_merge($group_old, $group);
                            break;
                        endif;
                    endforeach;
                endif;
                
                $group = $this->ClientLibrary->GroupGetCalculatedFields($post->id_work, $group);
                $groups[$i] = $group;

                $i++;
            endforeach;
            $post->groups = $groups;

            $this->datas->work = $post;
            $this->datas->typeDataView = 'update';

            $result->html = view('Calculator\devis-work', (array) $this->datas);
            $result->work = $post;
        endif;

        echo json_encode($result);
    }

    public function DevisMesurage($id_demande)
    {
        $filename = date('ymdHis') . '_Demande' . $id_demande . '_Mesurage';
        $variables = $this->ClientLibrary->VariablesGet($id_demande);

        $files = [];
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Template', $filename, $variables);
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Mesurage_Title', $filename, $variables);
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Demande', $filename, $variables);
        $files[] = $this->ClientLibrary->FileCreateFromTableMesurage($id_demande, $filename);
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Lexique', $filename, $variables);

        $Phpdocx = new PhpdocxLibrary();
        $docx_file = $Phpdocx->MergeDocx($files, $filename);
        foreach($files as $file) unlink($file);

        return redirect()->to(base_url('file/read/' . $filename . '.docx'));

        // not working conversion docx to pdf on production server...

        // $pdf_file = $Phpdocx->DocxToPdf($docx_file);
        // unlink($docx_file->getRealPath());

        // $FileLibrary = new FileLibrary($pdf_file);
        // $pdf_file->clientName = $filename . '.pdf';
        // $pdf_file->id_demande = $id_demande;
        // $id_file = $FileLibrary->FileDatabaseInsert($pdf_file);
        
        // return redirect()->to(base_url("demande/fiche/$id_demande"));
    }

    public function DevisPrint($id_demande)
    {
        $filename = date('ymdHis') . '_Demande' . $id_demande . '_Devis';
        $variables = $this->ClientLibrary->VariablesGet($id_demande);

        $files = [];
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Template', $filename, $variables);
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Print_Title', $filename, $variables);
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Demande', $filename, $variables);
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Print_ConditionsGenerales', $filename, $variables);
        $files[] = $this->ClientLibrary->FileCreateFromTablePrint($id_demande, $filename);
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Lexique', $filename, $variables);
        $files[] = $this->ClientLibrary->FileCreateFromTemplate('Print_NonCompris', $filename, $variables);

        $Phpdocx = new PhpdocxLibrary();
        $docx_file = $Phpdocx->MergeDocx($files, $filename);
        foreach($files as $file) unlink($file);

        if(in_array(base_url(), [$this->prod_url, "$this->prod_url/"])) :
            return redirect()->to(base_url('file/read/' . $filename . '.docx'));
        else :
            $pdf_file = $Phpdocx->DocxToPdf($docx_file);
            unlink($docx_file->getRealPath());

            return redirect()->to(base_url('file/read/' . $filename . '.pdf'));

            $FileLibrary = new FileLibrary();
            $pdf_file->clientName = $filename . '.pdf';
            $pdf_file->id_demande = $id_demande;
            $id_file = $FileLibrary->FileDatabaseInsert($pdf_file);
            
            return redirect()->to(base_url("demande/fiche/$id_demande"));
        endif;
    }

    public function DevisPrintView($id_demande, $id_devis)
    {
        $this->datas->devis = $this->ClientLibrary->DevisGet($id_demande, $id_devis);
        $html = view('Calculator\print', (array) $this->datas);
    }


    // public function PostList()
    // {
    //     $this->datas->EstimationHtmlTable = $this->AdminLibrary->EstimationHtmlTable();
    //     $this->datas->titleView = 'Calculette - Thesaurus des estimations';

    //     return view('Calculator\estimation-tesorus', (array) $this->datas);
    // }

    // public function DevisList($id_demande)
    // {
    //     $DataView = new DataViewConstructor();
    //     $columns = [
    //         "updated_at" => [t("Màj le", __NAMESPACE__), true, 'desc'],
    //         "updated_user" => [t("Màj par", __NAMESPACE__), true],
    //         "created_at" => [t("Créé le", __NAMESPACE__), true],
    //         "label" => [t("Dénomination", __NAMESPACE__), true],
    //         "annotation" => [t("Description", __NAMESPACE__), false],
    //         "comment" => [t("Notes internes", __NAMESPACE__), false],
    //         "action" => ["", false],
    //     ];
    //     $demande = $this->ClientModel->DemandeGet($id_demande);

    //     $this->datas->columns = $columns;
    //     $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
    //     $this->datas->demande = $demande;
    //     $this->datas->titleView = 'Calculator Client - Liste des devis de ' . fullname($demande->prenom_contact, $demande->nom_contact);

    //     return view('Calculator\mesurage-list', (array) $this->datas);
    // }

    // public function DevisMesurage($id_demande, $id_devis=null)
    // {
    //     if($this->request->getPost()) :
    //         $post = database_decode($this->request->getPost());
    //         $post->devis->id_demande = $id_demande;
            
    //         $id_devis = $this->ClientModel->DevisSave($post, $id_devis);

    //         return redirect()->to(base_url("calculator/demande/$id_demande/devis/$id_devis/mesurage"))->with('success', 'Le devis a bien été créé.');
    //     endif;

    //     $form_id = 'DevisForm';
    //     $typeDataView = !empty($id_devis) ? 'read' : 'create';
    //     $devis = $this->ClientLibrary->DevisGet($id_demande, $id_devis, $typeDataView, $form_id);
    //     $titleView = 'Calculette - Devis associé à la demande n°' . $id_demande;
    //     if(is_null($devis)) :
    //         $titleView = "Calculette - Erreur";
    //     endif;

    //     $this->datas->devis = $devis;
    //     $this->datas->form_id = $form_id;
    //     $this->datas->titleView = $titleView;
    //     $this->datas->typeDataView = $typeDataView;

    //     return view('Calculator\mesurage', (array) $this->datas);
    // }

    public function DevisView($id_demande)
    {
        if($this->request->getPost()) :
            $post = database_decode($this->request->getPost());
            $id_devis = $this->ClientModel->DevisSave($post, $id_demande);

            return redirect()->to(base_url("calculator/devis/$id_demande"))->with('success', 'Le devis a bien été sauvegardé.');
        endif;
        
        $roads = $this->ClientLibrary->GetThemsTesorus('active');
        $devis = $this->ClientLibrary->DevisGet($id_demande);
        $typeDataView = !empty($devis->works) ? 'read' : 'create';
        $titleView = is_null($devis) ? 'Calculette - Erreur' : 'Calculette - Devis associé à la demande ' . $id_demande;

        $this->datas->devis = $devis;
        $this->datas->roads = $roads;
        $this->datas->titleView = $titleView;
        $this->datas->typeDataView = $typeDataView;

        return view('Calculator\devis', (array) $this->datas);
    }

    // public function DevisEstimate($id_demande, $id_devis)
    // {
    //     $devis = $this->ClientLibrary->DevisGet($id_demande, $id_devis, 'read');
    //     $titleView = 'Calculette - Estimation du devis : ' . $devis->label;
    //     if(is_null($devis)) :
    //         $titleView = "Calculette - Erreur";
    //     endif;

    //     $this->datas->devis = $devis;
    //     $this->datas->titleView = $titleView;

    //     return view('Calculator\estimate', (array) $this->datas);
    // }
}



