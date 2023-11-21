<?php
/**
 * This is User Controller 
**/

namespace Administrator\Controllers;

use Administrator\Models\AvatarModel;
use Administrator\Models\UserModel;
use Autorisation\Libraries\AutorisationLibrary;
use Autorisation\Models\AutorisationModel;
use Base\Controllers\BaseController;
use Components\Libraries\ComponentOrderBy;
use Components\Libraries\Hash;
use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

class User extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        if(!session()->has('loggedUserId')) header("Location:".base_url("identification/logout"));

        $this->UserModel = new UserModel();

        $this->context = 'user';
        $this->request = \Config\Services::request();
        $this->is_distant = !empty($this->request->getVar("is_distant")) ? $this->request->getVar("is_distant") : 0;

        $this->datas->context = $this->context;
        $this->datas->is_distant = $this->is_distant;
        $this->datas->no_menu = !empty($this->is_distant) ? TRUE : FALSE;
    }

    public function UserList()
    {   
        $ComponentOrderBy = new ComponentOrderBy();
        $orderBy = $ComponentOrderBy->getOrderBy("nom, prenom, username", $this->request);
        $orderDirection = $ComponentOrderBy->getOrderDirection("ASC", $this->request);

        $DataView = new DataViewConstructor();
        $columns=
        [
            // "delete" => [null, false],
            "id" => ["Id", true],
            // "role" => ["Type d'utilisateur", true],
            "nom" => ["Nom", true, 'asc'],
            "prenom" => ["Prénom", true],
            "nom, prenom, username" => ["Login", true],
            "email" => ["E-mail", true],
            "updated_at" => ["Depuis", true],
            "is_actif" => ["Statut", true],
        ];

        $this->datas->users_infos = $this->UserModel->getListUsers($this->request, $orderBy, $orderDirection);
        $this->datas->users_total = $this->UserModel->pager->getTotal();
        $this->datas->pager = $this->UserModel->UsersPager();
        // $this->datas->per_page = $this->request->getGet('per_page') ?? 20;
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
        $this->datas->titleView = "Utilisateurs";

        return view($this->module . '\user-list', (array) $this->datas);
    }

    public function UserNew()
    {
        $config = new \Custom\Config\Autorisation();
        $this->datas->default_config = $config->default_autorisation;
        $this->datas->entities_config = $config->entities;
        $this->datas->fieldsAutorisation = $this->db->getFieldNames($this->t_autorisation);
        $this->datas->outils_config = $config->outils;
        $this->datas->titleView = "Nouvelle fiche Utilisateur";
        $this->datas->typeDataView = 'create';
        $this->datas->users_roles_total = $this->db->table($this->t_l_user_role)->where('is_actif', 1)->countAll();

        if(!$this->request->getPost()) :

            return view($this->module . '\user-new', (array) $this->datas);
        else :

            $validation = \Config\Services::validation();
            if ($validation->run($this->request->getPost(), $this->module . 'UserAdd') == FALSE) :
                $this->datas->validation = $validation;

                return view($this->module . '\user-new', (array) $this->datas);
            else :
                $post = database_decode($this->request->getPost());

                $password = $post->password;

                $post->password = Hash::make($post->password);
                $post->token = md5(uniqid(mt_rand()));
                $post->valided = 1;
                $post->updated_by = session('loggedUserId');
                $post->created_by = session('loggedUserId');

                $this->UserModel->insert(database_encode($this->t_user, $post));
                $id_user = $this->db->insertID();

                $post->id_user = $id_user;
                $this->db->table($this->t_autorisation)->set(database_encode($this->t_autorisation, $post))->insert();

                if($post->valided == 0) :
                    $sbj = 'Validation de votre compte ' . CRM_NAME;
                    $msg = '
                        <p>Bonjour ' . $post->prenom . ', </p>
                        <p>Un administrateur a créé un nouveau compte pour vous.</p>
                        <p>Login : <b>' . $post->username . '</b></p>
                        <p>Mot de passe : <b>' . $password . '</b></p>
                        <p>Cliquez <a href="' . base_url('identification/verify?token=' . $post->token . '') . '">Ici</a> pour valider votre email et activer votre compte d\'utilisateur.</p>
                        <p class="text-success"><b> The ' . CRM_NAME . ' Team </b></p>
                    ';

                    // send_email_to($from, $to, $cc, $bcc, $subject, $message)
                    send_email_to($post->email, '', '', $sbj, $msg);
                else :
                    $sbj = 'Création de votre compte ' . CRM_NAME;
                    $msg = '
                        <p>Bonjour ' . $post->prenom . ', </p>
                        <p>Un administrateur a créé un nouveau compte pour vous.</p>
                        <p>Accès: <a href="' . base_url() . '">' . base_url() . '</a></p>
                        <p> Votre login est : <b> ' . $post->username . '</b> <br>
                        Le mot de passe est : <b>' . $password . '</b> <br>
                        Veuillez le modifier lors de votre première connexion.</p>
                        <p class="text-success"><b> The ' . CRM_NAME . ' Team </b></p>
                    ';

                    send_email_to($post->email, '', '', $sbj, $msg);
                endif;
                
                return redirect()->to("user/autorisation?id_user=$id_user")->with('success', "Le compte de l'utilisateur a été créé et un mail contenant les informations du compte a été envoyé à l'utilisateur  $post->username ...");
            endif;
        endif;
    }

    public function UserProfile()
    {
        $id_user = $this->user->id;

        if(!$this->Autorisation->is_autorise("user_r") && $id_user!=session('loggedUserId')) return redirect()->to(base_url('forbidden'));

        $AvatarModel = new AvatarModel();
        $cropper_settings = $AvatarModel->get_cropper_settings();

        $this->datas->avatar_default = AVATAR_DEFAULT;
        $this->datas->avatar_name = !empty($this->user->avatar) ? $this->user->avatar : AVATAR_DEFAULT;
        $this->datas->avatar_path = AVATAR_PATH;
        $this->datas->context = 'user';
        $this->datas->context_sub = 'profile';
        $this->datas->cropper_selected = $cropper_settings[0]->value;
        $this->datas->cropper_view_mode = $cropper_settings[1]->value;
        $this->datas->cropper_drag_mode = $cropper_settings[2]->value;
        $this->datas->cropper_aspect_ratio = $cropper_settings[3]->value;
        $this->datas->cropper_min_cropbox_width = $cropper_settings[4]->value;
        $this->datas->cropper_min_cropbox_height = $cropper_settings[5]->value;
        $this->datas->user_infos = $this->user;
        $this->datas->users_backup = $this->UserModel->UsersActive();
        $this->datas->titleView = $id_user==session('loggedUserId') ? 'Mon compte' : 'Compte de ' . fullname($this->user->prenom, $this->user->nom);

        if($this->request->getPost()) :
            $validation = \Config\Services::validation();
            if($validation->run($this->request->getPost(), 'AdministratorEmail') == FALSE) :
                $this->datas->validation = $validation;
                return view($this->module . '\user-profile', (array) $this->datas);
            else :
                $post = (object) $this->request->getPost();
                $post->updated_at = date('Y-m-d H:i:s');
                $post->updated_by = session('loggedUserId');
                if($this->request->getPost('new_password')) :
                    if($validation->run($this->request->getPost(), 'AdministratorPassword') == FALSE) :
                        $this->datas->validation = $validation;
                        $this->datas->typeDataView = "update";
                        
                        return view($this->module . '\user-profile', (array) $this->datas);
                    else :
                        $post->password = Hash::make($post->new_password);
                        $post->token = md5(uniqid(mt_rand()));
                    endif;
                endif;
                $update_user = $this->UserModel->update($id_user, database_encode($this->t_user, $post));
                if (!$update_user) :
                    $alert = "warning";
                    $message = "Le compte utilisateur n'a pas pu être mis à jour.";
                else :
                    $alert = "success";
                    $message = "Le compte utilisateur a bien été mis à jour.";
                endif;
            endif;
    
            return redirect()->to(base_url("user/profile?" . $this->datas->id_user_get))->with($alert, $message);
        endif;

        $this->datas->typeDataView = "read";

        return view('Administrator\user-profile', (array) $this->datas);
    }

    public function UserAutorisation()
    {
        $id_user = $this->user->id;

        if(!$this->Autorisation->is_autorise("autorisation_r") && $id_user!=session('loggedUserId')) return redirect()->to(base_url('forbidden'));

        if($this->request->getPost()) :
            $Autorisation = new AutorisationLibrary();
            $Autorisation->save_autorisation();
    
            return redirect()->to(base_url("user/autorisation?" . $this->datas->id_user_get))->with("success","Les autorisations ont été modifiées");
        endif;

        $config = new \Custom\Config\Autorisation();
        $id_user = $this->user->id;
        
        $this->datas->avatar_default = AVATAR_DEFAULT;
        $this->datas->avatar_name = !empty($this->user->avatar) ? $this->user->avatar : AVATAR_DEFAULT;
        $this->datas->avatar_path = AVATAR_PATH;
        $this->datas->context = 'user';
        $this->datas->context_sub = 'autorisation';
        $this->datas->default_config = $config->default_autorisation;
        $this->datas->entities_config = $config->entities;
        $this->datas->fieldsAutorisation = $this->db->getFieldNames($this->t_autorisation);
        $this->datas->outils_config = $config->outils;
        $this->datas->userAutorisations = $this->Autorisation->get_autorisation($id_user);
        $this->datas->user_infos = $this->user;
        $this->datas->titleView = $id_user==session('loggedUserId') ? 'Mes autorisations' : 'Autorisations de ' . fullname($this->user->prenom, $this->user->nom);

        $this->datas->typeDataView = "read";

        return view('Administrator\user-autorisation', (array) $this->datas);
    }
    
    public function UserAvatarSave()
    {
        $id_user = $this->user->id;
        $avatar = $this->request->getPost('image');

        if (isset($avatar) && !empty($avatar)) :
            $parse = explode(";", $avatar);
            $parse = explode(",", $parse[1]);
            $avatar_blob = base64_decode($parse[1]);
            $random_name =  time() . '_' . uniqid() . '.png';
            $avatar_save = file_put_contents(AVATAR_PATH . $random_name, $avatar_blob);

            if (($avatar_save !== false) || ($avatar_save != -1)) :

                $user_infos = $this->UserModel->where('id', $id_user)->first();

                if($user_infos) :
                    $avatar_name_old = $user_infos->avatar;
                    // DELETE OLD AVATAR ONLY IF IS NOT THE AVATAR_DEFAULT
                    if (!empty($avatar_name_old) && file_exists(AVATAR_PATH . $avatar_name_old) && $avatar_name_old <> AVATAR_DEFAULT) :
                        unlink(AVATAR_PATH . $avatar_name_old);
                    endif;
                    $values = [ 'avatar' => $random_name, ];
                    $result = $this->UserModel->update($id_user, $values);
                endif;
                
                if ($result) :
                    if($id_user==session('loggedUserId')) session()->set('loggedUserAvatar', $random_name);
                    $post = base_url(AVATAR_PATH . $random_name);
                endif;
            endif;
        endif;

        if(!$post) $post = base_url(AVATAR_PATH . AVATAR_DEFAULT);
        
        echo $post;
    }
    
    // public function AvatarView()
    // {
    //     $id_user = $this->user->id;
    //     $avatar_path = AVATAR_PATH;

    //     $AvatarModel = new AvatarModel();
    //     $cropper_settings = $AvatarModel->get_cropper_settings();
    //     // $selected_cropper = $this->request->getPost('selectCropper') ?? $cropper_settings[0]->value;

    //     $this->datas->titleView = $id_user!=session('loggedUserId') ? "Editer l'avatar de " . $this->user->prenom . " " . $this->user->nom : "Editer mon avatar";
    //     $this->datas->context_sub = 'avatar';
    //     $this->datas->avatar_path = $avatar_path;
    //     // $this->datas->avatar_default = $avatar_default;
    //     $this->datas->avatar_name = !empty($this->user->avatar) ? $this->user->avatar : AVATAR_DEFAULT;
    //     $this->datas->selected_cropper = $cropper_settings[0]->value;

    //     $this->datas->view_mode = $cropper_settings[1]->value;
    //     $this->datas->drag_mode = $cropper_settings[2]->value;
    //     $this->datas->aspect_ratio = $cropper_settings[3]->value;
    //     $this->datas->min_cropbox_width = $cropper_settings[4]->value;
    //     $this->datas->min_cropbox_height = $cropper_settings[5]->value;

    //     if (!$this->request->getPost()) :
    //         return view('Administrator\user/avatar', (array) $this->datas);
    //     endif;

    //     $avatar = $this->request->getPost('image');

    //     if (isset($avatar) && !empty($avatar)) :
    //         $parse = explode(";", $avatar);
    //         $parse = explode(",", $parse[1]);
    //         $avatar_blob = base64_decode($parse[1]);
    //         $random_name =  time() . '_' . uniqid() . '.png';
    //         $avatar_path = AVATAR_PATH;
    //         $avatar_save = file_put_contents($avatar_path . $random_name, $avatar_blob);

    //         if (($avatar_save !== false) || ($avatar_save != -1)) :
    //             $user_infos = $this->UserModel->where('id', $id_user)->first();

    //             if ($user_infos) :
    //                 $avatar_name_old = $user_infos->avatar;

    //                 // DELETE OLD AVATAR ONLY IF IS NOT THE AVATAR_DEFAULT
    //                 if (!empty($avatar_name_old) && file_exists($avatar_path . $avatar_name_old) && $avatar_name_old <> AVATAR_DEFAULT)
    //                 {
    //                     unlink($avatar_path . $avatar_name_old);
    //                 }

    //                 $values = [
    //                     'avatar' => $random_name,
    //                 ];

    //                 $result = $this->UserModel->update($id_user, $values);
    //             endif;
                
    //             if ($result)
    //             {
    //                 if($id_user==session('loggedUserId')) session()->set('loggedUserAvatar', $random_name);
    //                 $post = base_url($avatar_path . $random_name);
    //             }
    //         endif;
    //     endif;

    //     if(!$post) $post = base_url($avatar_path . AVATAR_DEFAULT);
        
    //     echo $post;
    // }

    // /* AVATAR UPLOAD (WITH CROPPER) */
    // public function AvatarCropper()
    // {   
    //     $UserProfileModel = new UserProfileModel();

    //     $id_user = $this->request->getGet('id_user') ?? session('loggedUserId');

    //     $this->datas->title = lang('Avatar.title');
    //     $this->datas->subtitle = '';
    //     // $this->datas->titleView = 'Compte utilisateur - Avatar';
    //     $this->datas->context_sub = 'avatar';
    //     $this->datas->icon = 'fa fa-image';

    //     $AvatarModel = new AvatarModel();
    //     $cropper_settings = $AvatarModel->get_cropper_settings();

    //     // $selected_cropper = $this->request->getPost('selectCropper') ?? $cropper_settings[0]->value;

    //     $this->datas->selected_cropper = $cropper_settings[0]->value; 
    //     $this->datas->view_mode = $cropper_settings[1]->value;
    //     $this->datas->drag_mode = $cropper_settings[2]->value;
    //     $this->datas->aspect_ratio = $cropper_settings[3]->value;
    //     $this->datas->min_cropbox_width = $cropper_settings[4]->value;
    //     $this->datas->min_cropbox_height = $cropper_settings[5]->value;

    //     if (!$this->request->getPost()) :
    //         return view($this->module . '\avatar/index', (array) $this->datas);
    //     endif;

    //     $avatar = $this->request->getPost('image');

    //     if (isset($avatar) && !empty($avatar)) :
    //         $parse = explode(";", $avatar);
    //         $parse = explode(",", $parse[1]);
    //         $avatar_blob = base64_decode($parse[1]);
    //         $random_name =  time() . '_' . uniqid() . '.png';
    //         $avatar_path = AVATAR_PATH;
    //         $avatar_save = file_put_contents($avatar_path . $random_name, $avatar_blob);

    //         if (($avatar_save !== false) || ($avatar_save != -1)) :
    //             $profile_infos = $UserProfileModel->where('user_id', $id_user)->first();

    //             if ($profile_infos) :
    //                 $avatar_name_old = $profile_infos['avatar'];

    //                 // DELETE OLD AVATAR ONLY IF IS NOT THE AVATAR_DEFAULT
    //                 if (!empty($avatar_name_old) && file_exists($avatar_path . $avatar_name_old) && $avatar_name_old <> AVATAR_DEFAULT)
    //                 {
    //                     unlink($avatar_path . $avatar_name_old);
    //                 }

    //                 $values = [
    //                     'user_id' => $id_user,
    //                     'avatar' => $random_name,
    //                 ];

    //                 $result = $UserProfileModel->update($profile_infos['id'], $values);
    //             else :
    //                 $values = [
    //                     'user_id' => $id_user,
    //                     'avatar' => $random_name,
    //                 ];

    //                 $result = $UserProfileModel->insert($values);
    //             endif;
                
    //             if ($result)
    //             {
    //                 if($id_user==session('loggedUserId')) session()->set('loggedUserAvatar', $random_name);
    //                 $post = base_url($avatar_path . $random_name);
    //             }
    //         endif;
    //     endif;

    //     if(!$post) $post = base_url($avatar_path . AVATAR_DEFAULT);
        
    //     echo $post;
    // }

    // public function contact_view($id_contact)
    // {
    //     $data = (object) [];
    //     $data->contact = $this->UserContactModel->getOneContact($id_contact);
    //     $data->profiles = $this->UserContactModel->getProfilesByContact($id_contact);

    //     echo view($this->module . '\user/contact_profiles_view', (array) $data);
    // }

    // public function ProfileView()
    // {
    //     $user = $this->user;

    //     if($this->request->getPost()) :
    //         $validation = \Config\Services::validation();
    //         if($validation->run($this->request->getPost(), 'AdministratorEmail') == FALSE) :

    //             $this->session->setFlashdata('danger', $validation->listErrors());

    //             return redirect()->to(base_url('user/profile'));
    //         else :
    //             $post = (object) $this->request->getPost();
    //             $post->updated_at = date('Y-m-d H:i:s');
    //             $post->updated_by = session('loggedUserId');

    //             $update_user_account = $this->UserModel->update($user->id, database_encode($this->t_user, $post));
    //             // if (!$update_user_account) :
    //             //     return redirect()->back()->with('danger', "L'utilisateur n'a pas pu être mis à jour.");
    //             // else :

    //                 // $UserProfileModel = new UserProfileModel();
    //                 // $update_user_profile = $UserProfileModel->update($user->id_profile, database_encode($this->t_user_profile, $post));

    //                 $url = base_url("user/profile?" . $this->datas->id_user_get);
    //                 if (!$update_user_account) :
    //                     return redirect()
    //                         ->to($url)
    //                         ->with('danger', "La fiche utilisateur n'a pas pu être mis à jour.");
    //                 else :
    //                     return redirect()
    //                         ->to($url)
    //                         ->with('success', "La fiche utilisateur a bien été mis à jour.");
    //                 endif;
    //             // endif;
    //         endif;
    //     endif;

    //     $this->datas->context_sub = 'profile';
    //     $this->datas->titleView = 'User profile';
    //     $this->datas->titleView = $user->id!=session('loggedUserId') ? lang('UserProfile.profile_of') . " $user->prenom $user->nom" : "Mon profil";
    //     $this->datas->users_backup = $this->UserModel->UsersActive();

    //     // $this->datas->user = $user;
    //     // $this->datas->roles_infos = $this->ListRoleModel->find($role_id);
    //     // $this->datas->genders_infos = $this->ListGenderModel->find($gender_id);
    //     // $this->datas->countries_infos = $this->ListCountryModel->find($country_id);
    //     // $this->datas->profile_infos = $profile_infos;

    //     return view('Administrator\user/profile', (array) $this->datas);
    // }

    // public function ProfileEdit()
    // {
    //     $user = $this->user;

    //     $this->datas->context_sub = 'profile';
    //     $this->datas->titleView = $user->id!=session('loggedUserId') ? "Editer le profil de $user->prenom $user->nom" : "Editer mon profil";

    //     if (!$this->request->getPost()) :
    //         return view('Administrator\user/profile-edit', (array) $this->datas);
    //     endif;

    //     return json_encode($this->datas);
    // }

    // public function ProfileSave()
    // {
    //     $id_user = ($this->request && $this->request->getGet('id_user')) ? $this->request->getGet('id_user') : session('loggedUserId');
    //     $user = sessionUser($id_user);

    //     $this->datas->context_sub = 'profile';
    //     $this->datas->icon = 'fa fa-id-card';
    //     $this->datas->subtitle = lang('UserProfile.subtitle');
    //     $this->datas->user = $user;
    //     $this->datas->title = lang('UserProfile.title');
    //     $this->datas->titleView = 'Enregistrer';

    //     $validation = \Config\Services::validation();
    //     if($validation->run($this->request->getPost(), 'AdministratorEmail') == FALSE) :
    //         $this->session->setFlashdata('danger', $validation->listErrors());
    //         return view('Administrator\user/profile-edit', (array) $this->datas);
    //     else :
    //         $updated_at = date('Y-m-d H:i:s');
    //         $values = [
    //             'nom' => $this->request->getPost('nom'),
    //             'prenom' => $this->request->getPost('prenom'),
    //             'email' => $this->request->getPost('email'),
    //             // 'role_id' => $this->request->getPost('role_id'),
    //             // 'is_actif' => $this->request->getPost('is_actif'),
    //             'valided' => 1,
    //             'updated_at' => $updated_at,
    //             'updated_by' => session('loggedUserId'),
    //         ];
            
    //         if($this->request->getPost('role_id')) $values['role_id'] = $this->request->getPost('role_id');
    //         if($this->request->getPost('is_actif')) $values['is_actif'] = $this->request->getPost('is_actif');

    //         $update_user_account = $this->UserModel->update($id_user, $values);
    //         if (!$update_user_account) :
    //             return redirect()->back()->with('danger', "L'utilisateur n'a pas pu être mis à jour.");
    //         else :
    //             $values = [
    //                 'phone' => $this->request->getPost('phone'),
    //                 'website' => $this->request->getPost('website'),
    //                 'updated_at' => $updated_at,
    //                 'updated_by' => session('loggedUserId'),
    //             ];
    //             $UserProfileModel = new UserProfileModel();
    //             $update_user_profile = $UserProfileModel->update($user->id_profile, $values);

    //             $url = ($this->request && $this->request->getGet('id_user')) ? "user/profile?id_user=$id_user" : "user/profile";
    //             if (!$update_user_profile) :
    //                 return redirect()
    //                     ->to($url)
    //                     ->with('danger', "Le profil de l'utilisateur n'a pas pu être mis à jour.");
    //             else :
    //                 return redirect()
    //                     ->to($url)
    //                     ->with('success', "Les données de l'utilisateur ont bien été mis à jour.");
    //             endif;
    //         endif;
    //     endif;
    // }

    public function index()
    {
        if(session('loggedUserRoleId')==1) return redirect()->to(base_url('user/profile'));
        elseif(session('loggedUserRoleId')==2) return redirect()->to(base_url('user/contacts/list'));
    }

    // public function contacts_list()
    // {
    //     // $user = sessionUser();        
    //     // $id_user = $this->request->getGet('id_user') && $user->role_id==1 ? $this->request->getGet('id_user') : null;

    //     // $user = sessionUser($id_user);
    //     $ContactModel = new ContactModel();
    //     $contacts = $ContactModel->ContactsGet(null, null, $this->request);

    //     $this->datas->title = lang('User.title');
    //     $this->datas->subtitle = lang('User.dashboard');
    //     $this->datas->titleView = $this->module;
    //     // $this->datas->user = $user;
    //     $this->datas->context_sub = 'contacts_list';
    //     $this->datas->contacts = $contacts;
    //     // $this->datas->user = $user;
    //     // $this->datas->UserContactModel = $this->UserContactModel;

    //     return view($this->module . '\user/contacts_list', (array) $this->datas);
    // }

    // public function contacts_all()
    // {
    //     $contacts = $this->UserContactModel->getContacts(session('loggedUserId'), 'all_contacts');

    //     $this->datas->context_sub = 'contacts_all';
    //     $this->datas->title = lang('User.title');
    //     $this->datas->subtitle = lang('User.dashboard');
    //     $this->datas->titleView = $this->module;
    //     $this->datas->contacts = $contacts;

    //     return view($this->module . '\user/contacts_list', (array) $this->datas);
    // }

    // public function password()
    // { 
    //     $user = $this->user;

    //     $this->datas->titleView = lang('User.password');
    //     $this->datas->context_sub = 'password';

    //     if (!$this->request->getPost())
    //     {
    //         return view($this->module . '\user/password', (array) $this->datas);
    //     }

    //     $validation = $this->validate([
    //       /*  'current_password' => [
    //             'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
    //             'errors' => [
    //                 'required' => 'Current password is required ...',
    //                 'min_length' => 'Current password must have a minimum of 5 characters ...',
    //                 'max_length' => 'Current password must have a maximum of 12 characters ...',
    //                 'regex_match' => 'Current password must not have space ...',
    //             ]
    //         ],*/
    //         'new_password' => [
    //             'rules' => 'required|min_length[5]|max_length[12]|regex_match[/^\S*$/u]',
    //             'errors' => [
    //                 'required' => 'New password is required ...',
    //                 'min_length' => 'New password must have a minimum of 5 characters ...',
    //                 'max_length' => 'New password must have a maximum of 12 characters ...',
    //                 'regex_match' => 'New password must not have space ...',
    //             ]
    //         ],
    //         'confirm_password' => [
    //             'rules' => 'required|min_length[5]|max_length[12]|matches[new_password]|regex_match[/^\S*$/u]',
    //             'errors' => [
    //                 'required' => 'Vous devez entrer un mot de passe ...',
    //                 'min_length' => 'Le mot de passe doit avoir au moins 5 caractères ...',
    //                 'max_length' => 'Le mot de passe ne doit pas avoir plus de 12 caractères ...',
    //                 'matches' => 'Les mots de passe ne correspondent pas ...',
    //                 'regex_match' => "Le mot de passe ne peut avoir d'espace ...",
    //             ]
    //         ],
    //     ]);

    //     if (!$validation) :
    //         $this->datas->validation = $validation;

    //         return view($this->module . '\user/password', (array) $this->datas);
    //     else :
    //       /*  $current_password = $this->request->getPost('current_password');
    //         $check_password = Hash::check($current_password, $user->password);
    //         $username = $user->username;

    //         if (!$check_password)
    //         {
    //             $message = 'Your current password is invalid <b>'.$username.'</b>, password modifified without success ...';
    //             return redirect()->to('/user/password')->with('danger', $message);
    //         }

    //         else
    //         {*/
    //             $new_password = $this->request->getPost('new_password');
    //             $new_password = Hash::make($new_password);
    //             $token = md5(uniqid(mt_rand()));
    //             $updated_at = date('Y-m-d H:i:s');

    //             $values = [
    //                 'password' => $new_password,
    //                 'token' => $token,
    //                 'updated_at' => $updated_at,
    //             ];

    //             $update_passsword = $this->UserModel->update($user->id, $values);

    //             if (!$update_passsword) :
    //                 $message = "ERREUR! Attention le mot de passe n'a pas été mis à jour ...";

    //                 // return redirect()->to("user/contacts/list?id_user=$id_user")->with('danger', $message);
    //                 return redirect()->to("user/password")->with('danger', $message);
    //             else :
    //                 $message = "Congratulation le mot de passe a été mis à jour ...";

    //                 return redirect()->to("user/password")->with('success', $message);
    //                 // return redirect()->to("user/contacts/list?id_user=$id_user")->with('success', $message);
    //             endif;
    //         //}
    //     endif;
    // }

    public function settings()
    {
        $this->datas->title = lang('User.title');
        $this->datas->subtitle = lang('User.settings');
        $this->datas->titleView = lang('User.settings');
        $this->datas->context_sub = 'settings';
        $this->datas->icon = icon('settings');

        // TODO ...
        $action = $this->request->getGet('action');
        // if (!$this->request->getPost())
        if ($action == 'save' || $action == 'cancel')
        {
            session()->setFlashdata('info', 'User settings is under construction ...');
        }

        return view($this->module . '\user/settings', (array) $this->datas);
    }

    public function confidentiality()
    {
        $this->datas->title = lang('User.title');
        $this->datas->subtitle = lang('User.confidentiality');
        $this->datas->titleView = 'Confidentiality';
        $this->datas->context_sub = 'confidentiality';
        $this->datas->icon = icon('confidentiality');

        if (!$this->request->getPost())
        {
            $action = $this->request->getGet('action');

            if ($action == 'save')
            {
                $message = 'User confidentiality is under construction ...';
                return redirect()->to('/user/confidentiality')->with('info', $message);
            }
        }

        $password = $this->request->getPost('download_data');

        if ($password)
        {
            $check_password = Hash::check($password, $this->user->password);

            if (!$check_password)
            {
                $message = 'Invalid password detected ...';
                return redirect()->to('/user/confidentiality')->with('danger', $message);
            }

            else
            {
                // helper('download');
                // $name = 'mytext.txt';
                // return $response->download($name, $user);
                // debug($user,1);
                // $this->load->helper('download');
                // force_download($name, NULL);
                $message = "Valid password detected ...";
                return redirect()->to('/user/confidentiality')->with('success', $message);
            }
        }

        $password = $this->request->getPost('data_erasure');

        if ($password)
        {
            $check_password = Hash::check($password, $this->user->password);

            if (!$check_password)
            {
                $message = 'Invalid password detected ...';
                return redirect()->to('/user/confidentiality')->with('danger', $message);
            }

            else
            {
                $message = "Valid password detected ...";
                return redirect()->to('/user/confidentiality')->with('success', $message);
            }
        }

        return view($this->module . '\user/confidentiality', (array) $this->data);
    }

    // public function contacts_link($id_user=null)
    // {
    //     if (session('loggedUserRoleId')==1) :

    //         $user = sessionUser($id_user);  
    //         $contacts = $this->UserContactModel->getContacts(); 
    //         $futurContacts = $this->UserContactModel->getContactsFutur($this->request, $id_user);
    //         $pager = $this->UserContactModel->pager; 

    //         $this->datas->title = "Associer des contacts à ";
    //         $this->datas->titleView = "Associer des contacts à";
    //         $this->datas->context_sub = 'contacts_link';
    //         $this->datas->contacts = $contacts;
    //         $this->datas->user = $user;
    //         $this->datas->futurContacts = $futurContacts;
    //         $this->datas->pager = $pager;
    
    //         return view($this->module . '\user/contacts_link', (array) $this->datas);
    //     else :
    //         redirect()
    //             ->to(base_url('user'))
    //             ->with('warning', "Vous n'avez pas les autorisations Administrateur.trice pour associer des contacts.");
    //         // header("Location:" . base_url('user'));
    //     endif;
    // }

    // public function contacts_link_set()
    // {
    //     if (session('loggedUserRoleId') == 1) :
    //         // $validation = $this->validate([
    //         //     'id_user' => [
    //         //         'rules' => 'required',
    //         //         'errors' => [
    //         //             'required' => 'Aucun id_user défini ...',
    //         //         ]
    //         //     ],
    //         //     'id_contacts' => [
    //         //         'rules' => 'required',
    //         //         'errors' => [
    //         //             'required' => 'Vous devez choisir au moins un contact ...',
    //         //         ]
    //         //     ],
    //         // ]);

    //         if ($this->validation->run($this->request->getPost(), $this->module . 'ContactLink') == FALSE) :  
                
    //             return redirect()
    //                 ->to(base_url('user/contacts/link?itemSearch=' . $this->request->getGet('itemSearch')))
    //                 ->with('danger', "Aucun contact n'a pu être associé.");

    //         else :
    //             $this->UserContactModel->setFuturContacts($this->request->getVar());

    //             return redirect()
    //                 ->to(base_url('user/contacts/list?id_user=' . $this->request->getVar("id_user")))
    //                 ->with('success', "Les contacts ont été associés à l'utilisateur.");

    //         endif;
    //     else :

    //         redirect()
    //             ->to(base_url('user'))
    //             ->with('warning', "Vous n'avez pas les autorisations Administrateur.trice pour associer des contacts.");

    //     endif;
    // }

    // public function form_registration($id_user = 0, $id_activity = 0, $id_contact = 0, $validation = null)
    // {
    //     if (is_null($id_user))
    //     {
    //         $id_user=session('loggedUserId');
    //     }

    //     $modules=NULL;
    //     $injectedForm = NULL;
    //     $form_registration = NULL;
    //     $user = sessionUser($id_user);  
    //     $contacts = $this->UserContactModel->getContacts($id_user);
    //     $contact = 0;
    //     $activities = $this->activiteModel->getListActivitiesActifWithoutModules();


    //     $name_action=NULL;

        
    //     if($id_user>0)
    //     {   
    //         $contacts_possible = $this->UserContactModel->getContacts($id_user);
    //         if(count($contacts_possible)==1)
    //         {
    //             $id_contact=$contacts_possible[0]->id_contact;
    //         }

            
    //     }
    //     else
    //     {
    //         $id_contact = 0;
    //         $contacts_possible=NULL;
    //     }
       

    //     $dataView = new DataViewConstructor();
    //     $this->dataGeneratorModel = new DataViewConstructorModel();
    //     $fields = $this->dataGeneratorModel->getFields();


        
    //     if ($this->request->getVar("id_activity")&&$id_activity==0)
    //     {
    //         $id_activity = $this->request->getVar("id_activity");
    //     }

    //     if(isset($id_activity)&&$id_activity>0)
    //     {
    //         $modules= $this->activiteModel->getListModules($id_activity);

    //     }

    //     if ($this->request->getVar("id_contact"))
    //     {
    //         $id_contact = $this->request->getVar("id_contact");
    //     }

    //    if ($id_activity > 0)
    //    {
    //         $injectedForm = $this->dataGeneratorModel->getInjectedFormIframe($id_activity);
    //         $activity=$this->activiteModel->getActivite($id_activity);
    //        if(!empty($activity))
    //        {
    //            $name_action=$activity->idact." ".$activity->titre;
    //        }

    //         if (empty($injectedForm) || is_null($injectedForm))
    //         {
    //             $form_registration = "Pas de formulaire trouvé pour cette activité"; 
    //         }

    //         else
    //         {
    //             $indexes_form = explode(",", trim($injectedForm->fields));

    //             // On retire du formulaire les données de contact
    //             if ($id_contact > 0)
    //             {
    //                 $contact = $this->UserContactModel->getOneContact($id_contact);
    //                 //$indexes_form = $this->dataGeneratorModel->getSubstractFieldWithValues($indexes_form,"contact",$contact);
    //                 //debug($indexes_form );
    //              //debug($contact);
    //             }

    //             $form_registration = view("DataView\Views\/injected-form-iframe", [   
    //                 'title' => "form", 
    //                 'subtitle' => NULL, 
    //                 "titleView" => "form", 
    //                 'context' => $this->context, 
    //                 'injectedForm' => $injectedForm, 
    //                 'fields' => $fields, 
    //                 'dataView' => $dataView, 
    //                 "is_iframe" => TRUE, 
    //                 "id_injected_form" => $injectedForm->id_injected_form, 
    //                 "is_frame" => FALSE, 
    //                 "id_user" => $id_user, 
    //                 "id_activity" => $id_activity, 
    //                 "id_contact" => $id_contact, 
    //                 "contact" => $contact, 
    //                 "indexes_form" => $indexes_form,
    //                 "is_distant" => $this->is_distant,
    //                 "contacts_possible" => $contacts_possible,
    //                 "name_action"=>$name_action,
    //                 "modules"=>$modules,
    //                 "activity"=>$activity
                   
    //             ]); 
    //         }
    //     }

    //     $this->datas->title = lang('User.title');
    //     $this->datas->subtitle = lang('User.dashboard');
    //     $this->datas->titleView = 'Dashboard';
    //     $this->datas->context_sub = 'inscrire_activite';
    //     $this->datas->icon = icon('dashboard');
    //     $this->datas->user = $user;
    //     $this->datas->contacts = $contacts;
    //     $this->datas->id_activity = $id_activity;
    //     $this->datas->form_registration = $form_registration;
    //     $this->datas->activities = $activities;
    //     $this->datas->id_contact = $id_contact;
    //     $this->datas->contacts_possible = $contacts_possible;
    //     $this->datas->name_action = $name_action;
    //     $this->datas->modules = $modules;

    //     return view($this->module . '\user/form_registration', (array) $this->datas);

    //     // 1. Afficher la liste des inscriptions possibles
    //     // 2. Afficher la liste des contacts gérés si plus qu'Un sinon input avec l'id du contact unique
    //     // 3. bouton soumettre pour afficher l'iframe
    //     // 4. Partie iframe.
    // }


    // public function save_registration()
    // {
    //     // List of indexes of form
    //     $session = \Config\Services::session();
    //     $indexes = array_keys($this->request->getVar());

    //     // debug($indexes,true);
    //     $dataView = new DataViewConstructor();
    //     $rules = $dataView->getRules($indexes, $this->inscriptionModel->getFields());

       
    //    // debug($this->request->getVar());

    //     $id_activity = $this->request->getVar("id_activity");
    //     $id_contact = $this->request->getVar("id_contact");
    //     $id_user = $this->request->getVar("id_user");

    //     // debug( $this->request->getVar());
    //     $data = $this->request->getVar();

    //     if(isset($data["has_modules"])&&$data["has_modules"])
    //    {
    //         $rules["id_modules_5678"]="required";
    //    }

    //     if (!$this->validate($rules) && !empty($rules)) 
    //     {
    //         //debug($this->validator,true);
    //         if ($this->request->getVar('typeDataView') == "create")
    //         {
    //             echo $this->form_registration($id_user, $id_activity, $id_contact, $this->validator);
    //         }

    //         else 
    //         {
    //             // $data = array_merge($data, ['validation' => $this->validator]);
    //             echo $this->form_registration($id_user, $id_activity, $id_contact, $this->validator);
    //             // return redirect()->to(base_url(). '/user/form_registration/'.$id_user.'/'.$id_activity.'/'.$id_contact);
    //             // echo $this->form_registration($this->request->getVar('id_inscription'),$this->validator);
    //         }
    //     }

    //     else 
    //     {
    //         // treatment of data, on dit 
    //         // debug($this->request->getVar(),true);
    //         // print_r($this->request->getVar("id_inscription")); die();
    //         $data=$this->request->getVar();
    //         //debug($this->request->getVar(),true);
    //         if(isset($data["has_modules"])&&$data["has_modules"])
    //         {
    //             foreach($data["id_modules_5678"] as $id_module_5678)
    //             {
    //                 $data["id_activity"]=$id_module_5678;
    //                 // $id_inscription_save = $dataView->saveData($indexes, $data, $this->inscriptionModel->getFields(), $this->inscriptionModel->getTable());  
    //             }
    //         }
    //         else
    //         {
    //             // $id_inscription_save = $dataView->saveData($indexes, $data, $this->inscriptionModel->getFields(), $this->inscriptionModel->getTable());  

    //         }


    //         if($this->request->getVar('typeDataView')=="create")
    //         {
    //             session()->setFlashdata('success', "L'inscription a été enregistrée");
    //         }

    //         else 
    //         {
    //             session()->setFlashdata('success', "L'inscription a été enregistrée");
    //         }

    //         return redirect()->to(base_url("user/contacts/list?id_user=$id_user;is_distant=$this->is_distant"));
    //     }
    // }

    // public function contact_unlink($id_contact, $id_user)
    // {
    //     $this->UserContactModel->contact_unlink($id_contact, $id_user);

    //     $contact = $this->UserContactModel->getOneContact($id_contact);
    //     $user = sessionUser($id_user);

    //     $message = "La gestion du contact $contact->prenom_contact $contact->nom_contact a bien été retirée ";
    //     if($id_user==session('loggedUserId')) $message .= "de votre compte.";
    //     else $message .= "du compte de $user->prenom $user->nom.";

    //     return redirect()
    //         ->to(base_url("user/contacts/list?id_user=$id_user"))
    //         ->with('success', $message);
    // }


    // public function getDocumentsInscriptions($id_inscription)
    // {
    //     $documents=$this->UserContactModel->getDocumentsInscription($id_inscription);

    //     return view($this->module . '\user/listDocumentsInscription',
    //        [    "documents"=>$documents,
    //             'titleView' => 'Dashboard',
    //             'context' => $this->context,
    //        ]
    //     );

    // }

    public function getFile($name_file)
    {
       
        //debug(APPPATH."/Documents/Documents_by_crm/".$name_file,true);
     header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($name_file) . '"');
    /*    header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($name_file));*/

        set_time_limit(0);
       
        readfile(APPPATH."Documents/Documents_by_crm/".$name_file);
        exit;
    }

    public function seeFile($name_file)
    {
       
        //debug(APPPATH."/Documents/Documents_by_crm/".$name_file,true);
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($name_file) . '"');
    /*    header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($name_file));*/

        set_time_limit(0);
       
        readfile(APPPATH."Documents/Documents_by_crm/".$name_file);
        exit;
    }


    // public function is_autorisation_modif_contact($id_contact)
    // {
    //     if($id_contact>0)
    //     {
    //         //s'il est superadmin, il peut
    //         if(session('loggedUserRoleId') && session('loggedUserRoleId')==1)
    //         {
    //             return TRUE;
    //         }
    //         else
    //         {
    //             //il peut s'il gère contact et pas superadmin
    //             $contacts = $this->UserContactModel->getContacts(session('loggedUserId'));
    //             if(!empty($contacts))
    //             {
    //                 $autorisation_contact=[];
    //                 foreach($contacts as $contact)
    //                 {
    //                     array_push($autorisation_contact,$contact->id_contact);
    //                     if(in_array($id_contact,$autorisation_contact))
    //                     {
    //                         return TRUE;
    //                     }
    //                 }
    //             }
               
    //         }
    //     }
    //     return FALSE;

    // }

    // public function updateContact($id_contact, $id_user=NULL, $validation=NULL)
    // {
    //     $user = sessionUser($id_user);

    //    if($id_contact>0)
    //    {
    //         if($this->is_autorisation_modif_contact($id_contact))
    //         {
    //             $contact=$this->UserContactModel->getOneContact($id_contact);
                
    //             // $indexes=["nom_contact","prenom","nom_court_institution","nom_long_institution","date_naissance","adresse","codepostal","localite","email","tel1","tel2","gsm1","gsm2"];
    //             $indexes=["date_creation", "name","lastname","birthday"];

    //             $dataView=new DataViewConstructor();

                
    //             $this->datas->indexes = $indexes;
    //             $this->datas->contact = $contact;
    //             $this->datas->id_contact = $id_contact;
    //             $this->datas->dataView = $dataView;
    //             $this->datas->fields = $dataView->getFields();
    //             $this->datas->validation = $validation;
    //             $this->datas->titleView = "Modification du contact $id_contact";
    //             $this->datas->user = $user;

    //             echo view($this->module . '\user/updateContact', (array) $this->datas);
    //         }
    //         else
    //         {
    //             echo "Erreur! Vous n'avez pas l'autorisation de modifier cette fiche!";
    //         }
    //     }
    //     else
    //     {
    //         echo "Erreur! Pas d'identifiant Contact fourni!";
    //     }
       
    // }


    // public function save_updateContact()
    // {

    //     //debug($this->request->getVar()); 
    //     $id_contact=$this->request->getVar("id_contact");
    //     $id_user=$this->request->getVar("id_user");

    //     if(empty($id_contact))
    //     {
    //         echo "Erreur! Pas d'id_contact disponible";

    //         return false;

    //     }

    //     if(empty($id_user))
    //     {
    //         echo "Erreur! Pas d'id_user disponible";
    //         return false;

    //     }

    //     if($this->is_autorisation_modif_contact($id_contact))
    //     {
    //         $indexes=$this->request->getVar("indexesForm");

    //         $dataView=new DataViewConstructor();
    //         $rules=$dataView->getRules($indexes,$dataView->getFields());
        
    //         if (!$this->validate($rules)&&!empty($rules)) 
    //         {
    //             echo $this->updateContact($id_contact,$id_user,$this->validator);
    //         } 
    //         else 
    //         {
    //             //treatment of data  
    //             // $id_contact_save=$dataView->saveData(
    //             //     $indexes,
    //             //     $this->request->getVar(),
    //             //     $dataView->getFields(),
    //             //     "contact",
    //             //     $id_contact
    //             // );  
                
    //             $contact=$this->UserContactModel->getOneContact($id_contact);

    //             $message= 'La fiche du contact "'.$contact->prenom.' '.$contact->nom.'" a été modifiée';
            
                
    //             return redirect()->to(base_url("user/contacts/list?id_user=$id_user"))->with("success", $message);
    //         }
    //     }
    //     else
    //     {
    //         echo "Erreur! Vous n'avez pas l'autorisation d'enregistrer les modifications de cette fiche!";
    //         return false;

    //     }
    // }
}
