<?php namespace DataView\Libraries;

use Registration\Models\RegistrationModel;
use App\Models\ActivitiesModel;
use Contact\Models\ContactModel;

use DataView\Models\DataViewConstructorModel;
use Config\Fields;
use Tesorus\Libraries\TesorusLibrary;

class DataViewConstructor
{
    protected $path="DataView\Views\/";
    protected $dataViewConstructorModel;
    protected $fields;

    public function __construct()
    {
        $this->dataViewConstructorModel = new DataViewConstructorModel();
        $this->fields=$this->dataViewConstructorModel->getFields();
    }

    public function setQuerySearch($q, $fieldsSearch)
    {
        return $this->dataViewConstructorModel->setQuerySearch($q, $fieldsSearch);
    }

    public function getValueReadBoolean($value=null)
    {
        $html = '';
        if(isset($value)) :
            if(in_array(strval($value), ['Oui', 1])) :
                $html .= '<div class="text-success">' . fontawesome('check') . '</div>';
            elseif(in_array(strval($value), ['Non', 0])) :
                $html .= '<div class="text-dark">' . fontawesome('times') . '</div>';
            endif;
        endif;

        return $html;
    }
    
    public function SetOrderTh($columns=null, $request=null)
    {
        $order = $this->GetOrderDefault($columns);
        $order = $this->GetOrderFromRequest($order, $request);

        return view('DataView\table/table-head-th', [  
            "columns" => $columns,
            "order" => $order,
        ]);
    }

    public function getListAddField($type=NULL,$entity=NULL)
    {
       // echo "<h1>$type</h1>";
        if(!is_null($type))
        {
            $fieldsAll=$this->dataViewConstructorModel->getFieldsGestion($type);
            $fieldsSelected=$this->dataViewConstructorModel->getfieldsSelected($type,$entity);

           
         return view("DataView\Views/get-fieldsGestion",[
                "fieldsAll"=>$fieldsAll,
                "fieldsSelected"=>$fieldsSelected
            ]);
        }
        else
        {
            return false;
        }
    }

    

    public function GetOrderFromRequest($order=null, $request=null)
    {
        $orderBy = (!empty($order) && !empty($order[0])) ? $order[0] : null;
        $orderDirection = (!empty($order) && !empty($order[1])) ? $order[1] : null;
        if(!empty($request) && !empty($request->getGet('orderBy'))) :
            $orderBy = $request->getGet('orderBy');
            $orderDirection = $request->getGet('orderDirection') ? $request->getGet('orderDirection') : 'asc';
        endif;

        return [$orderBy, $orderDirection];
    }

    public function GetOrderDefault($columns)
    {
        $orderBy = null;
        $orderDirection = null;
        foreach($columns as $key=>$column) :
            if(!empty($column[2])) :
                $orderBy = $key;
                $orderDirection = $column[2];
                break;
            endif;
        endforeach;

        return [$orderBy, $orderDirection];
    }

    public function getFields()
    {
        return $this->dataViewConstructorModel->getFields();
    }

    public function getComponents($name)
    {
        return $this->dataViewConstructorModel->getComponents($name);
    }

    public function getOneComponent($name,$type)
    {
        return $this->dataViewConstructorModel->getOneComponent($name,$type);
    }

    public function setComponents($name,$posts)
    {
      
        return $this->dataViewConstructorModel->setComponents($name,$posts);
    }


    public function getValueIndex($index,$id_entity)
    {
        return $this->dataViewConstructorModel->getValueIndex($index,$id_entity);
    }

    public function getListAddFieldInjectedForm($id_injected_form=0)
    {
       
            $fieldsAll=$this->dataViewConstructorModel->getFieldsInjedtedFormDefault();
          
            $fieldsSelected=$this->dataViewConstructorModel->getFieldsInjedtedFormSelected($id_injected_form);
            return view("DataView\Views/get-fieldsGestion",[
                "fieldsAll"=>$fieldsAll,
                "fieldsSelected"=>$fieldsSelected,
                "type_clone"=>"injected_form"
            ]);
       
    }

    public function getValueIndexRead($index,$id_entity)
    {
        $value=$this->dataViewConstructorModel->getValueIndex($index,$id_entity);
        return $value;
        $fields=$this->fields[$index];
        return $this->getValueRead($index,$fields,$value);
       

    }

    public function getValueIndexBrut($index,$id_entity)
    {

        return $this->dataViewConstructorModel->getValueIndex($index,$id_entity);
    }

    public function getIndexLabel($index,$id_entity)
    {
        
        if(isset($this->fields[$index]))
        {
            $fields=$this->fields[$index];
            return $fields["label"];
        }
        
        return NULL;

    }

     //element pour formulaire
     public function getElementFormByIndex($index,$entity,$value=NULL,$is_ligne=FALSE)
     {
        if(isset($this->fields[$index]))
        {
           
            $field=$this->fields[$index];
            $elementForm=NULL;

            $elementForm.='<div index="'.$index.'" class="mb-2 form-group tr_fiche_'.$index.' tr_fiche">';
            $elementForm.="<div class='formcourscopy'>";
            $elementForm.="<label style='font-weight:bold' class='control-label'>";
            if($field["rule"]=="trim|required"): $elementForm.="*";endif;
        
            $elementForm.=$field["label"]."</label>";
            $elementForm.=$this->getElementForm($index,$field,$value,NULL,$is_ligne,$is_deselect=FALSE);
            $elementForm.='</div>';
            $elementForm.='</div>';

            return $elementForm;
        }

        return "Pas d'index $index correspondant";
        
     }

     public function getElementFormByIndexNoLabelAjax($index,$entity,$value=NULL,$is_ligne=FALSE)
     {
        if(isset($this->fields[$index]))
        {
            $field=$this->fields[$index];
            $elementForm=NULL;

            $elementForm.=$this->getElementForm($index,$field,$value,NULL,$is_ligne,$is_deselect=FALSE,$is_ajax=TRUE);
            

            return $elementForm;
        }

        return "Pas d'index $index correspondant";
        
     }

    //Element pour formulaire
    public function getElementForm($index,$fields=array(),$value=NULL,$num_container=NULL,$is_ligne=FALSE,$is_dselect=TRUE,$is_ajax=FALSE,$is_multiple=FALSE,$numero_tour)
    {
       if($is_multiple){$numero_multiple="[$numero_tour]";}else{$numero_multiple=NULL;}
        $label=$fields["label"]." …";
       
        if(!isset($fields["multiple"]))
        {
            $multiple=false;
        }
        else
        {
            $multiple=$fields["multiple"];
        }

        //if(session()->has($index)): $value="toto"; endif;
     
        switch($this->getType($fields)){
            case "no_modif_date":
                return view('DataView\form-no-modif-date', 
                [   "value"=>$value,
                    "index"=>trim($index),
                    "label"=>$label,
                    "numero_multiple"=>$numero_multiple
                  
                ]);

                case "no_modif":
                    return view('DataView\form-no-modif', 
                    [   "value"=>$value,
                        "index"=>trim($index),
                        "label"=>$label,
                        "numero_multiple"=>$numero_multiple

                      
                    ]);

           case "textarea":     return view('DataView\form-textarea', 
                                            [   "value"=>$value,
                                                "index"=>trim($index),
                                                "label"=>$label,
                                                "num_container"=>$num_container,
                                                "numero_multiple"=>$numero_multiple

                                            ]);

           case "select":      return view('DataView\form-selection', 
                                            [   "value"=>$value,
                                                "index"=>trim($index),
                                                "selects"=>$this->dataViewConstructorModel->getSelect($fields,$value),
                                                "num_container"=>$num_container,
                                                "is_dselect"=>$is_dselect,
                                                "multiple"=>$multiple,
                                                'is_ajax'=>$is_ajax,
                                                "autorisation"=>$fields["autorisation"],
                                                "numero_multiple"=>$numero_multiple

                                            ]);  

            case "radio":      return view('DataView\form-radio', 
                                            [   "value"=>$value,
                                                "index"=>trim($index),
                                                "checks"=>$this->dataViewConstructorModel->getCheck($fields,$value),
                                                "num_container"=>$num_container,
                                                "is_ligne"=>$is_ligne,
                                                "numero_multiple"=>$numero_multiple

                                            ]);                                 

            case "check":      return view('DataView\form-check', 
                                            [   "value"=>$value,
                                                "index"=>trim($index),
                                                "checks"=>$this->dataViewConstructorModel->getCheck($fields,$value),
                                                "num_container"=>$num_container,
                                                "is_ligne"=>$is_ligne,
                                                "numero_multiple"=>$numero_multiple

                                            ]); 
            case "boolean": return view('DataView\form-boolean', [
                "value"=>$value,
                "index"=>$index,
            ]); 

            case "birthday":                                
            case "date":        return view('DataView\form-datepicker', 
                                            [   "value"=>$value,
                                                "index"=>trim($index),
                                                "label"=>$label,
                                                "num_container"=>$num_container,
                                                "numero_multiple"=>$numero_multiple

                                            ]); 
                                       
            case "date_with_heure":        return view('DataView\form-date-with-heure', 
                                                [   "value"=>$value,
                                                    "index"=>trim($index),
                                                    "label"=>$label,
                                                    "num_container"=>$num_container,
                                                    "numero_multiple"=>$numero_multiple

                                                ]); 
            case "calcul":        return 'Champ calculé - non modifiable'; 
            case "files" :
                // $FileLibrary = new \Components\Libraries\FileLibrary();
                // $data->files = $FileLibrary->FilesGet($value);
                // return view('DataView\form/form-files', (array) $data);
            break;
            case "image" :
                // $FileLibrary = new \Components\Libraries\FileLibrary();
                // $data->file = $FileLibrary->FileGet($value);
                // return view('DataView\form/form-image', (array) $data);
            break;
            case "tesorus-tag-checkbox" :
            case "tesorus-tag-radio" :
                $TesorusLibrary = new TesorusLibrary();
                $road_name = $fields['table_list'];
                $value = json_decode($value);
                return $TesorusLibrary->get_road_tag_html($road_name, $index, $value);
            break;  
            default:             return view('DataView\form-input-text', 
                                            [   "value"=>$value,
                                                "index"=>trim($index),
                                                "label"=>$label,
                                                "num_container"=>$num_container,
                                                "numero_multiple"=>$numero_multiple

                                            ]);
        } 
    }

    public function getValueReadWithLabel($index,$value)
    {
        //je dois récupérer le descriptor et récupéer dans le descriptor, son array de description
        // et je retroune un array avec le label, la value mise en forme
        $fields=$this->fields[$index];

        return [
            "label"=>$fields["label"],
            "value"=>$this->getValueRead($index,$fields,$value),
        ];
    }

    public function getValueRead($index,$fields=array(),$value=NULL)
    {
        // debug($fields);
        $label=$fields["label"]." …";

        switch($this->getType($fields))
        {
            case "no_modif":
                return $value;

            case "no_modif_date":
                return convert_date_en_to_fr_with_h($value,false);

            case "textarea" :
                if($fields["function"]=="string_to_list") return string_to_list($value," - ");
                return nl2br($value);

            case "select" : return $this->dataViewConstructorModel->getSelectValue($fields,$value);

            case "boolean" :
                if($value==0) return 'Non';
                elseif($value==1) return 'Oui';
                
            case "radio" :
            case "check": return $this->dataViewConstructorModel->getCheckValue($fields,$value);
                
            case "tesorus-tag-checkbox" :
                $TesorusLibrary = new TesorusLibrary();
                $road_name = $fields['table_list'];
                $value = json_decode($value);
                return $TesorusLibrary->TagsValueGet($road_name, $value);
            case "tesorus-tag-radio" :
                $TesorusLibrary = new TesorusLibrary();
                $road_name = $fields['table_list'];
                return $TesorusLibrary->TagsValueGet($road_name, [$value]);

            case "birthday":
                $valueData = convert_date_en_to_fr($value);
                if(!is_null($valueData)) $valueData .= ' ('.calcul_age($value).') ';
                return $valueData;
                                
            case "date" : return convert_date_en_to_fr_with_h($value,false); 

            case "price" : return $value . " €";

            case "calcul" : 
                if(empty($fields["function"])) return null;

                $function = $fields["function"];
                $params = explode(',', $value);

                if(count($params)==1) return $function($value);
                elseif(count($params)>1) return $function($params);
                else return $function();

            case "files" : 
                $FileLibrary = new \Components\Libraries\FileLibrary();
                $ids_file = is_array($value) ? $value : explode(',', str_replace(['[', ']'], '', $value));
                $files = $FileLibrary->FilesGet($ids_file);
                $html = '';
                if(empty($files)) return $html;

                foreach($files as $file) :
                    $html .= '<div class="row">';
                    $html .= view('Components\file/read-file', ['file' => $file, 'index' => $index]);
                    $html .= '</div>';
                endforeach;
                return $html;

            case "image" : 
                if(empty($value)) return '';

                $FileLibrary = new \Components\Libraries\FileLibrary();
                $file = $FileLibrary->FileGet($value);
                return view('Components\file/read-image', ['file' => $file, 'index' => $index]) ;
    
            default : return $value;
        } 
    }

  

    //get the type from descriptor
    public function getType($fields)
    {
        if(isset($fields["type_field"]))
            return $fields["type_field"];
        return "input-text";    
    }

    public function getFiedlSql($index,$affiche_field_sql=TRUE)
    {
        if(isset($this->fields[$index]["field_sql"])&&$affiche_field_sql)
        {
            return $this->fields[$index]["field_sql"];
        }
        else
        {
            return trim($index);
        }
    }
   
   

    public function getRules($indexes,$fields)
    {
       
        $rules=[];
        if(!empty($index))
        {
            foreach($indexes as $index)
            {
                if(isset($fields[$index]["rule"]))
                {
                    $rules[$index]=$fields[$index]["rule"];
                }
            
            }
        }
        return $rules;
    }

    public function isRequired($field)
    {
        if(!isset($field["rule"])){ return false; }
        if (strpos($field["rule"], "required") !== FALSE) 
        {
              return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function saveData($indexes,$values,$fields,$table,$id_entity=0,$id_name_entity)
    {

        //debug($id_entity);
       // debug($table);
       //debug($fields);
        //debug($values);
       // debug($indexes,true);
       //$where[$fields["key_entity"][$table]]
        //$datasForSave=$this->prepareData($indexes,$values,$fields,$table);

        

        //Cas des inscriptions en cours
      /*  if(isset($values["id_activite_registration"])&&$values["id_activite_registration"]>0)
        {
            $datasForSave["inscriptions"][]=["id_activite_registration"=>$values["id_activite_registration"]];
        }

        if(isset($values["id_contact_registration"])&&$values["id_contact_registration"]>0)
        {
            $datasForSave["inscriptions"][]=["id_contact_registration"=>$values["id_contact_registration"]];

        }

        if(isset($values["id_contact"])&&$values["id_contact"]>0)
        {
            $datasForSave["inscriptions"][]=["id_contact_registration"=>$values["id_contact"]];

        }

        if(isset($values["id_activity"])&&$values["id_activity"]>0)
        {
            $datasForSave["inscriptions"][]=["id_activite_registration"=>$values["id_activity"]];
        }*/

        //si id_usr defini, on sait que l'on est dans un contexte user, si on a id_user >0 et id_contact=0, on va devoir rajouter le contact
        // au user; si on a id_user>0 et $id_contact>0, on ne fait rien, l'user gérer déjà le contact.
       /* if(isset($values["id_user"])&&$values["id_user"]>0)
        {
            $datasForSave["user_contacts"][]=["id_user_registration"=>$values["id_user"]];
        }

        if(isset($values["id_contact"]))
        {
            $datasForSave["user_contacts"][]=["id_contact_registration"=>$values["id_contact"]];
        }

        if(isset($values["id_contact"])&&$values["id_contact"]>0)
        {
            $datasForSave["inscriptions"][]=["id_contact_registration"=>$values["id_contact"]];
        }*/

        //If id_user=0000, on doit créer un nouvel user et envoyer mail de connexion!
      // debug($datasForSave,true);
        $id_entity_save=$this->dataViewConstructorModel->saveData($indexes,$table,$values,$fields,$id_entity,$id_name_entity);
        return $id_entity_save;    
    }

    public function prepareData($indexes,$values,$fields,$table,$is_mis_form=FALSE,$affiche_field_sql=TRUE)
    {
       
      
        $indexes=$this->get_index_for_component($indexes,$table);
      
            $dataForSave=$this->tablesPossible($indexes,$fields,$table);


            foreach($indexes as $index)
            {
                $data=[];
                if(isset($fields[$index]))
                {
                   
                    $type=$this->getType($fields[$index]);
                    $tableOfIndex=$this->getTable($fields[$index],$table);
                    switch($type)
                    {
                        case "birthday":    
                        case "date":  $data[$this->getFiedlSql($index,$affiche_field_sql)]= convert_date_fr_to_en($values[$index]); 
                                           //debugd($values[$index]);
                                            if(!$this->dataViewConstructorModel->nullable($index,$table)&&is_null($data[$this->getFiedlSql($index,$affiche_field_sql)]))
                                            {
                                                $data[$this->getFiedlSql($index,$affiche_field_sql)]="0000-00-00";
                                            }
                                            break;

                        case "radio"    :                       
                        case "check"    :   

                                               // debug($values);
                                                //debug($data);
                                            if(!isset($values[$index]))
                                            {
                                                $data[$this->getFiedlSql($index,$affiche_field_sql)]=NULL;
                                                if(!$this->dataViewConstructorModel->nullable($index,$table)&&(empty($data[$index])))
                                                {
                                                    $data[$this->getFiedlSql($index,$affiche_field_sql)]="";
                                                }
                                                
                                            }
                                            else
                                            {
                                                if(is_array($values[$index]))
                                                {
                                                    $data[$this->getFiedlSql($index,$affiche_field_sql)]= implode(",",$values[$index]);
                                                }
                                                else
                                                {
                                                    $data[$this->getFiedlSql($index,$affiche_field_sql)]= $values[$index];
                                                }
                                                    
                                            }
                                            break;

                        default:            
                        //if(!empty($values[$index]))
                        
                        if(isset($values[$index]))
                        {
                            if(is_array($values[$index])) $values[$index] = json_encode($values[$index]);
                            $data[$this->getFiedlSql($index,$affiche_field_sql)]=trim($values[$index]);
                        }
                    }
               
                    array_push($dataForSave[$tableOfIndex],$data);
                }
            }
            
            
            if(!$is_mis_form)
                return $dataForSave;


           // debug($dataForSave);

            $dataMisEnForm=NULL;

            foreach($dataForSave[$tableOfIndex] as $key=>$value)
            {
             //debug($value);
                foreach($value as $k=>$v)
                {
                    
                    $dataMisEnForm[$k]=$v;
                }
                
            }

           
            return $dataMisEnForm;
    }

    public function prepareData_with_indexes($indexes,$values,$fields,$table,$is_mis_form=FALSE)
    {
       
      
        $indexes=$this->get_index_for_component($indexes,$table);

            $dataForSave=$this->tablesPossible($indexes,$fields,$table);
            foreach($indexes as $index)
            {
                $data=[];
                if(isset($fields[$index]))
                {
                   
                    $type=$this->getType($fields[$index]);
                    $tableOfIndex=$this->getTable($fields[$index],$table);
                    switch($type)
                    {
                        case "birthday":    
                        case "date":  $data[$this->getFiedlSql($index)]= convert_date_fr_to_en($values[$index]); 
                                           
                                            if(!$this->dataViewConstructorModel->nullable($index,$table)&&is_null($data[$this->getFiedlSql($index)]))
                                            {
                                                $data[$this->getFiedlSql($index)]="0000-00-00";
                                            }
                                            break;
                        case "radio"    :                       
                        case "check"    :   

                                               // debug($values);
                                                //debug($data);
                                            if(!isset($values[$index]))
                                            {
                                                $data[$this->getFiedlSql($index)]=NULL;
                                                if(!$this->dataViewConstructorModel->nullable($index,$table)&&(empty($data[$index])))
                                                {
                                                    $data[$this->getFiedlSql($index)]="";
                                                }
                                                
                                            }
                                            else
                                            {
                                                if(is_array($values[$index]))
                                                {
                                                    $data[$this->getFiedlSql($index)]= implode(",",$values[$index]);
                                                }
                                                else
                                                {
                                                    $data[$this->getFiedlSql($index)]= $values[$index];
                                                }
                                                    
                                            }
                                            break;

                        default:            
                        //if(!empty($values[$index]))
                        
                        if(isset($values[$index]))
                        {
                            $data[$this->getFiedlSql($index)]=trim($values[$index]);

                        }
                    }
               
                    array_push($dataForSave[$tableOfIndex],$data);
                }
            }
            
            
            if(!$is_mis_form)
                return $dataForSave;


           // debug($dataForSave);

            $dataMisEnForm=NULL;

            foreach($dataForSave[$tableOfIndex] as $key=>$value)
            {
             //debug($value);
                foreach($value as $k=>$v)
                {
                    
                    $dataMisEnForm[$k]=$v;
                }
                
            }

           
            return $dataMisEnForm;
    }

    public function get_index_for_component($indexes_form,$table)
    {
        $index_component=[];

     //debugd($this->fields);

        foreach($indexes_form as $index)
        {
            $index=trim($index);

           if($this->fields[$index]["table"]==$table)
           {
                array_push($index_component,$index);
           }
        }

        return $index_component;
    }


    public function prepareDataOld($indexes,$values,$fields,$table)
    {


            $dataForSave=$this->tablesPossible($indexes,$fields,$table);
            foreach($indexes as $index)
            {
                $data=[];
                if(isset($fields[$index]))
                {
                   
                    $type=$this->getType($fields[$index]);
                    $tableOfIndex=$this->getTable($fields[$index],$table);
                    switch($type)
                    {
                        case "birthday":    
                        case "date":  $data[$this->getFiedlSql($index)]= convert_date_fr_to_en($values[$index]); 
                                           
                                            if(!$this->dataViewConstructorModel->nullable($index,$table)&&is_null($data[$this->getFiedlSql($index)]))
                                            {
                                                $data[$this->getFiedlSql($index)]="0000-00-00";
                                            }
                                            break;
                        case "radio"    :                       
                        case "check"    :   

                                               // debug($values);
                                                //debug($data);
                                            if(!isset($values[$index]))
                                            {
                                                $data[$this->getFiedlSql($index)]=NULL;
                                                if(!$this->dataViewConstructorModel->nullable($index,$table)&&(empty($data[$index])))
                                                {
                                                    $data[$this->getFiedlSql($index)]="";
                                                }
                                                
                                            }
                                            else
                                            {
                                                if(is_array($values[$index]))
                                                {
                                                    $data[$this->getFiedlSql($index)]= implode(",",$values[$index]);
                                                }
                                                else
                                                {
                                                    $data[$this->getFiedlSql($index)]= $values[$index];
                                                }
                                                    
                                            }
                                            break;

                        default:            
                        if(!empty($values[$index]))
                            $data[$this->getFiedlSql($index)]=trim($values[$index]);
                    }
               
                    array_push($dataForSave[$tableOfIndex],$data);
                }
            }
            
            return $dataForSave;
    }

    public function tablesPossible($indexes,$fields,$table)
    {
        $tablesPossible=NULL;
        foreach($indexes as $index)
        {
            if(isset($fields[$index]))
            {
                $tablesPossible[$this->getTable($fields[$index],$table)]=[];
            }
        
        }
        return $tablesPossible;
    }


    private function getTable($field,$table)
    {
        //If field is table of entity or table other of entity
        if(isset($field["table"])&&$field["table"]!=$table)
            {
                return $field["table"];
            }
            return  $table;
    }


    public function verifExistFields($fields,$table)
    {
        //Create fields if fields dont'exist in table sql
        foreach($fields as $index=>$field){
            $this->dataViewConstructorModel->verifExistFields($index,$field,$table);
        }   

        return TRUE;
       
    }

    public function nullable($index,$table)
    {
        return $this->dataViewConstructorModel->nullable($field,$table);
    }


 

    


} 