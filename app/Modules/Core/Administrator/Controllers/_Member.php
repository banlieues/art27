<?php

namespace Administrator\Controllers;

use Components\Libraries\Hash;

use Base\Controllers\BaseController;

use Administrator\Models\IdentificationModel;
use Administrator\Models\UserProfileModel;
use Administrator\Models\UserModel;

use Lists\Models\RoleModel;
use Components\Libraries\ComponentOrderBy;

class Member extends BaseController
{
    protected $request;

    public function __construct()
    {
        if(session('loggedUserRoleId')!=1)
        {
            header("Location:" . base_url("identification/logout"));
        }

        parent::__construct(__NAMESPACE__);

        helper('icons');
        
        $this->datas->context = "member";
        $this->datas->title = lang('Member.title');
        $this->datas->subtitle = lang('Member.edit');
        $this->datas->titleView = 'Members List';
        $this->datas->icon = icon('users');
        
        $this->IdentificationModel = new IdentificationModel();
        $this->UserProfileModel = new UserProfileModel();
        $this->ListRoleModel = new RoleModel();
        $this->UserModel=new UserModel();
        // $request=$this->request;

        $this->componentOrderBy = new ComponentOrderBy();

    }

    public function index()
    {   
        $orderBy = $this->componentOrderBy->getOrderBy("nom, prenom, username", $this->request);
        $orderDirection = $this->componentOrderBy->getOrderDirection("ASC", $this->request);

        $fieldsOrder=
        [
            "delete" => [null, false],
            "id" => ["Id", true],
            "role" => ["Type d'utilisateur", true],
            "nom" => ["Nom", true],
            "prenom" => ["Prénom", true],
            "nom, prenom, username" => ["Login", true],
            "email" => ["E-mail", true],
            "updated_at" => ["Depuis", true],
            "is_actif" => ["Statut", true],
        ];

        $this->datas->users_infos = $this->UserModel->getListUsers($this->request, $orderBy, $orderDirection);
        $this->datas->users_total = $this->UserModel->pager->getTotal();
        $this->datas->pager = $this->IdentificationModel->pager;
        $this->datas->page = $this->request->getGet('page') ?? 1;
        $this->datas->getTh = $this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);

        // $data = [
        //     'title' => lang('Member.title'),
        //     'subtitle' => lang('Member.view'),
        //     'titleView' => 'Members List',
        //     'icon' => icon('users'),
        //     'context' => $this->context,
        //     'users_infos' => $users_infos,
        //     'users_total' => $users_total,
        //     'pager' => $pager,
        //     'page' => $page,
        //     "itemSearch"=>$this->request->getVar("itemSearch"),
        //     "getTh"=>$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request)

        // ];

        return view($this->module . '\member/index', (array) $this->datas);
    }

    public function export()
    {
        echo $this->UserModel->export();
    }

    public function add()
    {
        $users_roles_total = $this->ListRoleModel->countAll();

        $this->datas->users_roles_total = $users_roles_total;

        if (!$this->request->getPost()) 
        {
            return view($this->module . '\member/add', (array) $this->datas);
        }

        $validation = $this->validate([
            'username' => [
                'rules' => 'required|is_unique[user_accounts.username]',
                'errors' => [
                    'required' => " Nom d'utilisateur obligatoire...",
                    'is_unique' => "Ce nom d'utilisateur est déjà utilisé ..."
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[user_accounts.email]',
                'errors' => [
                    'required' => "Email est obligatoire ...",
                    'valid_email' => "L'email est invalide...",
                    'is_unique' => "Cet email est déjà utilisé ..."
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Mot de passe obligatoire...',
                    'min_length' => 'Le mot de passe doit avoir 5 caractères ...',
                    'max_length' => 'Le mot de passe doit avoir un maximum of 12 caractères ...',
                    'regex_match' => "Le mot de passe ne peut avoir d'espace ...",
                ]
            ],
            'confirm' => [
                'rules' => 'required|min_length[5]|max_length[12]|matches[password]|regex_match[/^\S*$/u]',
                'errors' => [
                    'required' => 'Le mot de passe de confirmation est obligatoire ...',
                    'min_length' => 'Le mot de passe de confirmation doit avoir 5 caractères ...',
                    'max_length' => 'Le mot de passe de confirmation doit avoir un maximum of 12 caractères ...',
                    'matches' => 'Les deux mots de passe sont différents...',
                    'regex_match' => "Le mot de passe de confrimation ne peut pas avoir d'espace ...",
                ]
            ],
           
            'is_actif' => [
                'rules' => 'required|numeric|min_length[1]|max_length[1]',
                'errors' => [
                    'required' => 'Actived is required ...',
                    'numeric' => 'Actived must be a numeric value ...',
                    'min_length' => 'Actived must have a minimum of 1 characters ...',
                    'max_length' => 'Actived must have a maximum of 1 characters ...'
                ]
            ],
            'role_id' => [
                'rules' => 'required|numeric|min_length[1]|max_length[1]',
                'errors' => [
                    'required' => 'Role Id is required ...',
                    'numeric' => 'Role Id must be a numeric value ...',
                    'min_length' => 'Role Id must have a minimum of 1 characters ...',
                    'max_length' => 'Role Id must have a maximum of 1 characters ...'
                ]
            ],
        ]);

        if (!$validation)
        {
            $data = array_merge((array) $this->datas, ['validation' => $this->validator]);
            return view($this->module . '\member/add', $data);
        }

        else 
        {
            $username = $this->request->getPost('username');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $passhash = Hash::make($password);
            $token = md5(uniqid(mt_rand()));
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            $valided = 1;
            $is_actif = $this->request->getPost('is_actif');
            $role_id = $this->request->getPost('role_id');
            $nom=$this->request->getPost("nom");
            $prenom=$this->request->getPost("prenom");

            $values = [
                'username' => $username,
                'email' => $email,
                'password' => $passhash,
                'token' => $token,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
                'valided' => 1,
                'is_actif' => $is_actif,
                'role_id' => $role_id,
                'nom'=>$nom,
                'prenom'=>$prenom
            ];

            $insert_account = $this->IdentificationModel->insert($values);

            if (!$insert_account)
            {
                return redirect()->back()->with('danger', 'Erreur ...');
            }

            else
            {
                if ($valided == 0)
                {
                    $sbj = 'Validation de votre compte ' . CRM_NAME;
                    $url = '<a href="'.base_url('identification/verify?token='.$token.'').'">Ici</a>';
                    $msg = '<p>Bonjour '.$username.',</p>';
                    $msg .= '<p>Un administrateur a créé un nouveau compte pour vous.</p>';
                    $msg .= '<p>Le mot de passe est : <b>' . $password . '</b></p>';
                    $msg .= "<p>Clique ' . $url . ' pour valider votre email et activer votre compte d'utilisateur.</p>";
                    $msg .= '<p class="text-success"><b>THE ' . CRM_NAME . ' TEAM</b></p>';

                    // send_email_to($from, $to, $cc, $bcc, $subject, $message)
                    send_email_to(CRMAIL, $email, '', '', $sbj, $msg);
                }
                else
                {
                    $values_profil=[

                        "user_id"=>$insert_account,
                        "role_id"=>$role_id,
                        "avatar"=>"default.png",
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                       "phone"=>$this->request->getPost('phone'),
                        "gsm"=>$this->request->getPost('gsm'),
                        "website"=>$this->request->getPost('website'),


                    ];

                    $this->IdentificationModel->insertProfil($values_profil);

                    $sbj = 'Création de votre compte ' . CRM_NAME;
                    $msg = '<p>Bonjour ' . $username . ', </p>';
                    $msg .= '<p>Un administrateur a créé un nouveau compte pour vous.</p>';
                    $msg .= '<p>Accès: <a href="' . base_url() . '">' . base_url() . '</a></p>';
                    $msg .= "Votre login est : <b> $username </b>";
                    $msg .= '<p>Le mot de passe est : <b>' . $password . '</b></p>';
                    $msg .= '<p class="text-success"><b>THE ' . CRM_NAME . ' TEAM</b></p>';

                    send_email_to(CRMAIL, $email, '', '', $sbj, $msg);
                }

                return redirect()->to('member')->with('success', "Le compte de l'utilisateur a été créé et un mail contenant les informations du compte a été envoyé à l'utilisateur  $username ...");
            }
        }
    }

    public function valid()
    {
        $member_id = $this->request->getPost('id');
        $page = $this->request->getPost('page') ?? 1;

        if (isset($member_id) && !empty($member_id) && $member_id > 0)
        {
            $updated_at = date('Y-m-d H:i:s');
            $updated_by = session('loggedUserId');
            $valided = $this->request->getPost('valided') ? 0 : 1;

            $values = [
                'updated_at' => $updated_at, 
                'updated_by' => $updated_by, 
                'valided' => $valided, 
            ];

            $update_user_account = $this->IdentificationModel->update($member_id, $values);

            if ($update_user_account)
            {
                return redirect()->to('member/?page='.$page)->with('success', 'Members list updated with success ...');
            }

            return redirect()->to('member')->with('danger', 'Members list updated without success ...');
        }

        return redirect()->to('member')->with('danger', 'Sorry, this member does not exist ... ');
    }

    public function activate()
    {
        $member_id = $this->request->getPost('id');
        $page = $this->request->getPost('page') ?? 1;

        if (isset($member_id) && !empty($member_id) && $member_id > 0)
        {
            $updated_at = date('Y-m-d H:i:s');
            $updated_by = session('loggedUserId');
            $is_actif = $this->request->getPost('is_actif') ? 0 : 1;

            $values = [
                'updated_at' => $updated_at, 
                'updated_by' => $updated_by, 
                'is_actif' => $is_actif, 
            ];

            $update_user_account = $this->IdentificationModel->update($member_id, $values);

            if ($update_user_account)
            {
                return redirect()->to('member/?page='.$page)->with('success', 'Members list updated with success ...');
            }

            return redirect()->to('member')->with('danger', 'Members list updated without success ...');
        }

        return redirect()->to('member')->with('danger', 'Sorry, this member does not exist ... ');
    }

    public function details()
    {
        $member_id = $this->request->getGet('id');

        if (isset($member_id) && !empty($member_id) && $member_id > 0)
        {
            $user = sessionUser($member_id);
            
            if (isset($user) && !empty($user))
            {
                $users_roles_total = $this->ListRoleModel->countAll();
                $page = $this->request->getGet('page') ?? 1;

                $this->datas->member_id = $member_id;
                $this->datas->user = $user;
                $this->datas->users_roles_total = $users_roles_total;
                $this->datas->page = $page;

                return view($this->module . '\member/details', (array) $this->datas);
            }

            return redirect()->to('member')->with('danger', 'Sorry, this member does not exist ... ');
        }

        return redirect()->back()->with('danger', 'Something is wrong ...');
    }

    public function edit()
    {
        $member_id = $this->request->getGet('id');

        if (isset($member_id) && !empty($member_id) && $member_id > 0)
        {
            $user = sessionUser($member_id);
            
            if (isset($user) && !empty($user))
            {
                $users_roles_total = $this->ListRoleModel->countAll();
                $page = $this->request->getGet('page') ?? 1;

                $this->datas->member_id = $member_id;
                $this->datas->user = $user;
                $this->datas->users_roles_total = $users_roles_total;
                $this->datas->page = $page;

                return view($this->module . '\member/edit', (array) $this->datas);
            }

            return redirect()->to('member')->with('danger', 'Sorry, this member does not exist ... ');
        }

        return redirect()->back()->with('danger', 'Something is wrong ...');
    }

    public function save()
    {
        $member_id = $this->request->getPost('id');

        if (isset($member_id) && !empty($member_id) && $member_id > 0)
        {
            $user = sessionUser($member_id);
            $page = $this->request->getPost('page') ?? 1;

            $this->datas->user = $user;
            $this->datas->member_id = $member_id;
            $this->datas->page = $page;

            $validation = $this->validate([
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email is required ...',
                        'valid_email' => 'Invalid email detected ...',
                    ]
                ],
                'valided' => [
                    'rules' => 'required|numeric|min_length[1]|max_length[1]',
                    'errors' => [
                        'required' => 'Valided is required ...',
                        'numeric' => 'Valided must be a numeric value ...',
                        'min_length' => 'Valided must have a minimum of 1 characters ...',
                        'max_length' => 'Valided must have a maximum of 1 characters ...'
                    ]
                ],
                'is_actif' => [
                    'rules' => 'required|numeric|min_length[1]|max_length[1]',
                    'errors' => [
                        'required' => 'Actived is required ...',
                        'numeric' => 'Actived must be a numeric value ...',
                        'min_length' => 'Actived must have a minimum of 1 characters ...',
                        'max_length' => 'Actived must have a maximum of 1 characters ...'
                    ]
                ],
                'role_id' => [
                    'rules' => 'required|numeric|min_length[1]|max_length[1]',
                    'errors' => [
                        'required' => 'Role Id is required ...',
                        'numeric' => 'Role Id must be a numeric value ...',
                        'min_length' => 'Role Id must have a minimum of 1 characters ...',
                        'max_length' => 'Role Id must have a maximum of 1 characters ...'
                    ]
                ],
            ]);

            if (!$validation)
            {
                $data = array_merge((array) $this->datas, ['validation' => $this->validator]);
                return view($this->module . '\member/edit', $data);
            }

            else
            {
                $email = $this->request->getPost('email');
                $valided = $this->request->getPost('valided');
                $is_actif = $this->request->getPost('is_actif');
                $role_id = $this->request->getPost('role_id');
                $updated_at = date('Y-m-d H:i:s');
                $updated_by = session('loggedUserId');

                $values = [
                    'email' => $email,
                    'valided' => $valided,
                    'is_actif' => $is_actif,
                    'updated_at' => $updated_at,
                    'updated_by' => $updated_by,
                    'role_id' => $role_id,
                ];

                $update_user_account = $this->IdentificationModel->update($member_id, $values);

                if ($update_user_account)
                {
                    return redirect()->to('member/?page='.$page)->with('success', 'User account updated with success ...');
                }

                return redirect()->back()->with('danger', 'Something is wrong ...');
            }
        }

        return redirect()->back()->with('danger', 'Something is wrong ...');
    }

    public function delete()
    {
        if (!$this->request->getPost()) 
        {
            $member_id = $this->request->getGet('id');
            $users_infos = $this->IdentificationModel->find($member_id);

            if (isset($users_infos) && !empty($users_infos))
            {
                $this->datas->member_id = $member_id;
                $this->datas->users_infos = $users_infos;

                return view($this->module . '\member/delete', (array) $this->datas);
            }

            return redirect()->to('member')->with('danger', "Désolé, cet utilisateur n'existe pas ... ");
        }

        $member_id = $this->request->getPost('id');

        if (isset($member_id) && !empty($member_id) && $member_id > 0)
        {
            $delete_account = $this->IdentificationModel->delete(['id' => $member_id]);
            $delete_profile = $this->UserProfileModel->delete(['user_id' => $member_id]);

            $this->UserProfileModel->deleteRelation($member_id);
            

            if ($delete_account && $delete_profile)
            {
                return redirect()->to('member')->with('success', "L'utilisateur a été supprimé ...");
            }

            return redirect()->to('member')->with('danger', "Erreur! Impossible de supprimer l'utilsiateur ...");
        }

        return redirect()->back()->with('danger', "Erreur ...");
    }
}
