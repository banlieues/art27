<?php 

namespace Translator\Libraries;

use Base\Libraries\BaseLibrary;
use Components\Libraries\DatabaseLibrary;
use Translator\Config\Globals;

class TranslatorLibrary extends BaseLibrary
{
    protected $modules = ['co', 'em', 'en', 'ev', 'fe', 'li', 'ma', 're', 'rp', 'tp'];

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }
    
    // public function check_database__translator()
    // {
    //     $file = $this->path . '/json/table.json';
    //     if(!$this->db->tableExists($this->t_translator)) :
    //         $this->check_database__translator_import_data();
    //     endif;
    //     $this->db_l->check_database_table_common($this->t_translator, $file);
    // }

    // private function check_database__translator_import_data()
    // {
    //     foreach($this->modules as $module) :
    //         $table = $module . '_translation';
    //         if($this->db->tableExists($table)) :
    //             $rows_translator = $this->db->table($this->t_translator)->get()->getResult();
    //             $indexes = [];
    //             foreach($rows_translator as $row_translator) $indexes[] = mb_strtolower($row_translator->label_fr);
    //             $rows = $this->db->get($table)->getResult();
    //             foreach($rows as $row) :
    //                 $label_fr = htmlspecialchars(trim($row->label_fr));
    //                 $label_nl = htmlspecialchars(trim($row->label_nl));
    //                 if(!empty($label_fr) && !in_array(mb_strtolower($label_fr), $indexes)) :
    //                     $this->db->set(['label_fr'=>$label_fr])->insert($this->t_translator);
    //                     $indexes[] = mb_strtolower($label_fr);
    //                     // _print($this->db->last_query());
    //                 endif;
    //                 if(!empty($label_nl)) :
    //                     $this->db->set(['label_nl'=>$label_nl])->where('label_fr', $label_fr)->update($this->t_translator);
    //                     // _print($this->db->last_query());
    //                 endif;
    //             endforeach;
    //         else :
    //             $files_init = glob_recursive(APPPATH . 'modules/' . $module . '/', '*init.json');
    //             $files_init = !empty($files_init) ? $files_init : [];
    //             $files_list = glob_recursive(APPPATH . 'modules/' . $module . '/', '*list.json');
    //             $files_list = !empty($files_list) ? $files_list : [];
    //             $files = array_merge($files_init, $files_list);
    //             $this->import_labels_from_files($files);
    //         endif;
    //     endforeach;
    // }

    public function import_labels_from_files($files)
    {
        foreach($files as $file) :
            if(file_exists($file)) :
                $fields = json_decode(file_get_contents($file));
                foreach($fields as $ref=>$field) :
                    $this->import_label_from_file_recursive($field);
                endforeach;
            endif;
        endforeach;
    }

    private function import_label_from_file_recursive($field)
    {
        // case list.json
        if(is_array($field)) :
            foreach($field as $elem) :
                if(is_object($elem)) $this->import_label_from_file_line($elem);
            endforeach;
        // case form.json, annotation.json
        elseif(is_object($field)) :
            $this->import_label_from_file_line($field);
            foreach($field as $children) :
                if(is_array($children)) foreach($children as $child) $this->import_label_from_file_recursive($child);
            endforeach;
        endif;
    }
    
    public function import_label_from_file_line($field)
    {
        if(isset($field->label_fr)) :
            $label_fr = htmlspecialchars(trim($field->label_fr));
            $row = $this->db->table($this->t_translator)->where('label_fr', $label_fr)->get()->getRow();
            if(isset($row) && empty($row->label_nl) && !empty($field->label_nl)) :
                $label_nl = htmlspecialchars(trim($field->label_nl));
                $data = (object) [];
                $data->label_nl = $label_nl;
                $this->db->table($this->t_translator)->set(database_encode($this->t_translator, $data))->insert();
            endif;
        endif;
    }

    public function list($module)
    {
        $data = (object) [];
        $data->form_id = 'translatorForm';
        $data->rows = $this->db->table($this->t_translator)>get()->getResult();

        $title = 'Traduction des champs';
        $title_button = '
            <button type="button" class="btn btn-sm btn-outline-secondary mx-1"
                onclick="location.reload();"
                > 
                Annuler les modifications 
            </button>
            <button form="' . $data->form_id . '" class="btn btn-sm btn-success mx-1"> 
                Enregistrer les modifications 
            </button>
        ';
        
        $this->load->library('layout_library', ['module'=>$module]);
        $this->layout_library->set_title($title);
        $this->layout_library->set_subtitle($title, $title_button);      
        $this->layout_library->view('list', $data);
    }
    
    public function export()
    {
        $fields = $this->db->table($this->t_translator)->get()->getResult();
        $file = $this->export_convert_rows_to_csv($fields);
        if(file_exists($file)) $this->export_download($file);
    }

    private function export_convert_rows_to_csv($fields)
    {
        ini_set("auto_detect_line_endings", true);

        $file = $this->path . '/files/export.csv';
        $folder = pathinfo($file)['dirname'];
        if(!is_dir($folder)) mkdir($folder, 0777, true);
        $csv = fopen($file, 'w');
        foreach($fields as $field) :
            $data = (object) [];
            $data->label_fr = $field->label_fr;
            if(!empty($field->label_nl)) $data->label_nl = $field->label_nl;
            fputcsv($csv, (array) $data, ',');
        endforeach;
        fclose($csv);

        return $file;
    }

    private function export_download($file)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="homegrade_translation.csv"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    private function import_upload_config()
    {
        $config['upload_path'] = $this->path . '/files';
        $config['file_name'] = 'import';
        $config['allowed_types'] = 'csv';
        $config['overwrite'] = true;
        $this->load->library('upload', $config);

        return $config;
    }

    public function import()
    {
        $config = $this->import_upload_config();
        if(!$this->upload->do_upload('translator_file')) :
            $error = array('error' => $this->upload->display_errors());
            $this->session->setFlashdata('warning', "Échec lors d'import des données de traduction. <br>" . implode('<br>', $error));
        else :
            $file = $config['upload_path'] . '/' . $config['file_name'] . '.' . $config['allowed_types'];
            if($this->import_process($file)) :
                $this->session->setFlashdata('success', "Les données de traduction ont bien été chargées.");
            else :
                $this->session->setFlashdata('warning', "Échec lors d'import des données de traduction. <br> Chaque ligne doit être séparée par un saut à la ligne et les colonnes par une virgule.");
            endif;
        endif;

        header('Location: ' . $_SERVER["HTTP_REFERER"] );
    }

    private function import_process($file)
    {
        $rows = convert_file_csv_to_array($file);

        if(empty($rows) && count($rows)<=1) return false;

        $this->db->transStart();
        foreach($rows as $row) :
            if(!empty($row) && !empty($row[0]) && !empty($row[1])) :
                $label_fr = $row[0];
                $data = (object) [];
                $data->label_nl = $row[1];
                $this->db->where('label_fr', $label_fr)->update($this->t_translator, $data);
            endif;
        endforeach;

        if($this->db->transComplete() == false) return false;
        else return true;
    }
}