<?php

namespace Liste\Libraries;

use Base\Libraries\BaseLibrary;

class ListeLibrary extends BaseLibrary 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }
    
    public function TableLabel($table)
    {
        $config_liste = new \Custom\Config\Liste();
        $entities = database_decode($config_liste->config);

        foreach($entities as $entity) :
            foreach($entity->tables as $table_name=>$title) :
                if($table==$table_name) :
                    return $title;
                endif;
            endforeach;
        endforeach;
    }

    public function TableEntities()
    {
        $config_liste = new \Custom\Config\Liste();
        $liste = database_decode($config_liste->config);

        $entities = (object) [];
        foreach($liste as $key=>$entity) :
            $entities->$key = (object) [];
            $entities->$key->label = $entity->label;
            if(isset($entity->tables)) :
                $tables = (object) [];
                foreach($entity->tables as $table=>$title) :
                    if($this->db->tableExists($table)) $tables->$table = $title;
                endforeach;
                $entities->$key->tables = $tables;
            endif;
        endforeach;

        return $entities;
    }
}
