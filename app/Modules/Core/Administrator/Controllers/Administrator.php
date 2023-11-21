<?php

namespace Administrator\Controllers;

use Base\Controllers\BaseController;

class Administrator extends BaseController
{
    public function __construct()
    {
        if(session('loggedUserRoleId')!=1)
        {
            header("Location:" . base_url("identification/logout"));
        }

        parent::__construct(__NAMESPACE__);

        $this->context = "administrator";
    }

    public function index()
    {
        return redirect()->to(base_url('user/list'));
    }

    public function settings()
    {
        $this->datas->title = lang('Administrator.administrator_settings');
        $this->datas->subtitle = lang('Administrator.view');
        $this->datas->titleView = lang('Administrator.settings');
        $this->datas->context = 'administrator_settings';
        $this->datas->icon = icon('settings');

        // TODO ...
        $action = $this->request->getGet('action');
        // if (!$this->request->getPost())
        if ($action == 'save' || $action == 'cancel')
        {
            session()->setFlashdata('info', 'Administrator settings is under construction ...');
        }

        return view($this->module . '\administrator/settings', (array) $this->datas);
    }

}
