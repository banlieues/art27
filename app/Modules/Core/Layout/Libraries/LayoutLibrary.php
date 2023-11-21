<?php

namespace Layout\Libraries;

use Custom\Config\Sidebar;
use Custom\Config\Theme;

class LayoutLibrary
{
    public function __construct()
    {
        $this->theme_config = new Theme();
        $this->sidebar_config = new Sidebar();
    }

    public function getThemes()
    {
        $themes = (object) $this->theme_config->theme;

        $data = (object) [];

        foreach($themes as $ref=>$theme) :
            $theme = (object) $theme;
            $data->$ref = (object) [];
            $data->$ref->color = !empty($theme->color) ? $theme->color : '';
            $data->$ref->icon = !empty($theme->icon) ? fontawesome($theme->icon) : '';
        endforeach;

        $data->main->name = $this->sidebar_config->name;
        $data->main->logo = $this->sidebar_config->logo;

        return $data;
    }

    public function getThemeByRef($ref)
    {
        $theme = !empty($this->theme_config->theme[$ref]) ? (object) $this->theme_config->theme[$ref] : null;

        $data = (object) [];
        $data->color = !empty($theme->color) ? $theme->color : '';
        $data->icon = !empty($theme->icon) ? fontawesome($theme->icon) : '';

        return $data;
    }
}