<?php 

namespace Dashboard\Libraries;

use Dashboard\Models\Mdashboard;
use DataView\Models\DataViewConstructorModel;

class Ldashboard {
    

     public function __construct()
    {
        $this->dataViewConstructorModel = new DataViewConstructorModel();

        $this->mdashboard=new Mdashboard();

        $this->viewpath =  "Dashboard\Views\/"; 
       
    }
    
    public function change_sql($id)
    {
        $table="user_requete";
        $champs="user_requete.id_requete";
        $left=array();
        $left_condition["user_table"]="user_table.id_requete=user_requete.id_requete";
        array_push($left,$left_condition);
        
        $where="user_table.query_orig NOT LIKE user_requete.query AND id_user_table=$id";
        
        $data=$this->mdashboard->read_data($table,$champs,$where,$left);
        
        
    
           if(isset($data[0]->id_requete)): return TRUE; else: return FALSE; endif;
        
        return TRUE;
    }
    
    
    public function entity_possible($id_user_table)
    {
        //demande=1, rdv=2, tache=3
        //demande_seul=100
        //rdv seul=010
        //tache seul=001
        //demande+rdv=110
        //demande+tache=101
        //demande+rdv+tache=111
        
        
        $entities="demande,demande_detail,rdv,tache";
        $code=NULL;
        $data=$this->mdashboard->read_data("user_table","query","id_user_table=$id_user_table");
        if(isset($data[0]->query)):
            $query=$data[0]->query;
            if (strpos($query, "demande") !== FALSE):
                    $code.="1";
            else:
                    $code.="0";
            endif;
            if (strpos($query, "rdv") !== FALSE):
                    $code.=$code."1";
            else:
                    $code.=$code."0";
            endif;
            if (strpos($query, "tache") !== FALSE):
                    $code.=$code."1";
            else:
                    $code.=$code."0";
            endif;
             
            
        endif;
        
        return $code;
        
    }
    
     public function get_interface($id_user,$is_ajax=FALSE,$id_onglet=NULL)
   {
	$tables=array();
	$table_sql="user_table";
	if(is_null($id_onglet)): $id_onglet=$this->id_first_onglet($id_user); endif;
	$where="id_user=$id_user AND id_user_table_onglet=$id_onglet";
	$order="rank,date_create DESC";
	$data["id_user"]=$id_user;
	$data["id_onglet"]=$id_onglet;
	$tables_sql=$this->mdashboard->read_data($table_sql,"*",$where,NULL,$order);
	if(is_null($id_onglet)):
	    return view($this->viewpath."dash_tables_vide_onglet",$data);
	endif;
	
	if(!isset($tables_sql[0]->id_user_table)):
	    return view($this->viewpath."dash_tables_vide",$data);
	endif;
	$i=0;
       
	foreach($tables_sql as $t):
	    array_push($tables,$this->get_table($t->id_user_table,$t->query,$t->field,$i,$t->nom,$t->size,$t->rank,$t,$is_ajax));
	    $i=$i+1;
	endforeach;
	$data["tables"]=$tables;
	$data["i"]=$i;
	return view($this->viewpath."dash_tables",$data);
   }
    
   

   
    public function get_interface_ajax_data_one($id,$rank=0,$postData=NULL)
   {
	
	$table=NULL;
	$table_sql="user_table";
	$where="id_user_table=$id";
	$tables_sql=$this->mdashboard->read_data($table_sql,"*",$where,NULL);
	//print_r($tables_sql);
	
	foreach($tables_sql as $t):
           
	   $records= $this->get_data_table($t->id_user_table,$t->query,$t->field,$id."reload",$t->nom,$t->size,$t->rank,$t,FALSE,$rank,$postData);
	   $fields=explode(",",$t->field);
	endforeach;
	
	 $data = array();

     foreach($records["results"] as $record ){
	 foreach($fields as $fie):
	     $dp[$fie]=  mb_convert_encoding($this->link(strip_tags(tronque_text($record->$fie,50)),$fie),"UTF-8");
	    
	 endforeach;
	  array_push($data,$dp);

     }
     $draw=$postData["draw"];
     $totalRecords=$records["total"];
     $totalRecordwithFilter=$records["total"];
     ## Response
     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
     );

     return $response; 
   }
   
   
   
    public function get_interface_one_ajax($id,$rank)
   {
	
	$table=NULL;
	$table_sql="user_table";
	$where="id_user_table=$id";
	$tables_sql=$this->mdashboard->read_data($table_sql,"*",$where,NULL);
	

	foreach($tables_sql as $t):
           // print_r($t);
	   //$table= $this->get_table_ajax($t->id_user_table,$t->query,$t->field,$id."reload",$t->nom,$t->size,$t->rank,$t,FALSE,$rank);
           $id= $t->id_user_table;
           $query=$t->query;
           $options=$t;
           $i=$id."reload";
           $nom_table=$t->nom;
           $size=$t->size;
           $rank=$t->rank;
        
        
        //get_table_ajax($id,$query,$labels,$i,$nom_table,$size,$rank,$options,$is_ajax=FALSE,$data_rank=NULL,$postData=NULL);
        
        $data["datas"]=$this->get_table_data($query,$options);

        
	$champs_index=explode(",",$options->field);
        $datas_treat=array();
        if(!empty($champs_index[0])):
    
            foreach($data["datas"] as $data):	    
                foreach($champs_index as $c_index):
                   $da[$c_index]= $this->link(strip_tags($data->$c_index),$c_index);
                endforeach;
                 array_push($datas_treat,$da);           
            endforeach;
       endif;
	echo '<pre>';
       //print_r($datas_treat);
       echo '</pre>';
       
       
	  
	
	return $datas_treat;
	endforeach;
	return $table;
   }
    
   
   
   public function get_interface_one($id,$rank)
   {
	
	$table=NULL;
	$table_sql="user_table";
	$where="id_user_table=$id";
	$tables_sql=$this->mdashboard->read_data($table_sql,"*",$where,NULL);
	
	//debugd($tables_sql);
	foreach($tables_sql as $t):
           // print_r($t);
	   $table= $this->get_table($t->id_user_table,$t->query,$t->field,$id."reload",$t->nom,$t->size,$t->rank,$t,FALSE,$rank);
	  
	endforeach;
	return $table["data_table"];
   }
    
   
   
   
   
    public function get_data_table($id,$query,$labels,$i,$nom_table,$size,$rank,$options,$is_ajax=FALSE,$data_rank=NULL,$postData)
    {
	
	   return $this->get_table_data($query,$options,0,$postData);
	
	
	
    }
    
     public function get_table($id,$query,$labels,$i,$nom_table,$size,$rank,$options,$is_ajax=FALSE,$data_rank=NULL,$postData=NULL)
    {
	 
            //print_r($query);
            
            $data["labels"]=$this->get_table_label($labels);
            if(!$is_ajax&&!IS_AJAX_DATATABLE):
                $data["datas"]=$this->get_table_data($query,$options);
            endif;
            
            $data["i"]=$i;
            $data["nom_table"]=$nom_table;
            $data["id"]=$id;
            $data["size"]=$size;
            $data["rank"]=$rank;
            $data["options"]=$options;
            $data["data_rank"]=$data_rank;
                
            
            if($is_ajax):
                $result["data_table"]=view($this->viewpath."dash_table_one_ajax",$data);
            else:
                $result["data_table"]=view($this->viewpath."dash_table_one",$data);
            endif;
            $result["size"]=$size;
            
            return $result;
    }
    
    public function get_table_ajax($id,$query,$labels,$i,$nom_table,$size,$rank,$options,$is_ajax=FALSE,$data_rank=NULL,$postData=NULL)
    {
	 
	//print_r($query);

       
    }
    
    public function get_table_data_ajax($id,$query,$labels,$i,$nom_table,$size,$rank,$options,$is_ajax=FALSE,$data_rank=NULL,$postData=NULL)
    {
	 
	//print_r($query);
	 
	$data["datas"]=$this->get_table_data($query,$options);
	$datas_array=array();
            foreach($data["datas"] as $dd):
                foreach($dd as $kdd=>$vdd):
                    $data_data[$kdd]=$vdd;
                endforeach;
                array_push($datas_array,$data_data);
                $data_data=array();
            endforeach;
         
       
	
	return $datas_array;
    }
    
    
    public function create_where_filtre($query,$options)
    {
	
        //recupérer le group_by eventuel
       
        //print_r($group_bys);
        
	//YEAR(rdv.date_rdv_debut)=2019
	//YEAR(demande.date)=2019
	//print_r($options); die();
	$new_year_rdb="YEAR(rdv.date_rdv_debut)=YEAR(CURDATE())";
	$new_year_demande="YEAR(demande.date)=YEAR(CURDATE())";
	

        //echo($options->type_is_not_accompagnement_specifique);
	
	$entity_possible=array("demande","rdv","bien","personne","tache");
	//rechercher l'entity
	$entity_date="demande";
	foreach($entity_possible as $ep):
	    $request="FROM $ep";
	    if(preg_match('#'.$request.'#', $query)): $entity_date=$ep; endif;
	
	endforeach;
	
        
        
        
        
	$query_left=explode(" LEFT JOIN ",$query);
	
	$number_where=(count($query_left))-1;
	$query_t=$query_left[$number_where];
        
        $condition_where=array();
        
	
	
	  
            
            
		if(!empty($options->user_charge)||!empty($options->user_backup)):
                    $query_to_array=NULL;
		    //$listes=explode(",",$options->user_charge);
		    if(!empty($options->user_charge)&&!empty($options->user_backup)):
			    $query_to_array.="(";
			    $query_to_array.="demande.id_utilisateur IN($options->user_charge)";
			    $query_to_array.=" OR ";
			    $query_to_array.="demande.id_utilisateur_2 IN($options->user_backup)";
			    $query_to_array.=")";
		    elseif(!empty($options->user_charge)&&empty($options->user_backup)):
			    $query_to_array.="demande.id_utilisateur IN($options->user_charge)";
		    elseif(empty($options->user_charge)&&!empty($options->user_backup)):
			    $query_to_array.="demande.id_utilisateur_2 IN($options->user_backup)";
		    endif;
                    if(!is_null($query_to_array)):
                        array_push($condition_where,$query_to_array);
                    endif;
		endif;

		

		if(!empty($options->type_demande)):
			 
			
			
			    $query_to_array=" demande.id_type_demande IN($options->type_demande)";
			
			
                        if(!is_null($query_to_array)):
                            array_push($condition_where,$query_to_array);
                        endif;

		endif;
                
                
                if(!empty($options->type_accompagnement)):
			 
			//si dans la value il y 666 alors récupérer les acommpangement qui n'ont pas dd'acomppagemen
                        //passer les id possible des accompagnement spécifiques, les récupérer de la liste
                        //demande.id_demande IN(SELECT dc.id_demande FROM demande_caracteristique as dc WHERE (dc.id_type_accompagnement IN(1?2?3?3?3?3) AND dc.id_demande=demande.id_demande) )";
			$query_stock=array();
                       
                        if(stristr($options->type_accompagnement,"666") !== FALSE
                                
                                ):
                        
                            $qt="( demande.id_type_demande IN (1,2,3) AND demande.id_demande NOT IN(SELECT dc.id_demande FROM demande_caracteristique as dc WHERE (dc.id_type_accompagnement IN(SELECT id from liste_type_accompagnement) AND dc.id_demande=demande.id_demande) ))";
                            array_push($query_stock,$qt);
                        endif;

                        
                    
			$qt="( demande.id_type_demande=3 AND demande.id_demande IN(SELECT dc.id_demande FROM demande_caracteristique as dc WHERE (dc.id_type_accompagnement IN($options->type_accompagnement) AND dc.id_demande=demande.id_demande) ))";
			  array_push($query_stock,$qt);
			
                            $query_to_array="(".implode(" OR ",$query_stock ).")";
                            array_push($condition_where,$query_to_array);
//                       echo $options->type_accompagnement;
//                           print_r($query_stock); die();
		endif;
		
		

		if(!empty($options->statut_demande)):
		
		   
			$query_to_array=" demande.id_demande_statut IN($options->statut_demande)";
                        array_push($condition_where,$query_to_array);
			
		endif;
                
            
                
//                if(!is_null($options->type_is_not_accompagnement_specifique)&&!empty(strstr($query,"demande"))):
//		   
//		   $query_to_array=" demande_caracteristique.id_type_accompagnement>0 AND demande_caracteristique.id_type_accompagnement IS NOT NULL ";
//		    array_push($condition_where,$query_to_array);
//			
//		endif;
		
		//ifj'ai dans requete un from rdv ou un left JOIN RDV
		
                
                if(!empty(strstr($query,"FROM tache" ))||!empty(strstr($query,"LEFT JOIN tache"))):
		  
                      
                             if(!empty($options->statut_tache)):
                                $query_to_array=" tache.id_statut_tache IN($options->statut_tache)";
                                array_push($condition_where,$query_to_array);
                               endif;

                      
                        
                         if(!empty($options->user_tache)):
                           

                                $query_to_array=" tache.id_user_tache IN($options->user_tache)";
                                array_push($condition_where,$query_to_array);

                        endif;
                        
                     endif;     
                        
                        
                        
	
                
                
                
                if(!empty(strstr($query,"FROM rdv" ))||!empty(strstr($query,"LEFT JOIN rdv"))):
		  
                      
                    if(!empty($options->statut_rdv)):
                                $query_to_array=" rdv.id_statut_rdv IN($options->statut_rdv)";
                                array_push($condition_where,$query_to_array);
                    endif;
                      
                        
                         if(!empty($options->user_rdv)):
                         

                                $query_to_array=" rdv.id_user_rdv IN($options->user_rdv)";
                                array_push($condition_where,$query_to_array);

                        endif;
                     endif;     
                        
		
                
                
                
	    
            
         if(!is_null($condition_where)):
               $query_t.=" AND (";
               $query_t.=implode(" AND ",$condition_where);
         
               $query_t.=")";
         endif;
	
	$query_left[$number_where]=$query_t;
 
        
        
	$rquery=implode(" LEFT JOIN ",$query_left);
	
	//$rquery.= " ORDER BY demande.date DESC" ;
	
	$finish=str_replace("AND ()",NULL,$rquery);
        $finish=str_replace("AND ( )",NULL,$finish);
        
        //deplacement du groupe by
        if(!is_null($options->group_by_sidebar_sql)&&!empty($options->group_by_sidebar_sql)):
           
            $finish=str_replace("`",null,$finish);
            $finish=str_replace("GROUP BY ".$options->group_by_sidebar_sql,NULL,$finish);
            $finish=str_replace("GROUP BY ".$options->group_by_sidebar,NULL,$finish);
            $finish=$finish." GROUP BY $options->group_by_sidebar_sql ";
        endif;
        return $finish;
    }
   
    
    public function get_requete_origine($id_requete)
    {
        
        $requetes=$this->mdashboard->query_result("SELECT nom, id_requete FROM user_requete WHERE id_requete=$id_requete");
      
        if(isset($requetes[0]->id_requete)):
            return $requetes[0]->nom."<small><i>(id:".$requetes[0]->id_requete.")</i></small>";
        else:
            return NULL;
        endif;
    }
    
      public function get_table_data_total($query,$options=NULL,$limit=500,$postData=NULL)
    {
	//print_r($query); die();
        //print_r($postData);
	if(!is_null($postData)):
		    $draw = $postData['draw'];
		    $start = $postData['start'];
		    $rowperpage = $postData['length']; // Rows display per page
		    $columnIndex = $postData['order'][0]['column']; // Column index
		    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
		    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		    $searchValue = $postData['search']['value']; // Search value
	endif;

        
	$query_final=$this->create_where_filtre($query, $options);
      
	
	
	
	
	$results=$this->mdashboard->query_result(corrige_personne_contact($query_final));
	    //$results= $r->result();
	
      //echo $this->CI->db->last_query();
	//echo $query_final;
//	echo '<pre>';
//        print_r($results);
//        echo '</pre>';
	
	return count($results);
	
    }
    
    public function get_table_data($query,$options=NULL,$limit=200,$postData=NULL,$is_total=FALSE)
    {
       
        $query=str_replace("YEAR(rdv.date_rdv_debut)","YEAR(rdv.date_insert)",$query);
        $query=str_replace("YEAR(tache.date_rdv_debut)","YEAR(tache.date_insert)",$query);
	//print_r($query); die();
        //print_r($postData);
	if(!is_null($postData)):
		    $draw = $postData['draw'];
		    $start = $postData['start'];
		    $rowperpage = $postData['length']; // Rows display per page
		    $columnIndex = $postData['order'][0]['column']; // Column index
		    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
		    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		    $searchValue = $postData['search']['value']; // Search value
	endif;

        
	
	$query_final=$this->create_where_filtre($query, $options);
      
	if(!is_null($postData)):
	    $descriptor=$this->dataViewConstructorModel->getDescriptorBrut();
	$results["total"]= 500;
	$fields=explode(",",$options->field);
        $order_field=$fields[$columnName];
	if($searchValue != ''):
	    $query_final.= " AND  ";
	    $query_final.=" ( ";
		
		$ij=0;
                $concat_field=array();
		foreach($fields as $field):
		    if($ij>0): $query_final.=" OR "; endif;
		    if($descriptor[$field]["type_input"]=="select"):
			$tq="joinliste_$field";
			if(is_array($descriptor[$field]["select_field_sql"])):
			    $fiela=$descriptor[$field]["select_field_sql"];
			    $field1=$tq.'.'.$fiela[0];
			    $field2=$tq.'.'.$fiela[1];
			    $fcc="CONCAT($field1, $field2)";
                            array_push($concat_field,$fcc);
			    else:
				$fq=$descriptor[$field]["select_field_sql"];
				$tq="joinliste_$field";
				 $fcc=$tq.'.'.$fq;
                                 array_push($concat_field,$fcc);
			endif;
			
		    else:
			$fq=$descriptor[$field]["field_sql"];
			$tq=$descriptor[$field]["table"];
			 $fcc=$tq.'.'.$fq;
                         array_push($concat_field,$fcc);
		    endif;
                    
                    
                    $concat_string=implode(",",$concat_field);
                    
                    endforeach;
                    
                    
                    
                    
		   $sear_treat=explode(" ",$searchValue);
		    $lm=0;
		    $query_final.="(";
		   foreach($sear_treat as $ste):
			
			if($lm>0):
			     $query_final.=" AND ";
			endif;
			$query_final.=" CONCAT($concat_string) like '%".$ste."%' ";
                        
                       // $query_final.=" $fcc like '%".$ste."%' ";
			
			$lm=$lm+1;
		   endforeach;
		    $query_final.=")";
		    
		    $ij=$ij+1;
		
	    
	    $query_final.=" ) ";
	endif;
	
        
	
	//print_r($results["total"]);  die();
        if(!$is_total):
            
            
	$query_final.=" ORDER BY $order_field $columnSortOrder";
       
        //$total= (100-$start)+$rowperpage;
        
        //echo $start;
        //if($total>100):
	$query_final.= " LIMIT $rowperpage OFFSET $start ";
//        else:
//            $query_final.= " LIMIT 100 ";
//        endif;
        
        
        endif;
     
	    $r=$this->mdashboard->query_result(corrige_personne_contact($query_final));
	    $results= $r->result();
	else:
	    $query_final.= " LIMIT 500 ";
	$results=$this->mdashboard->query_result(corrige_personne_contact($query_final));
	    //$results= $r->result();
	endif;
    //echo $this->CI->db->last_query();
   // debugd($results);
	//debugd(corrige_personne_contact($query_final));
//	echo '<pre>';
//        print_r($results);
//        echo '</pre>';
	if($is_total):
            return count($results);
          else:  
	return $results;
        endif;
	
    }
    
    public function get_table_label($labels)
    {
        if(!empty($labels)):
	//$descriptor=$this->dataViewConstructorModel->getDescriptorBrut();
    $descriptor=$this->dataViewConstructorModel->getDescriptorBrut();
	$labels_translate=array();
	$label_a=explode(",",trim($labels));
        
	foreach($label_a as $index):
        if(isset($descriptor[$index]["label"]))
            array_push($labels_translate,$descriptor[$index]["label"]);
           
	endforeach;
	return $labels_translate;
        endif;
    }
	    
    public function convert_label($index)
    {
        $descriptor=$this->dataViewConstructorModel->getDescriptorBrut();

        if(isset($descriptor[$index])):
        return strip_tags(trim($descriptor[$index]["label"]));
        else:
            return "<span class='text-danger'>$index non trouvé</span>";
        endif;
    }
  
   public function get_deplacer($id_user,$id_onglet=NULL)
   {
       $data["id_user"]=$id_user;
   if(is_null($id_onglet)): $id_onglet=$this->id_first_onglet($id_user); endif;

       $data["id_onglet"]=$id_user;
       
       $data["pannels"]=$this->get_pannel($id_user,$id_onglet);
       
       return view($this->viewpath."dash_deplacer",$data);
   }
   
   
   
   public function get_pannel($id_user,$id_onglet)
   {
	$tables=array();
	$table_sql="user_table";
	$where="id_user=$id_user AND id_user_table_onglet=$id_onglet";
	$order="rank";
	$data["id_user"]=$id_user;
	return $this->mdashboard->read_data($table_sql,"*",$where,NULL,$order);
	
   }
   
   public function get_ajouter($id_user,$id_onglet)
   {
       $data["id_user"]=$id_user;
       $requetes=$this->mdashboard->read_data("user_requete","*","is_dasboard=1",NULL,"nom");
       if(isset($requetes[0]->id_requete)):
	$data["requetes"]=$requetes;
       else:
	   $data["requetes"]=NULL;
       endif;
       $data["id_onglet"]=$id_onglet;
       return view($this->viewpath."dash_ajouter",$data);
   }
   
   
   public function get_utilisateur()
   {
        return $this->mdashboard->read_data("user_accounts","id,concat(nom,' ',prenom) as label","is_new=1",NULL,"nom");

   }
   
   public function get_filtre_utilisateur_en_charge($value=NULL)
   {
       $datas=$this->mdashboard->read_data("user_accounts","id,concat(nom,' ',prenom) as label","is_new=1",NULL,"nom");
       $data["label"]="Utilisateur en charge";
       $data["datas"]=$datas;
	$data["name"]="user_charge";
	$data["value"]=$value;
    
       return view($this->viewpath."dash_filtre",$data);
   }
  
   
   public function get_filtre_utilisateur_back_up($value=NULL)
   {
       $datas=$this->mdashboard->read_data("user_accounts","id,concat(nom,' ',prenom) as label","is_new=1",NULL,"nom");
       $data["label"]="Utilisateur en back up";
       $data["datas"]=$datas;
       $data["name"]="user_backup";
       $data["value"]=$value;
       return view($this->viewpath."dash_filtre",$data);
   }
   
   public function get_filtre_type_demande($value=NULL)
   {
       $datas=$this->mdashboard->read_data("liste_demande_type","*",NULL,NULL,"rank");
       //$datas2=$this->mdashboard->read_data("liste_type_accompagnement","*",NULL,NULL,"rank");

       $data["label"]="Type de demande";
       $data["datas"]=$datas;
       //$data["datas2"]=$datas2;
       $data["name"]="type_demande";
       $data["value"]=$value;
       return view($this->viewpath."dash_filtre",$data);
   }
   
   public function get_filtre_type_accompagnement($value=NULL)
   {
       $datas=$this->mdashboard->read_data("liste_type_accompagnement","*",NULL,NULL,"rank");

       $data["label"]="Type d'accompagnement";
       $data["datas"]=$datas;
       $data["name"]="type_accompagnement";
       $data["value"]=$value;
       return view($this->viewpath."dash_filtre",$data);
   }
   
   
//   public function get_filtre_type_is_not_accompagnement_specifique($value=NULL)
//   {
//       $datas=$this->mdashboard->read_data("liste_oui_non","*",NULL,NULL,"rank");
//
//       $data["label"]="Pas d'accompagnement spécifique";
//       $data["datas"]=$datas;
//       $data["name"]="type_is_not_accompagnement_specifique";
//       $data["value"]=$value;
//       return view($this->viewpath."dash_filtre",$data);
//   }
   
   public function get_filtre_statut_demande($value=NULL)
   {
       $datas=$this->mdashboard->read_data("liste_demande_statut","*",NULL,NULL,"rank");
       $data["label"]="Statut de la demande";
       $data["datas"]=$datas;
       $data["name"]="statut_demande";
       $data["value"]=$value;
       return view($this->viewpath."dash_filtre",$data);
   }
   
    public function get_filtre_user_rdv($value=NULL)
   {
       $datas=$this->mdashboard->read_data("user_accounts","id,concat(nom,' ',prenom) as label","is_new=1",NULL,"nom");
       $data["label"]="User RDV";
       $data["datas"]=$datas;
       $data["name"]="user_rdv";
       $data["value"]=$value;
       return view($this->viewpath."dash_filtre",$data);
   }
   
   public function get_filtre_statut_rdv($value=NULL)
   {
       $datas=$this->mdashboard->read_data("liste_rdv_statut","*",NULL,NULL,"rank");
       $data["label"]="Statut du RDV";
       $data["datas"]=$datas;
       $data["name"]="statut_rdv";
       $data["value"]=$value;
       return view($this->viewpath."dash_filtre",$data);
   }
   
     public function get_filtre_user_tache($value=NULL)
   {
       $datas=$this->mdashboard->read_data("user_accounts","id,concat(nom,' ',prenom) as label","is_new=1",NULL,"nom");
       $data["label"]="User tache";
       $data["datas"]=$datas;
       $data["name"]="user_tache";
       $data["value"]=$value;
       return view($this->viewpath."dash_filtre",$data);
   }
   
   public function get_filtre_statut_tache($value=NULL)
   {
       $datas=$this->mdashboard->read_data("liste_tache_statut","*",NULL,NULL,"rank");
       $data["label"]="Statut du tache";
       $data["datas"]=$datas;
       $data["name"]="statut_tache";
       $data["value"]=$value;
       return view($this->viewpath."dash_filtre",$data);
   }
   
   public function link($label,$index){
       //echo $index;
       switch ($index):

        case "demande_id_visite":
        case 'id_demande':
        case "demande_id":
	    case "id_demande_id":
		$data["id"]=trim($label);
		$data["descriptor"]="demande";
	       //return "toto";
	       return view($this->viewpath."dash_a_demande",$data);

	       break;
           
           case "id_bien":
		$data["id"]=trim($label);
		$data["descriptor"]="bien";
	       //return "toto";
	       return view($this->viewpath."dash_a_demande",$data);

	       break;
	   
	   case "id_personne":
    case 'id_contact':
		$data["id"]=trim($label);
		$data["descriptor"]="contact";
	       //return "toto";
	       return view($this->viewpath."dash_a_demande",$data);

	       break;
	   
	   case "id_rdv":
		$data["id"]=trim($label);
		$data["descriptor"]="rdv";
	       //return "toto";
	       return view($this->viewpath."dash_a_demande",$data);

	       break;
	   
	    case "id_tache":
		$data["id"]=trim($label);
		$data["descriptor"]="tache";
	       //return "toto";
	       return view($this->viewpath."dash_a_demande",$data);

	       break;

	   default:
	       return $label;
	       break;
       endswitch;
       
       
   }
   
   public function get_onglet($id_user)
   {
       return $this->mdashboard->read_data("user_table_onglet","*","id_user=$id_user",NULL, "rank");
   }
   
   public function id_first_onglet($id_user)
   {
       $r=$this->mdashboard->read_data("user_table_onglet","*","id_user=$id_user",NULL, "rank");
       if(isset($r[0]->id_user)):
	   return $r[0]->id_user_table_onglet;
       endif;
   }
   
   public function get_name_onglet($id_onglet)
   {
              $r=$this->mdashboard->read_data("user_table_onglet","nom","id_user_table_onglet=$id_onglet");
	      if(isset($r[0]->nom)):
		  return $r[0]->nom;
	      else:
		  return NULL;
	      endif;

   }
	   
   public function test()
   {
    echo "test";
   }
}