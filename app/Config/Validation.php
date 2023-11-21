<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    
    // --------------------------------------------------------------------
    // Include Modules Validation Files
    // --------------------------------------------------------------------
   
    public function __construct()
    {
        if (file_exists(APPPATH . 'Modules')) {

            $modules = scandir(APPPATH . 'Modules');
    
            foreach ($modules as $module) {
                if(!preg_match('/_old$/', $module)) :
                    $this->entity_validation_get_recursive(APPPATH . 'Modules', $module);
                endif;
            }
        }
    }
    
    private function entity_validation_get_recursive($path, $module)
    {
        if ($module === '.' || $module === '..') return;

        if (is_dir($path . '/' . $module)) :
            $validationPath = $path . '/' . $module . '/Config/Validation.php';
            if (file_exists($validationPath)) :
                $namespace = '\\' . $module . '\Config\Validation';
                if(class_exists($namespace)) :
                    $class = new $namespace();
                    $functions = get_class_methods($class);
                    foreach($functions as $function) :
                        if(preg_match('/^rule/', $function) != false) :
                            $entity = explode('rule', $function)[1];
                            $this->{$module . ucfirst($entity)} = $class->$function();
                        endif;
                    endforeach;
                endif;
            else :
                $children = scandir($path . '/' . $module);
                foreach ($children as $child) :
                    if(!preg_match('/_old$/', $child)) :
                        $this->entity_validation_get_recursive($path . '/' . $module, $child);
                    endif;
                endforeach;
            endif;
        endif;
    }
 
}
