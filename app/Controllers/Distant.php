<?php

namespace App\Controllers;

use Administrator\Models\UserModel;
use App\Models\ActivitiesModel;
use App\Models\RegistrationsModel;
use Contact\Models\ContactModel;

use App\Models\UserProfileModel;

use App\Libraries\Hash;

use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

class Distant extends BaseController
{
    protected $activiteModel;
    protected $inscriptionModel;
    protected $contactModel;

    protected $request;

    protected $context;


    public function __construct()
    {
        $this->inscriptionModel=new RegistrationsModel();
        $this->activiteModel=new ActivitiesModel();

        $this->UserModel = new UserModel();
        $this->UserProfileModel = new UserProfileModel();

    }

	public function action($id_activity, $id_contact=0,$id_user=0, $validation=NULL)
    {
       if(session()->get("loggedUserId"))
       {
        return redirect()->to(base_url()."/utilisator/form_registration/".session()->get("loggedUserId")."?is_distant=1&id_activity=".$id_activity);

       }

        session()->set(["is_distant"=>true]);
        session()->set(["url_distant"=>$id_activity]);
                                              

        $dataView = new DataViewConstructor();
        $this->dataGeneratorModel = new DataViewConstructorModel();
        $fields = $this->dataGeneratorModel->getFields();

        $id_user=0;
        $contact=NULL;


            
        if ($id_activity > 0)
        {
            $injectedForm = $this->dataGeneratorModel->getInjectedFormIframe($id_activity);

            $activity=$this->activiteModel->getActivite($id_activity);
         
            $modules= $this->activiteModel->getListModules($id_activity);

            if (empty($injectedForm) || is_null($injectedForm))
            {
                $form_registration = "Pas de formulaire trouvé pour cette action"; 
            }
            else
            {
                $indexes_form = explode(",", trim($injectedForm->fields));

                if ($id_contact > 0)
                {
                    $contact = $this->utilisatorContactModel->getOneContact($id_contact);
                    $indexes_form = $this->dataGeneratorModel->getSubstractFieldWithValues($indexes_form,"contacts",$contact);
                    // debug($contact);
                }
              
               return view("DataView\Views\/injected-form-iframe_distant", [   
                    'title' => "form", 
                    'subtitle' => NULL, 
                    "titleView" => "form", 
                    'context' => $this->context, 
                    'injectedForm' => $injectedForm, 
                    'fields' => $fields, 
                    'dataView' => $dataView, 
                    "id_injected_form" => $injectedForm->id_injected_form, 
                    "is_frame" => TRUE, 
                    "id_user" => $id_user, 
                    "id_activity" => $id_activity, 
                    "id_contact" => $id_contact, 
                    "contact" => $contact, 
                    "indexes_form" => $indexes_form,
                    "no_menu"=>TRUE,
                    "is_distant"=>TRUE,
                    "modules"=>$modules,
                    "form_login"=>true,
                    "activity"=>$activity
                ]); 

            }
        }
        else
        {
            echo "<h4>Pas de formulaire trouvé pour cette action</h4>";
        }
    }


    public function save_registration()
    {
        // List of indexes of form
        $session = \Config\Services::session();
        $indexes = array_keys($this->request->getVar());

        //debug($indexes,true);
        $dataView = new DataViewConstructor();
        $rules = $dataView->getRules($indexes, $this->inscriptionModel->getFields());
        // debug($this->request->getVar(),true);

        $id_activity = $this->request->getVar("id_activity");
        $id_contact = $this->request->getVar("id_contact");
        $id_user = $this->request->getVar("id_user");
        $is_distant=$this->request->getVar("is_distant");

        // debug( $this->request->getVar());
        $data = $this->request->getVar();

        if(isset($data["has_modules"])&&$data["has_modules"])
        {
             $rules["id_modules_5678"]="required";
        }

        if(isset($data["form_login"])&&$data["form_login"])
        {
             $rules['username']=[
                'rules' => 'required|is_unique[user_accounts.username]',
                'errors' => [
                    'required' => " Nom d'utilisateur obligatoire...",
                    'is_unique' => "Ce nom d'utilisateur est déjà utilisé ..."
                ]];

                $rules['password']=[
                    'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
                    'errors' => [
                        'required' => 'Mot de passe obligatoire...',
                        'min_length' => 'Le mot de passe doit avoir 5 caractères ...',
                        'max_length' => 'Le mot de passe doit avoir un maximum of 12 caractères ...',
                        'regex_match' => "Le mot de passe ne peut avoir d'espace ...",
                    ]
                ];
                $rules['confirm']=[
                    'rules' => 'required|min_length[5]|max_length[12]|matches[password]|regex_match[/^\S*$/u]',
                    'errors' => [
                        'required' => 'Le mot de passe de confirmation est obligatoire ...',
                        'min_length' => 'Le mot de passe de confirmation doit avoir 5 caractères ...',
                        'max_length' => 'Le mot de passe de confirmation doit avoir un maximum of 12 caractères ...',
                        'matches' => 'Les deux mots de passe sont différents...',
                        'regex_match' => "Le mot de passe de confrimation ne peut pas avoir d'espace ...",
                    ]
                    ];  
        }

       
        if (!$this->validate($rules) && !empty($rules)) 
        {
            // debug($this->validator,true);
            if ($this->request->getVar('typeDataView') == "create")
            {
                echo $this->action($id_activity, $id_contact,$id_user, $this->validator);
            }
            else 
            {
                echo $this->action($id_activity, $id_contact,$id_user, $this->validator);
            }
        }

        else 
        {
             // treatment of data, on dit 
            // debug($this->request->getVar(),true);
            // print_r($this->request->getVar("id_inscription")); die();
            $data=$this->request->getVar();
            //debug($this->request->getVar(),true);
            if(isset($data["has_modules"])&&$data["has_modules"])
            {
                foreach($data["id_modules_5678"] as $id_module_5678)
                {
                    $data["id_activity"]=$id_module_5678;
                    $id_inscription_save = $dataView->saveData($indexes, $data, $this->inscriptionModel->getFields(), $this->inscriptionModel->getTable());  
                }
            }
            else
            {
               // debug($indexes);
                //debug($data);
                //debug($this->inscriptionModel->getFields());
                //debug($this->inscriptionModel->getTable());

                $id_inscription_save = $dataView->saveData($indexes, $data, $this->inscriptionModel->getFields(), $this->inscriptionModel->getTable());  

            }

            if(isset($data["form_login"])&&$data["form_login"])
            {
                //on crée un nouvel utilisateur et on lui envoie son login et mot de passe

                $username = slugify_name_file($this->request->getPost('username'));
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $passhash = Hash::make($password);
                $token = md5(uniqid(mt_rand()));
                $created_at = date('y-m-d h:m:s');
                $updated_at = date('y-m-d h:m:s');
                


                $values = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $passhash,
                    'token' => $token,
                    "avatar"=>"default.png",
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'valided' => 1,
                    'actived' => 1,
                    'role_id' => 2,
             
                ];
    
                $insert_account = $this->UserModel->insert($values);

                // $values_profil=[

                //     "user_id"=>$insert_account,
                //     "role_id"=>2,
                //     'created_at' => $created_at,
                //     'updated_at' => $updated_at,


                // ];

                // $this->IdentificationModel->insertProfil($values_profil);

                //On met en relation contact avec la personne, on doit obtenir le
                $id_contact_create=$this->inscriptionModel->get_contact($id_inscription_save);
                $value_gestion["id_user"]=$insert_account;
                $value_gestion["id_contact"]=$id_contact_create;
                $value_gestion["date"]=$created_at;

                $this->UserModel->UserContactInsert($value_gestion);
                

                //On envoie un mail de connection

                $sbj = 'Création de votre compte CEMEA';
                $msg  = '<p>Bonjour '.$username.',</p>';
                $msg .= "<p>Voici les informations d'accès à votre compte</p>";
                $msg.= '<p>Accès: <a href="'.base_url().'">'.base_url().'</a></p>';
                $msg .="Votre login est : <b>$username</b>";
                $msg .= '<p>Le mot de passe est : <b>'.$password.'</b></p>';
                $msg .= '<p><b>CEM<span style="color:#dc3545">ÉA</span>A TEAM</b></p>';

                send_email_to($email, '', '', $sbj, $msg);

                //Et on le connecte
                $data_session = [
                    'loggedUserId' => $insert_account, 
                    'loggedUserRoleId' => 2, 
                    'loggedUserAvatar' => "default.png", 
                    'loggedUserName' => $username, 
                ];

                session()->set($data_session);

            }
            
            if($this->request->getVar('typeDataView')=="create")
            {
                session()->setFlashdata('success', "L'inscription a été enregistrée et votre compte a été créé");
            }

            else 
            {
                session()->setFlashdata('success', "L'inscription a été enregistrée et votre compte a été créé");
            }

            return redirect()->to(base_url()."/distant/confirmation_enregistrement");
        }
    }

    public function confirmation_enregistrement()
    {
        echo "<h4>Votre demande d'inscription a été effectuée et votre compte a été créé.<br> Vous allez recevoir un mail avec les informations de connexion à votre compte.</h4>";
    }

    public function testSite($id_activity=5583)
    {
        $data["id_activity"]=$id_activity;
        $data["titleView"]="Test Site";
        $data["context"]="Test Site";


        return view("templates/index_test",$data);


    }





}