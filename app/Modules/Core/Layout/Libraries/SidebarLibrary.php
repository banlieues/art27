<?php

namespace Layout\Libraries;

use Autorisation\Libraries\AutorisationLibrary;
use Base\Libraries\BaseLibrary;
use Custom\Config\Sidebar;
use Layout\Libraries\LayoutLibrary;

class SidebarLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->sidebar = new Sidebar();
        $this->layout_l = new LayoutLibrary();
        $this->Autorisation = new AutorisationLibrary();
        // $this->themes = $this->layout_l->getThemes();
    }

    public function getMenu($context=null, $context_sub=null)
    {
        $menu = (object) $this->sidebar->menu;

        $i = 0;
        $html_children = '';
        foreach($menu as $sub) :
            $sub = (object) $sub;
            $theme = $this->layout_l->getThemeByRef($sub->ref);
            if($i==0) :
                $html_children .= '
                    <li class="sidebar-header bg-' . $theme->color . ' text-white-50">' . $sub->label . '</li>
                ';
            else :
                $html_children .= '
                    <li
                        class="sidebar-header bg-' . $theme->color . ' text-white-50 dropdown-toggle ' . $sub->ref . '" 
                        ref="' . $sub->ref . '"
                        data-bs-toggle="collapse" 
                        href="#' . $sub->ref . '" 
                        role="button"
                        >
                        ' . $sub->label . '
                    </li>
                ';
            endif;
            if(!empty($sub->children)) $html_children .= $this->getMenuSub($sub, $context, $context_sub);
            $i++;
        endforeach;

        $html = '
            <ul class="sidebar-nav" id="sidebar">
                ' . $this->getMenuLogo() . '
                ' . $html_children . '
            </ul>
        ';

        return $html;
    }

    private function getMenuLogo()
    {
        $logo = $this->sidebar->logo;
        $name = $this->sidebar->name;
        $themes = $this->layout_l->getThemes();
        $html = '
            <div class="sidebar-brand-off text-center bg-white border-end border-4 border-' . $themes->sidebar->color . '">
                <a href="' . base_url() . '">
                    <img 
                        src="' . base_url($logo) . '"
                        alt="' . $name . ' Logo" 
                        width="210" 
                        class="d-inline-block align-middle p-2"
                    />
                </a>
            </div>
        ';

        return $html;
    }

    private function getMenuSub($elem, $context=null, $context_sub=null)
    {
        $html_children = '';
        foreach($elem->children as $child) :
            $child = (object) $child;
            if(!empty($child->autorisation) && $this->db->fieldExists($child->autorisation, $this->t_autorisation) && !$this->Autorisation->is_autorise($child->autorisation)) continue;
            $theme = $this->layout_l->getThemeByRef($child->ref);
            $href = !empty($child->href) ? base_url($child->href) : '';
            $href = !empty($child->session) ? session($child->session) : $href;
            if(!empty($child->session) && empty(session($child->session))) :
                $li_class = 'd-none';
            else :
                $li_class = ($context && $context==$child->ref) ? 'border-end border-' . $theme->color . ' active' : '';
            endif;
            $a_class = ($context && $context==$child->ref) ? 'text-' . $theme->color : '';
            $html_children .= '
                <li class="sidebar-item ' . $li_class . '">
                    <a href="' . $href . '" class="d-flex align-items-center ' . $a_class . '">
                        <div class="ms-1">' . $theme->icon . '</div>
                        <div> ' . $child->label . ' </div>
                    </a>
                </li>
            ';
            if(!empty($child->children) && $context && $context==$child->ref) $html_children .= $this->getMenuSubSub($child, $context_sub);
        endforeach;

        $accordion_state = SIDEBAR_WEBSTORAGE ? '' : 'show';
        $accordion_data = (ACCORDION_SIDEBAR) ? '#sidebar' : '';
        $collapse_class = !empty($elem->nocollapse) ? '' : 'collapse multi-collapse';
        $html = '
            <div 
                class="' . $collapse_class . ' ' . $accordion_state . '" 
                id="' . $elem->ref . '" 
                data-bs-parent="' . $accordion_data . '"
                >
                ' . $html_children . '
            </div>
        ';

        return $html;
    }    
    
    private function getMenuSubSub($elem, $context_sub=null)
    {        
        $html_children = '';
        $theme = $this->layout_l->getThemeByRef($elem->ref);
        foreach($elem->children as $child) :
            $child = (object) $child;
            if(!empty($child->autorisation) && $this->db->fieldExists($child->autorisation, $this->t_autorisation) && !$this->Autorisation->is_autorise($child->autorisation)) continue;
            $a_class = ($context_sub && $context_sub==$child->ref) ? 'text-' . $theme->color . ' active' : '';
            $href = !empty($child->href) ? base_url($child->href) : '';
            $href = !empty($child->session) ? session($child->session) : $href;
            $html_children .= '
                <li class="sidebar-item text-' . $theme->color . ' border-end border-' . $theme->color . '">
                    <a class="ps-5 ' . $a_class . '" href="' . $href . '" style="line-height: 30px !important;"
                        >
                        ' . $child->label . '
                    </a>
                </li>
            ';
        endforeach;

        $html = '
            <div>
                ' . $html_children . '
            </div>
        ';

        return $html;
    }

}