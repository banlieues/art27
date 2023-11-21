<?php
/**
 * This is User Profile Controller 
**/

namespace Administrator\Controllers;

use Base\Controllers\BaseController;

use Administrator\Models\IdentificationModel;
use Administrator\Models\UserProfileModel;

use Lists\Models\RoleModel;
use Lists\Models\GenderModel;
use Lists\Models\CountryModel;

class UserProfile extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->IdentificationModel = new IdentificationModel();
        $this->UserProfileModel = new UserProfileModel();
        $this->ListRoleModel = new RoleModel();
        $this->ListGenderModel = new GenderModel();
        $this->ListCountryModel = new CountryModel();

    }

    // public function index($id_user=NULL)
    // {         
    //     // $profile_infos = $this->UserProfileModel->where('user_id', $id_user)->first();
     
    //     // $gender_id = $profile_infos['gender_id'];
    //     // $country_id = $profile_infos['country_id'];
    //     // $avatar_name = $profile_infos['avatar'] ?? AVATAR_DEFAULT;

    //     $user = sessionUser($id_user);
    //     // $role_id = $user->role_id;

    //     $this->datas->title = lang('UserProfile.title');
    //     $this->datas->subtitle = lang('UserProfile.view');
    //     $this->datas->titleView = 'User profile';
    //     $this->datas->context = $this->context;
    //     $this->datas->icon = 'fa fa-id-card';
    //     $this->datas->user = $user;
    //     // $this->datas->roles_infos = $this->ListRoleModel->find($role_id);
    //     // $this->datas->genders_infos = $this->ListGenderModel->find($gender_id);
    //     // $this->datas->countries_infos = $this->ListCountryModel->find($country_id);
    //     // $this->datas->profile_infos = $profile_infos;

    //     return view($this->module . '\profile/index', (array) $this->datas);
    // }

    // public function details($id_user=NULL)
    // {
    //     if(is_null($id_user)) $id_user=session('loggedUserId');

    //     $user = sessionUser($id_user);

    //     $profile_infos = $this->UserProfileModel->where('user_id', $id_user)->first();
    //     $avatar_name = $profile_infos['avatar'] ?? AVATAR_DEFAULT;

    //     $this->datas->title = lang('UserProfile.title');
    //     $this->datas->subtitle = lang('UserProfile.details');
    //     $this->datas->titleView = lang('UserProfile.title');
    //     $this->datas->context = $this->context;
    //     $this->datas->icon = 'fa fa-id-card';
    //     $this->datas->user = $user;
    //     $this->datas->roles_infos = $roles_infos;
    //     $this->datas->genders_infos = $genders_infos; 
    //     $this->datas->countries_infos = $countries_infos;
    //     $this->datas->profile_infos = $profile_infos;

    //     return view($this->module . '\profile/details', (array) $this->datas);
    // }

    public function edit($id_user=NULL)
    {
        $user = sessionUser($id_user);
        // $roles_infos = $this->ListRoleModel->find($user->role_id);

        // $profile_infos = $this->UserProfileModel->where('user_id', $id_user)->first();
        // $gender_id = $profile_infos['gender_id'];
        // $genders_infos = $this->ListGenderModel->find($gender_id);

        // $country_id = $profile_infos['country_id'];
        // $countries_infos = $this->ListCountryModel->find($country_id);

        // $users_genders_total = $this->ListGenderModel->countAll();
        // $users_countries_total = $this->ListCountryModel->countAll();
        // $avatar_name = $profile_infos['avatar'] ?? AVATAR_DEFAULT;

        $this->datas->context = 'profile';
        $this->datas->title = lang('UserProfile.title');
        $this->datas->subtitle = lang('UserProfile.edit');
        $this->datas->titleView = 'Dashboard';
        $this->datas->icon = 'fa fa-id-card';
        $this->datas->user = $user;
        // $this->datas->roles_infos = $roles_infos;
        // $this->datas->genders_infos = $genders_infos;
        // $this->datas->countries_infos = $countries_infos;
        // $this->datas->profile_infos = $profile_infos;
        // $this->datas->users_genders_total = $users_genders_total;
        // $this->datas->users_countries_total = $users_countries_total;

        if (!$this->request->getPost()) 
        {
            return view('Administrator\user/profile-edit', (array) $this->datas);
        }

        // $gender_id = $this->request->getPost('gender_id');
        // $gender_infos = $this->ListGenderModel->find($gender_id);

        // $country_id = $this->request->getPost('country_id');
        // $countries_infos = $this->ListCountryModel->find($country_id);

        // $this->datas->gender_id = $gender_id;
        // $this->datas->gender_label = $gender_infos['label'];
        // $this->datas->gender_description = $gender_infos['description'];
        // $this->datas->country_id = $country_id;
        // $this->datas->countries_infos = $countries_infos;
        // $this->datas->country_label = $countries_infos['label'];
        // $this->datas->country_description = $countries_infos['description'];
        // $this->datas->country_capitale = $countries_infos['capitale'];
        // $this->datas->user = $user;

        return json_encode($this->datas);
    }

    public function save($id_user=NULL)
    {
        if(is_null($id_user)) $id_user = session('loggedUserId');

        $user = sessionUser($id_user);

        $this->datas->context = 'profile';
        $this->datas->icon = 'fa fa-id-card';
        $this->datas->subtitle = lang('UserProfile.subtitle');
        $this->datas->user = $user;
        $this->datas->title = lang('UserProfile.title');
        $this->datas->titleView = 'Enregistrer';
        // $this->datas->user = sessionUser($id_user);
        // $this->datas->nom = $this->request->getPost('nom');
        // $this->datas->prenom = $this->request->getPost('prenom');

        // $this->datas->user = [
        //     'username'=>'tot',
        //     'nom'=>$this->request->getPost('nom'),
        //     'prenom'=>$this->request->getPost('prenom'),
        //     'role_id'=>$this->request->getPost('role_id'),
        //     'email'=>$this->request->getPost('email'),
        //     "avatar_path"=>$this->request->getPost('avatar_path'),
        //     "avatar_name"=>$this->request->getPost('avatar_name'),
        //     "username"=>$this->request->getPost('username'),
        //     'is_actif'=>$this->request->getPost('is_actif')
        // ];

        // $this->datas->profile_infos = [
        //     'role_id'=>$this->request->getPost('role_id'),
        //     'website' => $this->request->getPost('website'),
        //     'phone' => $this->request->getPost('phone'),
        //     'gsm'=> $this->request->getPost('gsm'),
        //     'role_id'=>$this->request->getPost('role_id')
        // ];
        
    //    $validation = $this->validate([
    //         'email' => [
    //             //'rules' => 'required|valid_email|is_unique[user_accounts.email]',
    //             'rules' => 'required|valid_email',
                
    //             'errors' => [
    //                 'required' => 'Email est requis ...',
    //                 'valid_email' => "Le format de l'email est invalide ...",
    //                 'is_unique'=>"Cette adresse est déjà utilisée …"
    //             ]
    //         ],
           
    //     ]);
        //$validation=TRUE;
        if($this->validation->run($this->request->getPost(), $this->module . 'Email') == FALSE)
        // if (!$validation)
        {
            // $this->datas->validation = $this->validator;
            $this->session->setFlashdata('warning', $this->validation->listErrors());

            return view($this->module . '\profile/edit', (array) $this->datas);
        }

        else
        {
            $updated_at = date('Y-m-d H:i:s');

            $values = [
                'nom' => $this->request->getPost('nom'),
                'prenom' => $this->request->getPost('prenom'),
                'email' => $this->request->getPost('email'),
                'role_id' => $this->request->getPost('role_id'),
                'is_actif' => $this->request->getPost('is_actif'),
                'valided' => 1,
                'updated_at' => $updated_at,
                'updated_by' => session('loggedUserId'),
            ];

            $update_user_account = $this->IdentificationModel->update($id_user, $values);

            if (!$update_user_account) :

                return redirect()->back()->with('danger', "L'utilisateur n'a pas pu être mis à jour.");

            else :
                $values = [
                    'phone' => $this->request->getPost('phone'),
                    'website' => $this->request->getPost('website'),
                    'updated_at' => $updated_at,
                    'updated_by' => session('loggedUserId'),
                ];

                $update_user_profile = $this->UserProfileModel->update($user->id_profile, $values);

                if (!$update_user_profile) :
                
                    return redirect()
                        ->to("user/profile/index/$id_user")
                        ->with('danger', "Le profil de l'utilisateur n'a pas pu être mis à jour.");
                
                else :
                
                    return redirect()
                        ->to("user/profile/index/$id_user")
                        ->with('success', "Les données de l'utilisateur ont bien été mis à jour.");
                
                endif;
            endif;
        }
    }

}
