<?php

use Translator\Models\TranslatorModel;

$db = db_connect();

if(! function_exists('LanguageSessionGet')) :
    function LanguageSessionGet()
    {
        if(empty(session('loggedUserLocale'))) return 'fr';

        return session('loggedUserLocale');
    }
endif;

if(! function_exists('label_convert_to_ref')) :
    function label_convert_to_ref($label)
    {
        $label = remove_accents(trim($label));
        $label = str_replace([',', '’', '\'', '"', ':', ' ', '(', ')'], '_', $label);
        $label = str_replace(['.', '!', '?'], '', $label);
        $label = preg_replace('/_+/', '_', $label);
        $label = urlencode($label);

        return $label;
    }
endif;

if(! function_exists('t')) :
    function t($label_fr, $namespace, $param=null)
    {
        $module = !empty($namespace) ? explode('\\', $namespace)[0] : "UndefinedModule";
        $lang = !empty($param->lang) ? $param->lang : LanguageSessionGet();;

        $param = !empty($param) ? (object) $param : (object) [];
        $param->align = !empty($param->align) ? $param->align : 'block';
        $param->withButton = (isset($param->withButton) && $param->withButton==false) ? false : true;
        $param->lang = $lang;
        $param->module = $module;
        $vars = isset($param->variables) ? $param->variables : [];

        $ref = !empty($param->ref) ? $param->ref : label_convert_to_ref($label_fr);
        
        if(session('loggedUserLocale')=='bi') :
            if(lang("$module.$ref", $vars, 'nl')=="$module.$ref") :
                $label_nl = translator_box($label_fr, $ref, $module, 'nl', $param);
            else :
                $label_nl = lang("$module.$ref", $vars, 'nl');
            endif;
            $label = $param->align == 'inline' ?
                $label_fr . '<small> - ' . $label_nl . '</small>' :
                '
                    <div class="d-inline">
                        <div style="line-height:1;">
                            ' . $label_fr . ' <br>
                            <small>' . $label_nl . '</small> 
                        </div>
                    </div>
                ';
        else :
            if(lang("$module.$ref", $vars, $lang)=="$module.$ref") :
                if($lang=='fr') :
                    $post = (object) [];
                    $post->ref = $ref;
                    $post->label_fr = $label_fr;
                    $post->module = explode('\\', $namespace)[0];
                    $TranslatorModel = new TranslatorModel();
                    $TranslatorModel->RowSaveInFile($post);
                    $label = $label_fr;
                else :
                    $label = translator_box($label_fr, $ref, $module, $lang, $param);
                endif;
            else :
                $label = lang("$module.$ref", $vars, $lang);
            endif;
        endif;

        return $label;
    }
endif;

if(!function_exists('translator_box')) :
    function translator_box($label_fr, $ref, $module, $lang, $param)
    {
        $TranslatorModel = new TranslatorModel();
        $row = $TranslatorModel->RowModelData()->where(['ref' => $ref, 'module' => $module])->first();
        if(!isset($row->id_transl)) :
            $data = (object) [];
            $data->ref = $ref;
            $data->label_fr = $label_fr;
            $data->module = $module;
            
            $id_transl = isset($row->id_transl) ? $row->id_transl : null;
            $TranslatorModel->RowSave($data, $id_transl);
            $row = $TranslatorModel->RowModelData()->where(['ref' => $ref, 'module' => $module])->first();
        endif;

        if($param->withButton==false) :
            $box = '[' . $row->label_fr . ']';
        else :
            $box = '
                <div class="box-translator d-inline" 
                    id_transl="' . $row->id_transl . '"
                    lang="' . $lang . '"
                    >
                    [' . $row->label_fr . ']<button type="button" class="btn-translator btn btn-sm p-0">' . fontawesome('tumblr-square') . '</button>
                </div>
            ';
        endif;

        return $box;
    }
endif;

// if(! function_exists('translator')) :
//     function translator($label_fr, $param=null)
//     {
//         $param = !empty($param) ? (object) $param : (object) [];
//         $param->align = !empty($param->align) ? $param->align : 'block';
//         $param->withButton = (isset($param->withButton) && $param->withButton==false) ? false : true;
//         $param->lang = !empty($param->lang) ? $param->lang : LanguageSessionGet();

//         $label_fr = htmlspecialchars(trim($label_fr));
//         $label = '';
//         if($param->lang == 'fr') :
//             $label = $label_fr;
//         else :
//             $t_translator = 'translator';
//             $db = db_connect();
//             $row = $db->table($t_translator)->where('label_fr', $label_fr)->get()->getRow();
//             if(!isset($row)) :
//                 $db->table($t_translator)->set('label_fr', $label_fr)->insert();
//                 $row = $db->table($t_translator)->where('label_fr', $label_fr)->get()->getRow();
//             endif;

//             if($param->lang=='bi') :
//                 $label = translator_bi($row, $param);
//             else :
//                 if(empty($row->{'label_' . $param->lang})) :
//                     $label = translator_box($row, $param);
//                 else :
//                     $label = htmlspecialchars($row->{'label_' . $param->lang});
//                 endif;
//             endif;
//         endif;

//         return $label;
//     }
// endif;

// if(!function_exists('translator_box')) :
//     function translator_box($row, $param)
//     {
//         $data = (object) [];
//         $data->label_fr = $row->label_fr;

//         return ($param->withButton==false) ? 
//             $row->label_fr : 
//             '<span class="box-translator" id_transl="' . $row->id_transl . '">
//                 ' . htmlspecialchars($row->label_fr) . '
//                 <button type="button" class="btn-translator btn btn-sm p-0 ms-1">
//                     <small>' . fontawesome('tumblr-square') . '</small>
//                 </button>
//             </span>';
//     }
// endif;

// if(! function_exists('translator_bi')) :
//     function translator_bi($row, $param)
//     {
//         $label = '';
//         $label_fr = htmlspecialchars($row->label_fr);

//         $data = (object) [];
//         $data->label_fr = $label_fr;
//         if($param->align == 'block') : 
            
//             if(!empty($row->label_nl)) :
//                 if($row->label_nl == $row->label_fr) $label_nl = '<span class="invisible">-</span>';
//                 else $label_nl = htmlspecialchars($row->label_nl);
//             else :
//                 $label_nl = ($param->withButton==false) ? '-' : translator_box($row, $param);
//             endif;

//             if($param->withButton==false) :
//                 $label = $label_fr . '<br>' . $label_nl;
//             else :
//                 $label = '
//                     <span>
//                         <div style="line-height:1;">
//                             ' . $label_fr . ' <br>
//                             <small>' . $label_nl . '</small> 
//                         </div>
//                     </span>
//                 ';
//             endif;

//         elseif($param->align == 'inline') :
//             if(!empty($row->label_nl)) :
//                 if($row->label_nl == $label_fr) $label_nl = '';
//                 else $label_nl = ' - ' . htmlspecialchars($row->label_nl);
//             else :
//                 $label_nl = ($param->withButton==false) ? ' -' : translator_box($row, $param);
//             endif;
//             if($param->withButton==false) :
//                 $label = $label_fr . $label_nl;
//             else :
//                 $label = $label_fr . '<small> - ' . $label_nl . '</small>';
//             endif;
//         endif;

//         return $label;
//     }
// endif;