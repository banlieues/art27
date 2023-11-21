<?php 

namespace Components\Controllers;

use Custom\Config\Sidebar;
use Base\Controllers\BaseController;

class Redirect extends BaseController 
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    private function get_context_by_href($href, $levels=null)
    {
        if(empty($levels)) :
            $sidebar = new Sidebar();
            $levels = $sidebar->menu;
        endif;

        foreach($levels as $level) :
            $level = (object) $level;

            if(!empty($level->href) && $level->href==$href) :
                $response = $level;
            elseif(!empty($level->children)) :
                $response = $this->get_context_by_href($href, $level->children);
            endif;
            if(!empty($response)) : return $response; endif;
        endforeach;
    }

    public function to($href)
    {
        $context = $this->get_context_by_href($href);
        
        $this->datas->context = $context->ref;
        $this->datas->titleView = t($context->label, __NAMESPACE__);

        return view('Layout\inprogress', (array) $this->datas);
    }

    public function error404()
    {
        if(!session('loggedUserId')) :
            return redirect()->to(base_url('identification/login?redirect=' . current_url()))->with('warning', "Vous devez d'abord vous connecter...");
        endif;

        $this->datas->title = "La page recherchée est introuvable.";
        $this->datas->url = current_url();
        $this->datas->titleView = t("Page introuvable", __NAMESPACE__);
        $this->datas->errorPage = '404';

        echo view('Layout\error404', (array) $this->datas);
    }

    public function forbidden403()
    {
        if(!session('loggedUserId')) return redirect()->to(base_url('identification/login?redirect=' . current_url()))->with('warning', "Vous devez d'abord vous connecter...");

        $this->datas->context = 'forbidden403';
        $this->datas->title = "Accès refusé.";
        $this->datas->url = $this->request->getGet('url') ?? previous_url();
        $this->datas->errorPage = '403';

        echo view('Layout\forbidden403', (array) $this->datas);
    }

}