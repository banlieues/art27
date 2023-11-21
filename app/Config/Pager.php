<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Templates
     * --------------------------------------------------------------------------
     *
     * Pagination links are rendered out using views to configure their
     * appearance. This array contains aliases and the view names to
     * use when rendering the links.
     *
     * Within each view, the Pager object will be available as $pager,
     * and the desired group as $pagerGroup;
     *
     * @var array<string, string>
     */
    public array $templates = [
        'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',

        'bs_full'        => 'Layout\Views\pagers\bs_full',
        'bs_full_ajax'   => 'Layout\Views\pagers\bs_full_ajax',
        'bs_simple'      => 'Layout\Views\pagers\bs_simple',
        'bs_amethyst'    => 'Layout\Views\pagers\bs_amethyst',
        'bs_info'        => 'Layout\Views\pagers\bs_info',
        'bs_success'     => 'Layout\Views\pagers\bs_success',
        'bs_orange'      => 'Layout\Views\pagers\bs_orange',
        'bs_pink'        => 'Layout\Views\pagers\bs_pink',
        'bs_office'      => 'Layout\Views\pagers\bs_office',


        'bs_dark'      => 'Layout\Views\pagers\bs_dark',
        'custom_pager'   => 'Layout\Views\pagers\custom',
    ];

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     */
    public int $perPage = 20;
}
