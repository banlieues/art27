<?php

namespace Translator\Models;

use Base\Models\BaseModel;
use DataView\Libraries\DataViewConstructor;

class TranslatorModel extends BaseModel
{
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->table = $this->t_translator;
        $this->primaryKey = get_primary_key($this->table);
    }

    public function RowModelData($order=null, $request=null)
    {
        $this->select("
            $this->t_translator.*,
            CONCAT_WS(' ', updated_by.prenom, updated_by.nom) as updated_by_fullname,
            updated_by.nom as updated_by_nom,
            updated_by.prenom as updated_by_prenom,
            CONCAT_WS(' ', created_by.prenom, created_by.nom) as created_by_fullname,
            created_by.nom as created_by_nom,
            created_by.prenom as created_by_prenom,
        ");

        $this->join("$this->t_user as updated_by", "updated_by.id = $this->t_translator.updated_by", 'left');
        $this->join("$this->t_user as created_by", "created_by.id = $this->t_translator.created_by", 'left');

        $DataView = new DataViewConstructor();
        if(!empty($request)):
            if(!empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
                $fieldsSearch = array(
                    "$this->t_translator.label_fr",
                    "$this->t_translator.label_nl",
                    "$this->t_translator.module",
                    "$this->t_translator.ref",
                    "updated_by.nom",
                    "updated_by.prenom",
                    "created_by.nom",
                    "created_by.prenom",
                );
                $DataView->setQuerySearch($this, $fieldsSearch);
            endif;
            if(!empty($request->getGet('isEmpty'))) :
                $this->groupStart();
                    $this->where('label_nl', null);
                    $this->orWhere('trim(label_nl)', '');
                $this->groupEnd();
            endif;
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy("$this->t_translator.label_fr", 'asc');

        return $this;
    }

    public function RowGet($id_transl)
    {
        return $this->RowModelData()->find($id_transl);
    }

    public function RowsGet($order=null, $request=null, $no_pager=null)
    {
        $q = $this->RowModelData($order, $request);
        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $rows = $q->find();
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $rows = $q->paginate($per_page);
        endif;

        return $rows;        
    }

    public function RowsPagerGet()
    {
        return $this->pager;
    }

    public function RowDelete($id_transl)
    {
        $this->db->table($this->t_translator)->where('id_transl', $id_transl)->delete();
    }

    public function RowSave($post, $id_transl=null)
    {
        $post->updated_by = session('loggedUserId');
        if(!empty($id_transl)) :
            $post->updated_at = date('Y-m-d H:i:s');
            $this->db->table($this->t_translator)
                ->set(database_encode($this->t_translator, $post))
                ->where('id_transl', $id_transl)
                ->update();
        else :
            $row = $this->RowModelData()->where('label_fr', $post->label_fr)->where('module', '')->first();
            if(isset($row)) :
                if(isset($row->label_nl)) $post->label_nl = $row->label_nl;
                $this->delete($row->id_transl);
            endif;

            $post->created_by = session('loggedUserId');
            $this->db->table($this->t_translator)
                ->set(database_encode($this->t_translator, $post))
                ->insert();
            $id_transl = $this->db->InsertID();
        endif;

        $row = $this->RowGet($id_transl);
        $this->RowSaveInFile($row);
        
        return $id_transl;
    }

    public function RowSaveInFile($post)
    {
        foreach(['fr', 'nl'] as $lang) :
            if(empty($post->{'label_' . $lang})) continue;

            $value = $post->{'label_' . $lang};
            $path = APPPATH . 'Language/' . $lang . '/' . $post->module . '.php';
            if(!file_exists($path)) :
                $file = fopen($path, 'w');
                fclose($file);
                $datas = [];
                $datas[$post->ref] = $value;
            else :
                $datas = include($path);
                $datas[$post->ref] = $value;
                ksort($datas);
                
            endif;
            $string_data = '<?php

    // ' . $post->module . ' translation

    return ' . var_export($datas, true) . ';';
                file_put_contents($path, $string_data);

        endforeach;
    }

}