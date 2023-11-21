<?php

namespace Components\Controllers;

use Base\Controllers\BaseController;
use Components\Models\Base64Model;

class Base64 extends BaseController
{

    public function __construct()
    {

        $this->base64Model=new Base64Model();
    }

    public function index()
    {

       

       /* $db= \Config\Database::connect();
        $test=$db->table("document_upload")
                ->where("contentByte<>",'')
                ->get()
                ->getResult();

        echo count($test);
          
        if(empty($test->url_file))
        {
            $name_file=$test->id.'_'.slugify_name_file($test->name);
        }
        else
        {
            $name_file=$test->url_file;
        }

       

        $this->base64Model->convert_direct_base64($test->contentByte,$name_file);*/

        

    }

    public function mass64()
    {
        return view("Components\mass_64/index");
    }
    

    public function post()
    {
    
        return view("Components\mass_64/inc/post");
    }

}