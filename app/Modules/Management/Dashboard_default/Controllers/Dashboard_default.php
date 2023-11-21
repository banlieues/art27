<?php

namespace Dashboard_default\Controllers;

use Base\Controllers\BaseController;
use Components\Libraries\ComponentOrderBy;
use Layout\Libraries\LayoutLibrary;

class Dashboard_default extends BaseController
{
	public function __construct()
	{
	   

	    parent::__construct(__NAMESPACE__);
			
		$layout_l = new LayoutLibrary();

		$this->context = 'dashboard';
		$this->datas->theme = $layout_l->getThemeByRef($this->context);
		$this->datas->context = $this->context;
		$this->viewpath = $this->module . "\Views\/"; 

		$this->componentOrderBy=new ComponentOrderBy();

		

		$this->autorisationManager = \Config\Services::autorisationModel();

		if(!$this->autorisationManager->is_autorise("dashboard_user"))
		{
			header("Location:".base_url("autorisation/no_autorisation"));
		}	
		
	}
	
	public function index()
	{
		
	    return view($this->viewpath."dashboard_default",(array) $this->datas);
	}
	
	
}
