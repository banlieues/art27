<?php

namespace Ticket\Controllers;

use Base\Controllers\BaseController;


use Ticket\Models\TicketModel;


use Layout\Libraries\LayoutLibrary;


use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Demande\Models\DemandeModel;

use Components\Libraries\ComponentOrderBy;

use Dompdf\Dompdf;
use Dompdf\Options;

use Barcode\Models\List_BarCodeModel;


use Mpdf\Mpdf;


class Ticket extends BaseController
{

    public  $mois_ligne=[
        "1janvier"=>"Janvier",
        "2fevrier"=>"Février",
        "3mars"=>"Mars",
        "4avril"=>"Avril",
        "5mai"=>"Mai",
        "6juin"=>"Juin",
        "7juillet"=>"Juillet",
        "8aout"=>"Août",
        "9septembre"=>"Septembre",
        "10octobre"=>"Octobre",
        "11novembre"=>"Novembre",
        "12decembre"=>"Décembre"

    ];


    public function __construct()
    {
       
        if(session()->get("loggedUserRoleId")==2)
        {
            header("Location:" . base_url("user"));
        }
            if(session()->get("loggedUserRoleId")!=1)
        {
            header("Location:" . base_url("identification/logout"));
        }

        parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();

        $request=$this->request;

        $this->ticketModel = new TicketModel();
        $this->list_BarCodeModel=new List_BarCodeModel();

        $this->context = 'ticket';
        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;
        $this->viewpath = $this->module . "\Views";  
        $this->componentOrderBy=new ComponentOrderBy();

        $this->autorisationManager = \Config\Services::autorisationModel();
    }

   
    public function listTicket($id_partenaire_culturel=0,$annee_select=0)
    {
   

        $orderBy=$this->componentOrderBy->getOrderBy("ticket.date_created",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        if($this->request->getVar("annee_select"))
        {
            $annee_select=$this->request->getVar("annee_select");
        }

        if(empty($annee_select))
        {
            $annee_select=date("Y");
        }

        $fieldsOrder=
        [
            
           // "delete"=>[null,false,"partenaire_socials_culturel_d"],
           "num_code_ticket"=>["NumCode",true],
           "-"=>["Ticket",false],
            "Partenaire culturel"=>["Partenaire culturel",false],
            "label_mois_ticket"=>["Mois concerné",true],
           
            "statut"=>["Statut ticket",true],
            "commentaire"=>["Commentaire",true],
            "date_scanning"=>["Scanné le",true],
            "scannor"=>["par",true],
            "Partenaire social"=>["Partenaire social",false],
            "num_code"=>["Référence",TRUE],
            "barcode"=>["Code Barre produit",false],
           
           

        ];


     

        
        $this->datas->tickets=$this->ticketModel->getListTicket($id_partenaire_culturel,$annee_select,$mois_select=0,$id_ticket=0,$this->request,$orderBy,$orderDirection);
        

        
        $this->datas->pager=$this->ticketModel->pager;
        $this->datas->nbTickets= $this->datas->pager->getTotal();
        $this->datas->fields= $this->ticketModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des tickets";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
        $this->datas->type_ticket=$this->ticketModel->get_liste_type_ticket();
        $this->datas->module=$this->module;
        $this->datas->partenaire_culturels=$this->ticketModel->get_partenaire_culturels();
        $this->datas->mois=$this->mois_ligne;
        $this->datas->annee_select=$annee_select;
        $this->datas->statuts=$this->list_BarCodeModel->get_statut();

        $this->datas->statuts_ticket=$this->ticketModel->get_statut();

        $this->datas->id_partenaire_culturel=$id_partenaire_culturel;

       


        return view($this->module . '\list-ticket', (array) $this->datas );
    }


    public function associe_demande($id_ticket)
    {
        if($this->request->getVar("id_demande"))
        {
            $this->ticketModel->associe_demande($id_ticket,$this->request->getVar("id_demande"));
            $this->datas->ticket=$this->ticketModel->getTicket($id_ticket);
            $this->datas->id_demande=$this->request->getVar("id_demande");
            $this->datas->titleView = "Confirmation de l'ajout du ticket n°$id_ticket à la demande n°".$this->request->getVar("id_demande");
            return view($this->module .'\confirmation_associe_demande', (array) $this->datas);
        }
        else
        {
            return redirect()->to(base_url()."/ticket/liste_demande/$id_ticket")->with("danger","Vous devez sélectionner une demande");
        }
    }


    public function upload_file()
    {
        //$write_path=APPPATH."tickets/individuel";
        $write_path=PATH_DOCU_UPLOAD.'individuel/';

    //$write_path="./assets/test_upload/";


        $id_partenaire_culturel=$this->request->getVar("id_partenaire_culturel");
    
        $annee=$this->request->getVar("annee_select");
        $mois=$this->request->getVar("mois_select");
        
        if ($this->request->getFile("file")) 
        {
            
            //if ($validateImage) {
                $imageFile = $this->request->getFile('file');
                
                $nameOriginal=$imageFile->getName();
                $newName = date("ymdHis").$id_partenaire_culturel.$mois.$annee.'_ticket';
                $nameOriginal=$imageFile->getName();
                $newName = "ticket_".date("ymdHis").$id_partenaire_culturel.$mois.$annee.'_'.slugify_name_with_extension($imageFile->getName());
    
                $imageFile->move($write_path,$newName);
                $data = [
    
                    'name' => slugify_name_with_extension($nameOriginal),
                    'url_file' => $newName,
                    'contentByte_Type'  => $imageFile->getClientMimeType(),
                    'id_user' =>session()->get('loggedUserId'),
                    'date_created'=>date("Y-m-d H:i:s"),
                    'display'   => 1,
                    'annee' => $annee,
                    'mois' => $mois,
                    'id_partenaire_culturel'=>$id_partenaire_culturel,
                
                ];
                $id_upload_file = $this->ticketModel->upload_file($data);
                
            //}
            
        }
        else
        {
            echo "Erreur! Pas de fichier";
        }
    

        //Je récupère l'image
    }


    
    public function decoup_pdf_dompdf()
    {
       
        // Chemin du PDF d'entrée
        $inputPdfPath = APPPATH . 'Tickets/source_brut/test.pdf';

        // Chemin du dossier de sortie pour les PDF découpés
        $outputPdfPath = APPPATH . 'Tickets/individuel/';

        // Nom de base pour les fichiers de sortie
        $outputFileName = 'page_decoupee_';

        // Configuration de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Initialisation de Dompdf
        $dompdf = new Dompdf($options);
    
        // Charger le PDF d'entrée
       // $dompdf->loadHtml(file_get_contents($inputPdfPath));
       $dompdf->loadHtml("toto");
        $dompdf->setPaper('A4', 'auto'); // Détection automatique de l'orientation

       // debugd($dompdf);
        $dompdf->render();
      
        // Découper le PDF
        $totalPages = $dompdf->getCanvas()->get_page_count();
           // debugd($totalPages);
        for ($page = 1; $page <= $totalPages; $page += 2) 
        {
            // Ajouter une nouvelle page au PDF de sortie
            $dompdf->add_page();

            // Copier les deux pages dans la nouvelle page
            $canvas = $dompdf->getCanvas();
            $canvas->set_page($page);
            $canvas->accept_page_break();
            $canvas->set_page($page + 1);
            $canvas->accept_page_break();

            // Sauvegarder la page découpée en tant que fichier PDF individuel
            $outputFilePath = $outputPdfPath . $outputFileName . $page . '.pdf';
            file_put_contents($outputFilePath, $dompdf->output());

            echo 'PDF découpé avec succès.';
        }

    }

  /*  public function decoup_pdf_imagick()
    {
        $imagick = new \Imagick();

        $inputPdfPath = APPPATH . 'Tickets/source_brut/test.pdf';
        $outputPdfPath = APPPATH . 'Tickets/individuel/';


        // Nom de base pour les fichiers de sortie
        $outputFileName = 'page_decoupee.pdf';

        $imagick->readImage($inputPdfPath);

        $imagick->writeImages($outputPdfPath.$outputFileName, false);
    }*/

    public function decoup_pdf_imagick()
    {
        // Chemin du PDF d'entrée
        $inputPdfPath = APPPATH . 'test/source_brut/test.pdf';

        // Chemin du dossier de sortie pour les images temporaires
        $tempImagePath = APPPATH . 'test/temp/';


        // Chemin du dossier de sortie pour les PDF découpés
        $outputPdfPath = APPPATH . 'test/individuel/';

        // Nom de base pour les fichiers de sortie
        $outputFileName = 'page_decoupee_';

        // Vérifier si les dossiers de sortie existent, sinon les créer
        if (!is_dir($tempImagePath)) {
            mkdir($tempImagePath, 0777, true);
        }

        if (!is_dir($outputPdfPath)) {
            mkdir($outputPdfPath, 0777, true);
        }

        // Initialisation d'Imagick
        $imagick = new \Imagick();
        $imagick->readImage($inputPdfPath);

        // Découper le PDF
        $totalPages = $imagick->getNumberImages();

        for ($page = 0; $page < $totalPages; $page += 2) {
            // Extraire deux pages consécutives et les fusionner en une seule image
            $imagick->setIteratorIndex($page);
            $image1 = $imagick->getImage();
            $imagick->setIteratorIndex($page + 1);
            $image2 = $imagick->getImage();


    
            
            $combinedImage = $image1->appendImages($image2);

              // Ajuster la résolution et la qualité de l'image
              $combinedImage->setImageResolution(300, 300); // Résolution en DPI
              $combinedImage->setImageCompressionQuality(100); // Qualité de compression (0-100)
  

            // Enregistrer l'image temporaire
            $tempImagePathPage = $tempImagePath . 'temp_page_' . $page . '.png';
            $combinedImage->writeImage($tempImagePathPage);

            // Sauvegarder l'image temporaire en tant que fichier PDF individuel
            $outputFilePath = $outputPdfPath . $outputFileName . $page . '.pdf';
            $this->convertImageToPdf($tempImagePathPage, $outputFilePath);
        }

        // Nettoyer les images temporaires
        $this->cleanTemporaryImages($tempImagePath);

        echo 'PDF découpé avec succès.';
    }

    private function convertImageToPdf($inputImagePath, $outputPdfPath)
    {
        $imagick = new \Imagick();
        $imagick->readImage($inputImagePath);
        $imagick->setImageFormat('pdf');
        $imagick->writeImages($outputPdfPath, true);
    }

    private function cleanTemporaryImages($tempImagePath)
    {
        array_map('unlink', glob($tempImagePath . '*'));
        rmdir($tempImagePath);
    }


    public function decoup_pdf_mpdf()
    {
       /*         

                    $mpdf = new \Mpdf\Mpdf();


            $pagecount = $mpdf->SetSourceFile($inputPdfPath);
            $tplId = $mpdf->ImportPage($pagecount);
            $mpdf->SetPageTemplate($tplId);

            // Do not add page until page template set, as it is inserted at the start of each page
            $mpdf->AddPage();

            $mpdf->WriteHTML('Hello World');

            // The template $tplId will be inserted on all subsequent pages until (optionally)
            // $mpdf->SetPageTemplate();

            $mpdf->Output();*/
        //
      

        // Chemin du PDF d'entrée
       

        // Chemin du dossier de sortie pour les PDF découpés
        $inputPdfPath = APPPATH . 'Tickets/source_brut/test.pdf';
        $outputPdfPath = APPPATH . 'Tickets/individuel/';


        // Nom de base pour les fichiers de sortie
        $outputFileName = 'page_decoupee_';

        // Initialisation de mPDF
       // $mpdf = new \Mpdf\Mpdf();

        // Lecture du PDF d'entrée
        $mpdf = new \Mpdf\Mpdf();
        

        $pagecount = $mpdf->SetSourceFile($inputPdfPath);

       
        
        //Découper le PDF
        for ($page = 1; $page <= $pagecount; $page += 2) {
            // Ajouter une nouvelle page au PDF de sortie
           
            $mpdf->SetSourceFile($inputPdfPath);
            // Importer les deux pages du PDF d'entrée
            $tplId1 = $mpdf->importPage($page);
            $tplId2 = $mpdf->importPage($page + 1);

            // Utiliser les deux pages sur la page de sortie
            $mpdf->SetPageTemplate($tplId1);

            $mpdf->AddPage();
            $mpdf->WriteHTML($tplId1);


            // Sauvegarder la page découpée en tant que fichier PDF individuel
            $outputFilePath = $outputPdfPath . $outputFileName . $page . '.pdf';
            $mpdf->Output($outputFilePath, 'F');
        }

        echo 'PDF découpé avec succès.';
    }

       


    public function setStatut($id_ticket) 
    {
        if(!$this->autorisationManager->is_autorise("partenaire_social_u"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }
        $statut=$this->request->getVar("statut");


  
        $this->ticketModel->setStatut($id_ticket,$statut);

        


        echo view($this->module.'\form_ticket_statut', [
            "ticket" => $this->ticketModel->get_ticket($id_ticket),
            "statut_ticket"=>$this->ticketModel->get_statut()       

          
            
        ]);

   


        //echo json_encode($data);

       
    }

    public function setCommentaire()
    {
      
        if($this->request->getVar("id_ticket"))
        {
            $this->ticketModel->setCommentaire($this->request->getVar("id_ticket"),$this->request->getVar("commentaire_ticket"));


            echo view($this->module.'\form_ticket_commentaire', [
                "ticket" => $this->ticketModel->get_ticket($this->request->getVar("id_ticket"))

                
            ]);
        }
    }

    public function setNumCode()
    {
      
        if($this->request->getVar("id_ticket"))
        {
            $this->ticketModel->setNumCode($this->request->getVar("id_ticket"),$this->request->getVar("num_code_ticket"));

            $ticket=$this->ticketModel->getListTicket($id_partenaire_culturel=0,$annee_select=0,$mois_select=0,$this->request->getVar("id_ticket"),$this->request,$orderBy=null,$orderDirection=null);

            $this->datas->ticket=$ticket;
            $this->datas->statuts=$this->list_BarCodeModel->get_statut();
            $this->datas->statuts_ticket=$this->ticketModel->get_statut();
            echo view("Ticket\list_ticket_td",  (array) $this->datas);



           /* echo view($this->module.'\form_ticket_num_code', [
                "ticket" => $this->ticketModel->get_ticket($this->request->getVar("id_ticket"))

                
            ]);*/
        }
    }



}