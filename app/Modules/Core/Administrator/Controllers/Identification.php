<?php

namespace Administrator\Controllers;

use Administrator\Models\UserModel;
use Base\Controllers\BaseController;
use Components\Libraries\Hash;

class Identification extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->UserModel = new UserModel();

        $this->datas->title = lang('Identification.identification');
        $this->datas->icon = 'fa fa-lock';
        $this->datas->context = "identification";
    }

    public function index()
    {
        if (session()->has('loggedUserId'))
        {
            $url = $this->request->getGet('redirect') ?? '';
            return redirect()->to($url);
        }

        // if (session()->has('loggedUserId'))
        // {
        //     return redirect()->to('/')->with('danger', 'You are already logend in  ...');
        // }

        $this->datas->subtitle = lang('Identification.pleaselogin');

        // $data = [
        //     'title' => lang('Identification.identification'),
        //     'subtitle' => lang('Identification.pleaselogin'),
        //     'icon' => 'fa fa-lock',
        //     'context' => $this->context,
        // ];


        return view($this->module . '\identification/login', (array) $this->datas);
    }

    // TO LOGIN A EXISTING USER
    public function login()
    {
        $this->datas->subtitle = lang('Identification.pleaselogin');

        if(!$this->request->getPost()) :

            if (session()->has('loggedUserId')) :
                $user = sessionUser();
                $url = $this->request->getGet('redirect') ?? '';
                return redirect()
                    ->to($url)
                    ->with('warning', 'Vous êtes actuellement connecté en tant que <b>' . $user->username . '</b>.');
            endif;

            // if (session()->has('loggedUserId')) :
            //     return redirect()
            //         ->to('/')
            //         ->with('warning', "Vous êtes déjà connecté en tant que " . $this->user->username . " (" . $this->user->prenom . " " . $this->user->nom . ")");
            // endif;

            // session()->setFlashdata('item', 'value');
            return view($this->module . '\identification/login', (array) $this->datas);
        endif;

        // $validation = $this->validate([
        //     'username' => [
        //         'rules' => 'required|is_not_unique[user_accounts.username]|regex_match[/^\S*$/u]',
        //         'errors' => [
        //             'required' => "Mauvais login ou mot de passe",
        //             // 'is_not_unique' => 'Username not exist ...',
        //             // 'regex_match' => 'Username must not have space ...',
        //             'is_not_unique' => 'Mauvais login ou mot de passe',
        //             'regex_match' => 'Mauvais login ou mot de passe',
        //         ]
        //     ],
        //   /*  'password' => [
        //         'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
        //         'errors' => [
        //             'required' => 'Password is required ...',
        //             // 'min_length' => 'Password must have a minimum of 5 characters ...',
        //             // 'max_length' => 'Password must have a maximum of 12 characters ...',
        //             // 'regex_match' => 'Password must not have space ...',
        //             'min_length' => 'Password error ...',
        //             'max_length' => 'Password error ...',
        //             'regex_match' => 'Password error ...',
        //         ]
        //     ]*/
        // ]);
        $validation = \Config\Services::validation();
        
        if ($validation->run($this->request->getPost(), $this->module . 'Login') == FALSE)
        // if (!$validation)
        {
            $this->datas->validation = $validation;

            return view($this->module . '\identification/login', (array) $this->datas);
        }
        else 
        {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $query = ['username' => $username, 'valided' => 1, 'is_actif' => 1];
            $user_infos = $this->UserModel->where($query)->first();

            if (!$user_infos)
            {
                $message = 'Bonjour <b>' . $username . '</b>, vous devez valider votre compte avant de pouvoir vous connecter ...';
                return redirect()->to(base_url('identification'))->with('danger', $message);
            }

            $check_password = Hash::check($password, $user_infos->password);

            if (!$check_password)
            {
                if(empty($user_infos->password) && $user_infos->source=="spip")
                {
                    return redirect()->to('/identification/reset_procedure')->with('info', "Il est nécessaire de mettre à jour votre mot de passe!. <br>Veuillez indiquer l'adresse mail de votre compte!");
                }

                return redirect()->to('/identification')->with('danger', 'Mauvais login ou mot de passe');
            }

            $user = sessionUser($user_infos->id);
            $avatar = file_exists(AVATAR_PATH . $user->avatar) ? $user->avatar : AVATAR_DEFAULT;
            // $id_user = $user_infos->id;
            // $role_id = $user_infos->role_id;

            // $avatar = $this->UserProfileModel->where('user_id', $user_infos->id)->first();
            // $avatar = $avatar['avatar'];

            $session = [
                'loggedUserId' => $user->id, 
                'loggedUserRoleId' => $user->role_id, 
                'loggedUserAvatar' => $avatar,
                'loggedUserName' => $user->username, 
                'loggedUserMail' => $user->email
            ];

            session()->set($session);

            if($this->session->get("url_distant") && $user->role_id==1)
            {
                return redirect()
                    ->to(base_url("/user/form_registration/$user->id?is_distant=1&id_activity=" . $this->session->get("url_distant")));
            }

            $message = 'Bienvenue <div class="d-inline fw-bold mx-2"> ' . $user->prenom . ' </div> °\_(ツ)_/°';
            
            if($user->role_id==2) :
                return redirect()->to(base_url("user/profile"))->with('success', $message);

            else :
                $url = $this->request->getGet('redirect') ?? 'dashboard';
                return redirect()->to($url)->with('success', $message);

            endif;

            // $message = "Bienvenue <b> $user->username </b> - $user->prenom";

            // return redirect()->to('/')->with('success', $message);
        }
    }

    // TO REGISTER A NEW USER
    // THIS CREATE A USER ACCOUBT WITHOUT DEFAULT USER PROFILE
    public function register()
    {
        $this->datas->subtitle = lang('Identification.pleaseregister');

        // $data = [
        //     'title' => lang('Identification.identification'),
        //     'subtitle' => lang('Identification.pleaseregister'),
        //     'icon' => 'fa fa-lock',
        //     'context' => $this->context,
        // ];

        if (!$this->request->getPost()) 
        {
            if (session()->has('loggedUserId'))
            {
                return redirect()->to('/')->with('danger', 'You are already logend in  ...');
            }
            return view($this->module . '\identification/register', (array) $this->datas);
        }

        // $validation = $this->validate([
        //     'username' => [
        //         'rules' => 'required|is_unique[user_accounts.username]|regex_match[/^\S*$/u]',
        //         'errors' => [
        //             'required' => 'Username is required ...',
        //             'is_unique' => 'This username is already used ...',
        //             'regex_match' => 'Username must not have space ...',
        //         ]
        //     ],
        //     'email' => [
        //         'rules' => 'required|valid_email|is_unique[user_accounts.email]|regex_match[/^\S*$/u]',
        //         'errors' => [
        //             'required' => 'Email is required ...',
        //             'valid_email' => 'This email is invalid ...',
        //             'is_unique' => 'This email is already used ...',
        //             'regex_match' => 'Email must not have space ...',
        //         ]
        //     ],
        //     'password' => [
        //         'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
        //         'errors' => [
        //             'required' => 'Password is required ...',
        //             'min_length' => 'Password must have a minimum of 5 characters ...',
        //             'max_length' => 'Password must have a maximum of 12 characters ...',
        //             'regex_match' => 'Password must not have space ...',
        //         ]
        //     ],
        //     'confirm' => [
        //         'rules' => 'required|min_length[5]|max_length[12]|matches[password]|regex_match[/^\S*$/u]',
        //         'errors' => [
        //             'required' => 'Confirm password is required ...',
        //             'min_length' => 'Confirm password must have a minimum of 5 characters ...',
        //             'max_length' => 'Confirm password must have a maximum of 12 characters ...',
        //             'matches' => 'Confirm password not match to password ...',
        //             'regex_match' => 'Password must not have space ...',
        //         ]
        //     ]
        // ]);

        $validation = \Config\Services::validation();
        if ($validation->run($this->request->getPost(), $this->module . 'Registration') == FALSE)
        // if (!$validation)
        {
            $this->datas->validation = $validation;

            return view($this->module . '\identification/register', (array) $this->datas);
        }

        else 
        {
            $username = $this->request->getPost('username');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $password = Hash::make($password);
            $token = md5(uniqid(mt_rand()));
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            $created_by = DEFAULT_SERVICE_ID;
            $updated_by = DEFAULT_SERVICE_ID;
            $valided = VALIDED_BY_DEFAULT;
            $is_actif = ACTIVED_BY_DEFAULT;
            $role_id = DEFAULT_ROLE_ID;

            $values = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'token' => $token,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
                'created_by' => $created_by,
                'updated_by' => $updated_by,
                'valided' => $valided,
                'is_actif' => $is_actif,
                'role_id' => $role_id,
            ];

            $insert_account = $this->UserModel->insert($values);

            if (!$insert_account)
            {
                $message = 'Something is wrong <b>'.$username.'</b>, registration failed ...';
                return redirect()->back()->with('danger', $message);
            }

            else
            {
                $sbj = 'User email verification and user account validation';
                $url = '<a href="'.base_url('identification/verify?token='.$token.'').'">here</a>';
                $msg = '<p>Hi <b>'.$username.'</b>,</p>';
                $msg .= '<p>Click '.$url.' to validate your email adress, activate your user account and created your default user profile.</p>';
                $msg .= '<p><b>' . CRM_NAME . ' TEAM</b></p>';

                send_email_to($email, '', '', $sbj, $msg);
                $message = 'Registration successfully <b>'.$username.'</b>, please verify your email to validate your account before login ...';
                return redirect()->to('/identification')->with('success', $message);
            }
        }
    }

    // TO VERIFY EMAIL ADDRESS
    // VALIDATE AND ACTIVATE THIS USER ACCOUNT
    // AND CREATE A DEFAULT USER PROFILE
    public function verify()
    {
        $token = $this->request->getGet('token');

        if (!isset($token) || empty($token)) 
        {
            return redirect()->to('/identification/login')->with('danger', 'Token invalid détecté, echec de la validation ...');
        }

        $user_infos = $this->UserModel->where('token', $token)->first();

        if (!$user_infos)
        {
            return redirect()->to('/identification/reverify')->with('danger', 'Désolé, ce token est invalide ...');
        }

        else
        {
            $user_id = $user_infos->id;

            $values = [
                'token' => md5(uniqid(mt_rand())), 
                'updated_at' => date('Y-m-d h:m:s'), 
                'updated_by' => session('loggedUserId'), 
                'created_by' => session('loggedUserId'), 
                'valided' => 1, 
                'is_actif' => 1, 
            ];

            $update_account = $this->UserModel->update($user_id, $values);

            if (!$update_account)
            {
                return redirect()->back()->with('warning', 'Echec de la mise à jour de votre compte ...');
            }

            // else
            // {
                // $role_id = DEFAULT_ROLE_ID;
                // $gender_id = DEFAULT_GENDER_ID;
                // $country_id = DEFAULT_COUNTRY_ID;
                // $avatar = AVATAR_DEFAULT;
                // $website = DEFAULT_WEBSITE;
                // $phone = DEFAULT_PHONE;
                // $gsm = DEFAULT_GSM;
                // $birthday = DEFAULT_BIRTHDAY;
                // $created_at = date('Y-m-d H:i:s');
                // $updated_at = date('Y-m-d H:i:s');
                // $created_by = DEFAULT_SERVICE_ID;
                // $updated_by = DEFAULT_SERVICE_ID;
                // $is_actif = 1;

                // $values = [
                //     // 'id' => AUTO_INCREMENT,
                //     'user_id' => $user_id, 
                //     'role_id' => $role_id, 
                //     'gender_id' => $gender_id, 
                //     'country_id' => $country_id, 
                //     'avatar' => $avatar, 
                //     'website' => $website, 
                //     'phone' => $phone, 
                //     'gsm' => $gsm, 
                //     'birthday' => $birthday, 
                //     'created_at' => $created_at, 
                //     'updated_at' => $updated_at, 
                //     'created_by' => $created_by, 
                //     'updated_by' => $updated_by, 
                //     'is_actif' => $is_actif,
                // ];

                // $insert_profile = $this->UserProfileModel->insert($values);

                // if (!$insert_profile)
                // {
                //     return redirect()->back()->with('danger', 'Le profil a été ajouté avec succès ...');
                // }

                // else
                // {
                //     $username = $user_infos->username;
                //     $message = 'Hi <b>'. $username .'</b>, your email adress is valided, your user account is actived and your default user profile is created with success ...';
                //     return redirect()->to('/identification/login')->with('success', $message);
                // }
            // }
        }
    }

    // TO REVERIFY EMAIL ADRESS
    // RESET THIS USER ACCOUNT TOKEN AND SEND A NEW EMAIL
    public function reverify()
    {
        $this->datas->subtitle = lang('Identification.reverify');

        // $data = [
        //     'title' => lang('Identification.identification'),
        //     'subtitle' => lang('Identification.reverify'),
        //     'icon' => 'fa fa-lock',
        //     'context' => $this->context,
        // ];

        if (!$this->request->getPost()) 
        {
            return view($this->module . '\identification/reverify', (array) $this->datas);
        }

        // $validation = $this->validate([
        //     'email' => [
        //         'rules' => 'required|valid_email|is_not_unique[user_accounts.email]|regex_match[/^\S*$/u]',
        //         'errors' => [
        //             'required' => 'Email est requis ...',
        //             'valid_email' => 'Email est invalide ...',
        //             'is_not_unique' => "Email n'existe pas  ...",
        //             'regex_match' => "Email ne doit pas comporter d'espace ...",
        //         ]
        //     ]
        // ]);

        $validation = \Config\Services::validation();
        if ($validation->run($this->request->getPost(), $this->module . 'Email') == FALSE)
        // if (!$validation)
        {
            $this->datas->validation = $validation;

            return view($this->module . '\identification/reverify', (array) $this->datas);
        }

        else 
        {
            $email = $this->request->getPost('email');
            $user_infos = $this->UserModel->where('email', $email)->first();

            if (!$user_infos)
            {
                return redirect()->to('/identification/reverify')->with('danger', 'Erreur ...');
            }

            else
            {
                $user_id = $user_infos->id;
                $token = md5(uniqid(mt_rand()));
                $updated_at = date('Y-m-d H:i:s');
    
                $values = [
                    'token' => $token,
                    'updated_at' => $updated_at,
                ];

                $update_token = $this->UserModel->update($user_id, $values);

                if (!$update_token)
                {
                    return redirect()->back()->with('danger', 'Erreur ...');
                }

                else
                {
                    $sbj = 'User account reverification';
                    $url = '<a href="'.base_url('identification/verify?token='.$token.'').'">ici</a>';
                    $msg = '<p>Hi '.$user_infos->username.',</p>';
                    $msg .= '<p>Click '.$url.' pour valider votre email et activer votre compte.</p>';
                    $msg .= '<p><b>' . CRM_NAME . ' TEAM</b></p>';

                    send_email_to($email, '', '', $sbj, $msg);
                    return redirect()->to('/identification/reverify')->with('success', 'Email de vérification envoyé avec succès ...');
                }
            }
        }
    }

    // TO FORGOT PASSWORD
    // RESET THIS USER ACCOUNT TOKEN AND SEND A NEW EMAIL
    public function forgot()
    {
        $this->datas->subtitle = lang('Identification.forgetpassword');

        // $data = [
        //     'title' => lang('Identification.identification'),
        //     'subtitle' => lang('Identification.forgetpassword'),
        //     'icon' => 'fa fa-lock',
        //     'context' => $this->context,
        // ];

        if (!$this->request->getPost()) 
        {
            return view($this->module . '\identification/forgot', (array) $this->datas);
        }

        // $validation = $this->validate([
        //     'email' => [
        //         'rules' => 'required|valid_email|is_not_unique[user_accounts.email]|regex_match[/^\S*$/u]',
        //         'errors' => [
        //             'required' => 'Email est requis ...',
        //             'valid_email' => 'Cet email est invalide ...',
        //             'is_not_unique' => "Cet email n'existe pas",
        //             'regex_match' => "Ceci n'est pas une adresse mail ...",
        //         ]
        //     ]
        // ]);

        // $validation = \Config\Services::validation();
        // if($validation->run($this->request->getPost(), $this->module . 'Email') == false)
        // // if (!$validation)
        // {
        //     $this->datas->validation = $validation;

        //     return view($this->module . '\identification/forgot', (array) $this->datas);
        // }

        // else 
        // {
            $email = $this->request->getPost('email');
            $user_infos = $this->UserModel->where('email', $email)->first();

            if (!$user_infos) :
                return redirect()->to('/identification/forgot')->with('danger', "L'adresse encodée n'a pas été retrouvée dans la base de données utilisateurs.");
            else :
                $user_id = $user_infos->id;
                $token = md5(uniqid(mt_rand()));
                $updated_at = date('Y-m-d H:i:s');
    
                $values = [
                    'token' => $token,
                    'updated_at' => $updated_at,
                ];

                $update_token = $this->UserModel->update($user_id, $values);

                if (!$update_token) :
                    return redirect()->back()->with('danger', 'Erreur...');
                else :
                    $sbj = 'Réinitialisation du mot de passe';
                    $url = '<a href="'.base_url('identification/reset?token='.$token.'').'">ici</a>';
                    $msg = '
                        <p> Bonjour ' . $user_infos->username.', </p>
                        <p> Vous avez demandé de mettre à jour votre mot de passe. Veuillez cliquer ici ' . $url . '. </p>
                        ' . signature() . '
                    ';
                    send_email_to($email, '', '', $sbj, $msg);

                    //return redirect()->back()->with('success', 'Email envoyé avec succès ...');
                    return redirect()->to('/identification/login')->with('success', 'Email envoyé avec succès ...');
                endif;
            endif;
        // }
    }


    public function reset_procedure()
    {

        $this->datas->subtitle = "Suite à une mise à jour de notre système, il est nécessaire de recréer votre mot de passe ! <br>Indiquer l'adresse mail de votre compte afin de recevoir un message avec un lien de réinitialisation de votre compte";

        // $data = [
        //     'title' => lang('Identification.identification'),
        //     'subtitle' => "Suite à une mise à jour de notre système, il est nécessaire de recréer votre mot de passe ! <br>Indiquer l'adresse mail de votre compte afin de recevoir un message avec un lien de réinitialisation de votre compte",
        //     'icon' => 'fa fa-lock',
        //     'context' => $this->context,
        // ];

        if (!$this->request->getPost()) 
        {
            return view($this->module . '\identification/reset_procedure', (array) $this->datas);
        }

        // $validation = $this->validate([
        //     'email' => [
        //         'rules' => 'required|valid_email|is_not_unique[user_accounts.email]|regex_match[/^\S*$/u]',
        //         'errors' => [
        //             'required' => 'Email est requis ...',
        //             'valid_email' => 'Cet email est invalide ...',
        //             'is_not_unique' => "Cet email n'existe pas",
        //             'regex_match' => "Ceci n'est pas une adresse mail ...",
        //         ]
        //     ]
        // ]);

        $validation = \Config\Services::validation();
        if ($validation->run($this->request->getPost(), $this->module . 'Email') == FALSE)
        // if (!$validation)
        {
            $this->datas->validation = $validation;

            return view($this->module . '\identification/reset_procedure', (array) $this->datas);
        }

        else 
        {
            $email = $this->request->getPost('email');
            $user_infos = $this->UserModel->where('email', $email)->first();

            if (!$user_infos)
            {
                return redirect()->to('/identification/reset_procedure')->with('danger', 'Erreur...');
            }

            else
            {
                $user_id = $user_infos->id;
                $token = md5(uniqid(mt_rand()));
                $updated_at = date('Y-m-d H:i:s');
    
                $values = [
                    'token' => $token,
                    'updated_at' => $updated_at,
                ];

                $update_token = $this->UserModel->update($user_id, $values);

                if (!$update_token)
                {
                    return redirect()->back()->with('danger', 'Erreur ...');
                }

                else
                {
                    $sbj = 'Réinitialisation du mot de passe';
                    $url = '<a href="'.base_url('identification/reset?token='.$token.'').'">ici</a>';
                    $msg = '<p>Hi '.$user_infos->username.',</p>';
                    $msg .= '<p>Cliquer ici '.$url.' pour mettre à jour votre mot de passe .</p>';
                    $msg .= '<p><b>' . CRM_NAME . ' TEAM</b></p>';
                    send_email_to($email, '', '', $sbj, $msg);

                    //return redirect()->back()->with('success', 'Email envoyé avec succès ...');
                    return redirect()->to('/identification/login')->with('success', 'Email envoyé avec succès ...');
                }
            }
        }
    }

    // TO RESET PASSWORD
    public function reset()
    {
        $this->datas->subtitle = lang('Identification.resetpassword');

        // $data = [
        //     'title' => lang('Identification.identification'),
        //     'subtitle' => lang('Identification.resetpassword'),
        //     'icon' => 'fa fa-lock',
        //     'context' => $this->context,
        // ];

        if (!$this->request->getPost()) :
            $token = $this->request->getGet('token');

            if(!isset($token) || empty($token)) : 
                return redirect()->to('identification')->with('danger', 'Désolé, le token détecté est invalide...');
            endif;

            $user_infos = $this->UserModel->where('token', $token)->first();

            if(!$user_infos) :
                return redirect()->to('/identification')->with('danger', 'Désolé, le token détecté est inconnu ...');
            endif;

            $this->datas->token = $token;

            return view($this->module . '\identification/reset', (array) $this->datas);
        endif;

        $token = $this->request->getPost('token');

        if (!isset($token) || empty($token)) :
            return redirect()->to('identification/reset')->with('danger', 'Désolé, le token détecté est invalide...');
        endif;

        $validation = \Config\Services::validation();
        if($validation->run($this->request->getPost(), $this->module . 'PasswordReset') == FALSE) :

            $this->datas->token = $token;

            return view($this->module . '\identification/reset', (array) $this->datas);
        else :
            $user_infos = $this->UserModel->where('token', $token)->first();

            if (!$user_infos) :
                return redirect()->to('/identification')->with('danger', 'Désolé, ce token est inconnu...');
            else :
                $user_id = $user_infos->id;
                $password = $this->request->getPost('password');
                $password = Hash::make($password);
                $token = md5(uniqid(mt_rand()));
                $updated_at = date('Y-m-d H:i:s');

                $values = [
                    'password' => $password,
                    'token' => $token,
                    'updated_at' => $updated_at,
                ];

                $update_password = $this->UserModel->update($user_id, $values);

                if (!$update_password) :
                    return redirect()->back()->with('danger', 'Le mot de passe a été mis à jour avec succès ...');
                else :
                    return redirect()->to('/identification/login')->with('success', 'Le mot de passe a été mis à jour avec succès ...');
                endif;
            endif;
        endif;
    }

    // TO LOGOUT
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/identification/login')->with('success', 'Vous êtes déconnecté ...');
    }

    public function logout_distance()
    {
        //debug(session()->get());

        $remove=["loggedUserId","loggedUserRoleId","loggedUserAvata","loggedUserName","loggedUserMail"];

        session()->remove($remove);
        return redirect()->to('/identification/login')->with('success', 'Vous êtes déconnecté ...');

    }
}
