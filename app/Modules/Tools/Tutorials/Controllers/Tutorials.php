<?php
/**
 * This is Tutorials Module Controller 
**/

namespace Tutorials\Controllers;

use App\Controllers\BaseController;

class Tutorials extends BaseController
{
    protected $context;
    protected $path;

    public function __construct()
    {
        $this->context = "tutorials";
        $this->viewpath = "Tutorials\Views";
    }

	public function index()
	{
		$data = [
			'title' => lang('Tutorials.title'),
            'subtitle' => '',
            'titleView' => 'Tutorials',
            'context' => $this->context,
			'icon' => 'fa fa-user-graduate',
		];

		return view($this->viewpath . '\index', $data);
	}

	public function ci4()
	{
		$data = [
			'title' => lang('Tutorials.title'),
            'subtitle' => lang('Tutorials.ci4'),
            'titleView' => 'CodeIgniter 4',
            'context' => $this->context,
			'icon' => 'fa fa-fire',
		];

		return view($this->viewpath . '\ci4', $data);
	}

	public function bs5()
	{
		$data = [
			'title' => lang('Tutorials.title'),
            'subtitle' => lang('Tutorials.bs5'),
            'titleView' => 'Bootstrap 5',
            'context' => $this->context,
			'icon' => 'fab fa-bootstrap',
		];

		return view($this->viewpath . '\bs5', $data);
	}
}
