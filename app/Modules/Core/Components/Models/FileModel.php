<?php

namespace Components\Models;

use Base\Models\BaseModel;
use CodeIgniter\Files\File;

class FileModel extends BaseModel
{
    protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;
	protected $table = 'document_upload';

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->table = $this->t_file;
        $this->pk = get_primary_key($this->table);
    }

    public function FileGet($id_file)
    {
        $file = database_decode($this->db->table($this->t_file)->where($this->pk, $id_file)->get()->getRow());
        
        if(empty($file)) return null;

        return $this->FileGetCalculatedFields($file);
    }

    private function FileGetCalculatedFields($file)
    {
        $file->clientName = !empty(trim($file->name)) ? $file->name : preg_replace('/^\d{12}_/', '', $file->url_file);

        if(file_exists(PATH_DOCU_DEMANDE . $file->url_file)):
            $CIfile = new File(PATH_DOCU_DEMANDE . $file->url_file);
            $file->type = $this->FileGetType($CIfile->guessExtension());
            $file->icon = $this->FileGetIcon($CIfile->guessExtension());
        else :
            $file->isUndefined = true;
        endif;

        return $file;
    }

    public function FilesGet($ids_file=null)
    {
        if(empty($ids_file)) return null;
        if(is_string($ids_file)) $ids_file = explode(',', str_replace(['[', ']', '(', ')', ' '], '', $ids_file));
        
        $files = database_decode($this->db->table($this->t_file)->whereIn("$this->t_file.$this->pk", $ids_file)->get()->getResult());
        $i = 0;
        foreach($files as $file) :
            $files[$i] = $this->FileGetCalculatedFields($file);
            $i++;
        endforeach;

        return $files;
    }

    public function ImgsGet($ids_file=null)
    {
        if(empty($ids_file)) return null;
        if(is_string($ids_file)) $ids_file = explode(',', str_replace(['[', ']', '(', ')', ' '], '', $ids_file));
        
        $files = database_decode($this->db->table($this->t_file)->whereIn("$this->t_file.$this->pk", $ids_file)->get()->getResult());

        $imgs = [];
        foreach($files as $file) :
            $file = $this->FileGetCalculatedFields($file);
            if(empty($file->isUndefined) && $file->type=='image') $imgs[] = $file;
        endforeach;

        return $imgs;
    }

    public function DocsGet($ids_file=null)
    {
        if(empty($ids_file)) return null;
        if(is_string($ids_file)) $ids_file = explode(',', str_replace(['[', ']', '(', ')', ' '], '', $ids_file));
        
        $files = database_decode($this->db->table($this->t_file)->whereIn("$this->t_file.$this->pk", $ids_file)->get()->getResult());

        $docs = [];
        foreach($files as $file) :
            $file = $this->FileGetCalculatedFields($file);
            if(empty($file->isUndefined) && $file->type!='image') $docs[] = $file;
        endforeach;

        return $docs;
    }

    public function FileGetType($ext)
    {
        switch($ext) :
            case 'aac' :
            case 'mid' :
            case 'midi' :
            case 'mp3' :
            case 'oga' :
            case 'opus' :
            case 'wav' :
            case 'weba' :
                return 'audio';
            case 'avif' :
            case 'bmp' :
            case 'gif' :
            case 'ico' :
            case 'jpeg' :
            case 'jpg' :
            case 'png' :
            case 'svg' :
            case 'tif' :
            case 'tiff' :
            case 'webp' :
                return 'image';
            case 'doc' :
            case 'docx' :
            case 'odt' :
            case 'txt' :
                return 'document';
            case 'odp' :
            case 'ppt' :
            case 'pptx' :
                return 'presentation';
            case 'csv' :
            case 'ods' :
            case 'xls' :
            case 'xlsx' :
                return 'table';
            case 'pdf' :
                return 'pdf';
            case 'avi' :
            case 'mp4' :
            case 'mpeg' :
            case 'ogv' :
            case 'ts' :
            case 'webm' :
                return 'video';
        endswitch;
    }

    private function FileGetIcon($ext)
    {
        $type = $this->FileGetType($ext);

        switch($type) :
            case 'audio' :
                return fontawesome('file-audio');
            case 'image' :
                return fontawesome('image');
            case 'document' :
                return fontawesome('file-alt');
            case 'presentation' :
                return fontawesome('table');
            case 'table' :
                return fontawesome('table');
            case 'pdf' :
                return fontawesome('file-pdf');
            case 'video' :
                return fontawesome('file-video');
        endswitch;
    }
}