<?php namespace DataQuery\Libraries;


use DataQuery\Models\DataQueryModel;
use Config\Fields; 

class DataQueryConstructor
{
    protected $dataQueryModel;
    protected $entities;
    protected $path;
    protected $descriptor;
    protected $descriptorIndexByFields;

    public function __construct()
    {
        $this->dataQueryModel = new DataQueryModel();
        $this->entities=$this->dataQueryModel->getEntities();
        $this->descriptor=$this->dataQueryModel->getDescriptor();
        $this->descriptorIndexByFields=$this->dataQueryModel->getDescriptorIndexByFields();
        //$this->path="App\Modules\DataQuery\Views\/";
        $this->path="DataQuery\Views\/";

    }


    public function list_entity($i,$entity_select=NULL)
    {
        $entities=[];
        $descriptor=$this->descriptor;
        foreach ($this->entities as $entity)
        {
            if(isset($descriptor[$entity->ref]))
            {
                $entities[$entity->ref]=$entity->label;
            }
        }

        return view($this->path."r_list_entity",[
            "entities"=>$entities,
            "entity_select"=>$entity_select,
            "path"=>$this->path,
            "i"=>$i
        ]);
	
    }

    public function get_list_select_field($entity,$i=NULL,$field_select=NULL)
    {
        $fields=array();
        if($entity=="personne")
        {
            $entity="contact";
        }
        $descriptor=$this->descriptor[$entity];
        foreach($descriptor as $index=>$field):
            $fields[$index]=$field["label"];
        endforeach;   
        $data["fields"]=$fields;
        $data["entity"]=$entity;
        $data["field_select"]=$field_select;
        return view($this->path."r_list_fields",[
            "fields"=>$fields,
            "entity"=>$entity,
            "field_select"=>$field_select,
            "i"=>$i
        ]);
	
    }

    public function get_operateur($champ,$i=NULL,$operator_select=NULL)
    {
       
        $descriptor=$this->descriptorIndexByFields;
        $type_input=$descriptor[$champ]["type_field"];
        $data["type_input"]=$type_input;
        $data["operator_select"]=$operator_select;
        $data["i"]=$i;
        return view($this->path."r_list_operator",$data);
    }
    
    public function get_input($champ,$i=NULL,$value=NULL)
    {
        
        $descriptor=$this->descriptorIndexByFields;
        $type_input=$descriptor[$champ]["type_field"];
        $name=$descriptor[$champ]["field_sql"];
        $data["name"]=$name;
        $data["value"]=$value;
        $data["i"]=$i;

        switch ($type_input)
        {
            case "date_normal":
            case "date_with_h":
            case "date":
            case 'birthday':
            case "datepicker":
                return view($this->path."r_input_date",$data);
                    
            case "select_label":
            case "select":
            case "check":    
            case "radio":
                $select_table=$descriptor[$champ]["table_list"];
                $id_select=$descriptor[$champ]["key_list"];
                $label_select=$descriptor[$champ]["label_list"];
                if(isset($descriptor[$champ]["order_list"])): $order_select=$descriptor[$champ]["order_list"]; else: $order_select=NULL; endif;
                if(isset($descriptor[$champ]["select_where"])): $select_where=$descriptor[$champ]["select_where"]; else: $select_where=NULL; endif;
                if(isset($descriptor[$champ]["type_select_multiple"])): $data["is_multiple"]=$descriptor[$champ]["type_select_multiple"]; else: $data["is_multiple"]=FALSE; endif;
                
                $fields="$id_select as id,";
                if(is_array($label_select)):
                    $labels=NULL;
                    $ij=0;
                    foreach($label_select as $f):
                            if($ij>0):
                            $labels.=",' ',";
                            endif;
                            $labels.=$f;
                            $ij=$ij+1;
                    
                    endforeach;
                    //$labels=implode(", ",$label_select);
                    $fields.="CONCAT($labels) as label";
                else:
                    $fields.=$label_select.' as label ';
                endif;
                
                $data["options"]=$this->dataQueryModel->getList($select_table,$fields,$select_where,$order_select);

                return view($this->path."r_input_select",$data);
            default:
                return view($this->path."r_input_text",$data);

        }
    }


    public function get_error($getVar=NULL)
    {
        /* --- Input possible -----
            /*  ou_et_##(number)
            /*  par_ouvert_##(number)
            /*  entity_##(number)
            /* champ_##(number)
            /*  operateur_##(number)
            /* ##(number)##_value
            /*  par_ferme_##(number)
            /*  number //number of line of condition
            /* fields_select
        */
        //$findError=FALSE;

        /* Not input */

      


        if(is_null($getVar))
        {
            return 1;
        }

        /* FIRST: Verify if condition is OK */
        $number=$getVar["number"];
        $messages_error=[];
        $nb_bracket_open=0;
        $nb_bracket_close=0;
        
        //Verif if a condition exist


        for($i=1;$i<=$number;$i++)
        {
            
            if(!isset($getVar["champ_##$i"]))
            {
                array_push($messages_error,$this->error_message(2,$i));
            }

            if(isset($getVar["champ_##$i"])&&!isset($getVar["##$i##_value"]))
            {

                if($getVar["operateur_##$i"]!="vide"&&$getVar["operateur_##$i"]!="vide_pas")
                {
                    array_push($messages_error,$this->error_message(3,$i));
                }
                
            }

            if(isset($getVar["##$i##_value"])&&empty($getVar["##$i##_value"]))
            {
                if($getVar["operateur_##$i"]!="vide"&&$getVar["operateur_##$i"]!="vide_pas")
                {
                    array_push($messages_error,$this->error_message(4,$i));
                }
            }

            if($getVar["par_ouvert_##$i"])
            {
                $nb_bracket_open=$nb_bracket_open+1;
            }

            if($getVar["par_ferme_##$i"])
            {
                $nb_bracket_close=$nb_bracket_close+1;
            }

        }

        if($nb_bracket_open!=$nb_bracket_close)
        {
            array_push($messages_error,$this->error_message(5));
        }

        /* SECOND: Verify if a field selected */
        if(!isset($getVar["fields_select"]))
        {
            array_push($messages_error,$this->error_message(9));
        }

       // debug($messages_error);
       // debugd($getVar);

        return $messages_error;

    }


    public function error_message($type_error,$line=NULL)
    {
        switch($type_error)
        {
            case 1: return "Vous devez d'abord créer une requête!";
            case 2: return "Vous devez choisir une entité pour la condition <b>#$line</b>!";
            case 3: return "Vous devez choisir un champ pour la condition <b>#$line</b>!";
            case 4: return "Vous devez entrer ou choisir une valeur pour la condition <b>#$line</b>!";
            case 5: return "Le nombre de parenthèses ouvrantes et fermantes ne correspondent pas!";
            case 9: return "Vous devez sélectionner au moins un champ à afficher dans le sélecteur de champs en dessous du cadre des conditions!";
            

        }
    }

   

   


} 