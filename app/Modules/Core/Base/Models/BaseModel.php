<?php

namespace Base\Models;

use Base\Libraries\InitLibrary;
use CodeIgniter\Model;
use Translator\Libraries\TranslatorLibrary;

class BaseModel extends Model 
{
    protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct($namespace=null)
    {
        parent::__construct();
        
        $InitLibrary = new InitLibrary();
        $InitLibrary->GetHelpers();
        $globals = $InitLibrary->GetGlobals($namespace);
        foreach($globals as $key=>$value) $this->$key = $value;

        $this->transl_l = new TranslatorLibrary();
        // $this->TranslatorLibrary->check_database__translator();

        $this->db = db_connect();
        $this->curl = \Config\Services::curlrequest();
        $this->request = \Config\Services::request();
    }
}
