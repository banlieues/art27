<?php

namespace Administrator\Controllers;

use Administrator\Models\UserModel;
use Base\Controllers\BaseController;

class Autorisation extends BaseController
{
    public $default_autorisation_config;

    public function __construct()
    {
        if(session()->get("loggedUserRoleId")!=1)
        {
            return redirect()->to(base_url('forbidden'));
            // header("Location:".base_url("identification/logout"));
        }

        parent::__construct(__NAMESPACE__);

    }

    public function index()
    {
        $id_user = $this->user->id;

        if(!$this->Autorisation->is_autorise("autorisation_r") && $id_user!=session('loggedUserId')) return redirect()->to(base_url('forbidden'));

        if($this->request->getPost()) :
            $this->Autorisation->save_autorisation();

            $id_user_get = ($this->request && $this->request->getGet('id_user')) ? 'id_user=' . $this->request->getGet('id_user') : '';
    
            return redirect()->to(base_url("user/autorisation?$id_user_get"))->with("success","Les autorisations ont été modifiées");
        endif;

        $this->context = "user";

        $config = new \Custom\Config\Autorisation();
        $id_user = $this->user->id;
        $user_infos = $this->UserModel->find($id_user);
        
        $this->datas->avatar_default = AVATAR_DEFAULT;
        $this->datas->avatar_name = !empty($user_infos->avatar) ? $user_infos->avatar : AVATAR_DEFAULT;
        $this->datas->avatar_path = AVATAR_PATH;
        $this->datas->context = 'user';
        $this->datas->context_sub = 'autorisation';
        $this->datas->default_config = $config->default_autorisation;
        $this->datas->entities_config = $config->entities;
        $this->datas->fieldsAutorisation = $this->db->getFieldNames($this->t_autorisation);
        $this->datas->outils_config = $config->outils;
        $this->datas->userAutorisations = $this->Autorisation->get_autorisation($id_user);
        $this->datas->user_infos = $user_infos;
        $this->datas->titleView = $id_user==session('loggedUserId') ? 'Mes autorisations' : 'Autorisations de ' . fullname($user_infos->prenom, $user_infos->nom);

        $this->datas->typeDataView = "read";

        return view('Administrator\user/autorisation', (array) $this->datas);
    }

    // public function edit()
    // {
    //     if(!$this->Autorisation->is_autorise("autorisation_u")) return redirect()->to(base_url('forbidden'));

    //     if($this->request->getPost()) :
    //         $this->Autorisation->save_autorisation();

    //         $id_user_get = ($this->request && $this->request->getGet('id_user')) ? 'id_user=' . $this->request->getGet('id_user') : '';
    
    //         return redirect()->to(base_url("user/autorisation?$id_user_get"))->with("success","Les autorisations ont été modifiées");
    //     endif;

    //     $this->datas->typeDataView = "update";

    //     return view('Administrator\user/autorisation', (array) $this->datas);
    // }
}
   

