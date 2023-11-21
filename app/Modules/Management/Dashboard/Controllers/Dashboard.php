<?php

namespace Dashboard\Controllers;

use Base\Controllers\BaseController;
use Components\Libraries\ComponentOrderBy;
use Dashboard\Libraries\Ldashboard;
use Dashboard\Models\Mdashboard;
use Layout\Libraries\LayoutLibrary;

class Dashboard extends BaseController
{
	public function __construct()
	{
	    if(session("loggedUserRoleId")==2) header("Location:" . base_url("user"));
        if(session("loggedUserRoleId")!=1) header("Location:" . base_url("identification/logout"));

	    parent::__construct(__NAMESPACE__);

		if($this->type_dashboard=="default")
		{
			header('Location: '.base_url("dashboard_default"));
		}
			
		if($this->type_dashboard=="default")
		{
			header('Location: '.base_url("dashboard_default"));
		}


		$layout_l = new LayoutLibrary();

		$this->context = 'dashboard';
		$this->datas->theme = $layout_l->getThemeByRef($this->context);
		$this->datas->context = $this->context;
		$this->viewpath = $this->module . "\Views\/"; 

		$this->componentOrderBy=new ComponentOrderBy();

		$this->mdashboard=new Mdashboard();
		$this->ldashboard=new Ldashboard();
		

		$this->autorisationManager = \Config\Services::autorisationModel();

		if(!$this->autorisationManager->is_autorise("dashboard_user"))
		{
			header("Location:".base_url("autorisation/no_autorisation"));
		}	
		
	}
	
	public function index($id_user=NULL,$id_onglet=NULL,$id_demande=0)
	{
	   //print_r($_SESSION);
	    if($id_user==0): $id_user=NULL; endif;
        if($id_onglet==0): $id_onglet=NULL; endif;
		
		
		$this->datas->id_demande=$id_demande;
	 
	    
	    
	  
	    if(is_null($id_user)): $id_user=session("loggedUserId"); endif;
	    $this->datas->id_user=$id_user;
	    if(is_null($id_onglet)): $id_onglet=$this->ldashboard->id_first_onglet($id_user); endif;
	    $this->datas->id_onglet=$id_onglet;
	    
	    $this->datas->view=$this->ldashboard->get_interface($id_user,TRUE,$id_onglet);
	    $this->datas->is_nav_pannel=TRUE;
	    	    
		$this->datas->viewpath=$this->viewpath;

	    return view($this->viewpath."dashboard_dynamique",(array) $this->datas);
	}
	
	public function maj_requete_orig()
        {
            $id_user_table=$this->request->getVar("id_user_table");
	
            if(!empty($id_user_table)):
                $query="SELECT id_requete, field FROM user_table WHERE id_user_table=$id_user_table";
				$db= \Config\Database::connect();
                $rs=$db->query($query)->getResult();
                if(isset($rs[0]->id_requete)):
                    $query="SELECT query, field, group_by_sidebar, group_by_sidebar_sql FROM user_requete WHERE id_requete=".$rs[0]->id_requete;
                    $rss=$db->query($query)->getResult();
                    if(isset($rss[0]->query)&!empty($rss[0]->query)):
                   $data_update["query_orig"]=$rss[0]->query;
                   $data_update["query"]=$rss[0]->query;
                   $data_update["field_orig"]=$rss[0]->field;
                   //$data_update["string_where"]=$rss[0]->string_where;
                   //$data_update["annee_select"]=$rss[0]->annee_select;
                   //$data_update["annee_select2"]=$rss[0]->annee_select2;
                     //$data_update["group_by_sidebar"]=$rss[0]->group_by_sidebar;
                     //$data_update["group_by_sidebar_sql"]=$rss[0]->group_by_sidebar_sql;
                   //Update field
                   $field_orig=explode(",",$rss[0]->field);
                   $field_now=explode(",",$rs[0]->field);
                   $newfield=array();
                   foreach($field_now as $f):
                       if(in_array($f,$field_orig)):
                           array_push($newfield,$f);
                       endif;
                   endforeach;
                    
//                   print_r($field_now);
//                   echo '<hr>';
//                   print_r($newfield);
                   
                $data_update["field"]=implode(",",$newfield);
                   
                    $where_update["id_user_table"]=$id_user_table;
                   

                    $this->mdashboard->update_data("user_table",$data_update,$where_update);
                        
                    //echo $query;
                    endif;
                endif;
                
            endif;
        }
	
	public function form_update()
	{
	    $where_update["id_user_table"]=$this->request->getVar("id_user_table");
	    $old_value=$this->mdashboard->read_data("user_table","field","id_user_table=".$this->request->getVar("id_user_table"));
	    
	    $old_field_array=explode(",",$old_value[0]->field);
	    $field_array=$this->request->getVar("field");
	    
	    $field_garde=array();
	    
	    foreach($old_field_array as $f):
		if(in_array($f,$field_array)):
		   array_push($field_garde,$f);
		endif;
	    endforeach;
	    
	    foreach($field_array as $fa):
		if(!in_array($fa,$old_field_array)):
		   array_push($field_garde,$fa);
		endif;
	    endforeach;
	    
	    $field=$this->request->getVar("field");
	    $field=$field_garde;
	    
	    $user_charge=$this->request->getVar("user_charge");
	    $user_backup=$this->request->getVar("user_backup");
	    $type_demande=$this->request->getVar("type_demande");
	    $statut_demande=$this->request->getVar("statut_demande");
	    $statut_rdv=$this->request->getVar("statut_rdv");
            $user_rdv=$this->request->getVar("user_rdv");
            $statut_tache=$this->request->getVar("statut_tache");
            $user_tache=$this->request->getVar("user_tache");
	    $type_accompagnement=$this->request->getVar("type_accompagnement");
            //$type_is_not_accompagnement_specifique=$this->request->getVar("type_is_not_accompagnement_specifique");
	    
	    if(!empty($field)):
		$data_update["field"]=implode(",",$field);
	    endif;
	    if(!empty($user_charge)):
		$data_update["user_charge"]=implode(",",$user_charge);
	    else:
		$data_update["user_charge"]=NULL;
	    endif;
	    if(!empty($user_backup)):
		$data_update["user_backup"]=implode(",",$user_backup);
	    else:
		$data_update["user_backup"]=NULL;
	    endif;
	    if(!empty($type_demande)):
		$data_update["type_demande"]=implode(",",$type_demande);
	    else:
		$data_update["type_demande"]=NULL;
	    endif;
	    if(!empty($statut_demande)):
		$data_update["statut_demande"]=implode(",",$statut_demande);
	    else:
		$data_update["statut_demande"]=NULL;
	    endif;
	    
            
	    if(!empty($type_accompagnement)):
		$data_update["type_accompagnement"]=implode(",",$type_accompagnement);
	    else:
		$data_update["type_accompagnement"]=NULL;
	    endif;
            
            if(!empty($statut_rdv)):
		$data_update["statut_rdv"]=implode(",",$statut_rdv);
	    else:
		$data_update["statut_rdv"]=NULL;
	    endif;
            
             if(!empty($user_rdv)):
		$data_update["user_rdv"]=implode(",",$user_rdv);
	    else:
		$data_update["user_rdv"]=NULL;
	    endif;
            
            
              if(!empty($statut_tache)):
		$data_update["statut_tache"]=implode(",",$statut_tache);
	    else:
		$data_update["statut_tache"]=NULL;
	    endif;
            
             if(!empty($user_tache)):
		$data_update["user_tache"]=implode(",",$user_tache);
	    else:
		$data_update["user_tache"]=NULL;
	    endif;
            
            
            //$data_update["type_is_not_accompagnement_specifique"]=$type_is_not_accompagnement_specifique;
            
            
	    $data_update["id_user_table_onglet"]=$this->request->getVar("id_user_table_onglet");
	    $data_update["size"]=$this->request->getVar("size");
	    $data_update["nom"]=$this->request->getVar("nom");
	    $this->mdashboard->update_data("user_table",$data_update,$where_update);
	    
	    $dataj["id"]=$this->request->getVar("id_user_table");
	    $dataj["size"]=$this->request->getVar("size");
	    echo json_encode($dataj);
	    //echo $this->db->last_query();
	}
	
	public function dupliquer_table()
	{
		
		$id_user_table=$this->request->getVar("id_user_table");
		if(!empty($id_user_table)):
			
			$tables=$this->mdashboard->read_data("user_table","*","id_user_table=$id_user_table");
			if(!empty($tables)):
			$table=$tables[0];

			$data_insert["color_background"]=$table->color_background;
			$data_insert["color_police"]=$table->color_police;
			$data_insert["id_user"]=$table->id_user;
			$data_insert["id_requete"]=$table->id_requete;
			$data_insert["query"]=$table->query;
			$data_insert["field"]=$table->field;
			$data_insert["size"]=$table->size;
			$data_insert["rank"]=0;
			$data_insert["query_orig"]=$table->query_orig;
			$data_insert["field_orig"]=$table->field_orig;
			//$data_insert["string_where"]=$table->string_where;
			$data_insert["is_dasboard"]=$table->is_dasboard;
			//$data_insert["annee_select"]=$table->annee_select;
			//$data_insert["annee_select2"]=$table->annee_select2;
			//$data_insert["group_by_sidebar"]=$table->group_by_sidebar;
			//$data_insert["group_by_sidebar_sql"]=$table->group_by_sidebar_sql;
			$data_insert["id_user_table_onglet"]=$table->id_user_table_onglet;
			$data_insert["nom"]='[COPIE] '.$table->nom;
	
					
					
			if(!empty($table->user_charge)):
				$data_insert["user_charge"]=$table->user_charge;
			endif;
			if(!empty($table->user_backup)):
				$data_insert["user_backup"]=$table->user_backup;
			endif;
			if(!empty($table->type_demande)):
				$data_insert["type_demande"]=$table->type_demande;
			endif;
			if(!empty($table->statut_demande)):
				$data_insert["statut_demande"]=$table->statut_demande;
			endif;
			if(!empty($table->user_rdv)):
				$data_insert["user_rdv"]=$table->user_rdv;
			endif;
			if(!empty($table->statut_rdv)):
				$data_insert["statut_rdv"]=$table->statut_rdv;
			endif;
			if(!empty($table->user_tache)):
				$data_insert["user_tache"]=$table->user_tache;
			endif;
			if(!empty($table->statut_tache)):
				$data_insert["statut_tache"]=$table->statut_tache;
			endif;
			if(!empty($table->type_accompagnement)):
				$data_insert["type_accompagnement"]=$table->type_accompagnement;
			endif;
			

			$this->mdashboard->insert_data($data_insert,"user_table");
			endif;
		endif;	
	}
	
	public function form_insert()
	{
	    
	    //print_r($this->request->getVar());
	    $id_requete=$this->request->getVar("id_requete");
	    $fields=$this->request->getVar("field");
	    $size=$this->request->getVar("size");
	    $nom=$this->request->getVar("nom");
	    $user_charge=$this->request->getVar("user_charge");
	    $user_backup=$this->request->getVar("user_backup");
	    $type_demande=$this->request->getVar("type_demande");
	    $statut_demande=$this->request->getVar("statut_demande");
	    $statut_rdv=$this->request->getVar("statut_rdv");
            $statut_tache=$this->request->getVar("statut_tache");
            $user_rdv=$this->request->getVar("user_rdv");
            $user_tache=$this->request->getVar("user_tache");
	    $type_accompagnement=$this->request->getVar("type_accompagnement");
		$color_background=$this->request->getVar("color_background");
		$color_police=$this->request->getVar("color_police");

           // $type_is_not_accompagnement_specifique=$this->request->getVar("type_is_not_accompagnement_specifique");
	    
	    $requetes=$this->mdashboard->read_data("user_requete","*","id_requete=$id_requete");
	    //print_r($requetes);
	    if(isset($requetes[0]->id_requete)):
		$requete=$requetes[0];
		$data_insert["color_background"]=$this->request->getVar("color_background");
		$data_insert["color_police"]=$this->request->getVar("color_police");
		$data_insert["id_user"]=$this->request->getVar("id_user");
		$data_insert["id_requete"]=$id_requete;
		$data_insert["query"]=$requete->query;
		$data_insert["field"]=implode(",",$fields);
		$data_insert["size"]=$size;
		$data_insert["rank"]=0;
		$data_insert["query_orig"]=$requete->query;
		$data_insert["field_orig"]=$requete->field;
		//$data_insert["string_where"]=$requete->string_where;
		$data_insert["is_dasboard"]=$requete->is_dasboard;
		//$data_insert["annee_select"]=$requete->annee_select;
		//$data_insert["annee_select2"]=$requete->annee_select2;
                //$data_insert["group_by_sidebar"]=$requete->group_by_sidebar;
                //$data_insert["group_by_sidebar_sql"]=$requete->group_by_sidebar_sql;
		$data_insert["id_user_table_onglet"]=$this->request->getVar("id_user_table_onglet");
               // $data_insert["type_is_not_accompagnement_specifique"]=$type_is_not_accompagnement_specifique;
                
                
		if(!empty($user_charge)):
		    $data_insert["user_charge"]=implode(",",$user_charge);
		endif;
		if(!empty($user_backup)):
		    $data_insert["user_backup"]=implode(",",$user_backup);
		endif;
		if(!empty($type_demande)):
		    $data_insert["type_demande"]=implode(",",$type_demande);
		endif;
		if(!empty($statut_demande)):
		    $data_insert["statut_demande"]=implode(",",$statut_demande);
		endif;
                if(!empty($user_rdv)):
		    $data_insert["user_rdv"]=implode(",",$user_rdv);
		endif;
		if(!empty($statut_rdv)):
		    $data_insert["statut_rdv"]=implode(",",$statut_rdv);
		endif;
                if(!empty($user_tache)):
		    $data_insert["user_tache"]=implode(",",$user_tache);
		endif;
		if(!empty($statut_tache)):
		    $data_insert["statut_tache"]=implode(",",$statut_tache);
		endif;
		if(!empty($type_accompagnement)):
		    $data_insert["type_accompagnement"]=implode(",",$type_accompagnement);
		endif;
		if(empty(trim($nom))):
		    $data_insert["nom"]=$requete->nom;
		else:
		    $data_insert["nom"]=$nom;
		endif;
		$this->mdashboard->insert_data($data_insert,"user_table");
	    endif;
	    //echo $this->db->last_query();
	}
	
	public function ajouter($id_user=NULL,$id_onglet=NULL)
	{
	     
	    if(is_null($id_user)): $id_user=session("loggedUserId"); endif;
	    if(is_null($id_onglet)): $id_onglet=$this->ldashboard->id_first_onglet($id_user); endif;
		$this->datas->id_user=$id_user;
	    $this->datas->id_onglet=$id_onglet;
	    $this->datas->view=$this->ldashboard->get_ajouter($id_user,$id_onglet);
		$this->datas->viewpath=$this->viewpath;

		return view($this->viewpath."dashboard_dynamique",(array) $this->datas);
	   
	}
	
	public function ajouter_onglet($id_user=NULL)
	{
	     
	    if(is_null($id_user)): $id_user=session("loggedUserId"); endif;
	    $this->datas->id_user=$id_user;
	    $this->datas->id_onglet=NULL;
	    $this->datas->view=view($this->viewpath."dash_ajouter_onglet",(array) $this->datas);
	    $this->datas->viewpath=$this->viewpath;
	    return view($this->viewpath."dashboard_dynamique",(array) $this->datas);
	}
	
	public function modifier_onglet($id_user=NULL,$id_onglet)
	{
	     
	    if(is_null($id_user)): $id_user=session("loggedUserId"); endif;

	    $this->datas->id_user=$id_user;
	    $this->datas->id_onglet=$id_onglet;
	    $this->datas->name_onglet=$this->ldashboard->get_name_onglet($id_onglet);
	    $this->datas->view=view($this->viewpath."/dash_modifier_onglet",(array) $this->datas);
	    
		$this->datas->viewpath=$this->viewpath;
	    
	    
	   //$data["view_tb"]=$this->get_tb($is_ajax=FALSE);
	
	    return view($this->viewpath."dashboard_dynamique",(array) $this->datas);
	  
	}
	
	public function form_insert_onglet()
	{
	    $table="user_table_onglet";
	    $id_user=$this->request->getVar("id_user");
	    $nom=$this->request->getVar("nom");
	    $rank=$this->mdashboard->read_data($table,"*","id_user=$id_user",NULL, "rank DESC");
	    if(isset($rank[0]->id_user)):
		$data["rank"]=$rank[0]->rank+10;
	    else:
		$data["rank"]=10;
	    endif;
	    if(empty($nom)): $nom="Sans titre"; endif;
	    
	    $data["id_user"]=$id_user;
	    $data["nom"]=$nom;
	    
	    $new_id=$this->mdashboard->insert_data($data,$table);
	    

		return redirect()->to(base_url()."/dashboard/index/$id_user/$new_id")->with("success","L'onglet <b>$nom</b> a été créé");;

	  
	}
	
	public function form_update_onglet()
	{
	    $table="user_table_onglet";
	    $new_id=$this->request->getVar("id_user_table_onglet");
	    $id_user=$this->request->getVar("id_user");
	    $where["id_user_table_onglet"]=$this->request->getVar("id_user_table_onglet");
	    $nom=$this->request->getVar("nom");
	    
	    
	    if(empty($nom)): $nom="Sans titre"; endif;
	    
	 
	    $data["nom"]=$nom;
	    
	    $this->mdashboard->update_data($table,$data,$where);
	    
	    return redirect()->to(base_url()."/dashboard/index/$id_user/$new_id")->with("success","Le nom de l'onglet a été modifié en <b>$nom</b>");;
	  
	}
	
	public function delete_table()
	{
	    $data["id_user_table"]=$this->request->getVar("id_user_table");
	    $this->mdashboard->delete_data("user_table",$data);
	}
	
	public function delete_onglet()
	{
	    $data["id_user_table_onglet"]=$this->request->getVar("id_onglet");
	    $this->mdashboard->delete_data("user_table_onglet",$data);
	    $this->mdashboard->delete_data("user_table",$data);
	    
	}
	
	public function deplacer($id_user=NULL,$id_onglet=NULL)
	{
	     
	 
	    if(is_null($id_user)): $id_user=session("loggedUserId"); endif;
	    $this->datas->id_user=$id_user;
	    if(is_null($id_onglet)): $id_onglet=$this->ldashboard->id_first_onglet($id_user); endif;
	    $this->datas->id_onglet=$id_onglet;
	    $this->datas->view=$this->ldashboard->get_deplacer($id_user,$id_onglet);
	    
	    
	    $this->datas->viewpath=$this->viewpath;
		
	
	   //$data["view_tb"]=$this->get_tb($is_ajax=FALSE);
	   
	    return view($this->viewpath."dashboard_dynamique",(array) $this->datas);
	  
	}
	
	public function update_sortable()
	{
	    $positions=$this->request->getVar("positions");
	    foreach($positions as $p):
		$where["id_user_table"]=$p[0];
		$data["rank"]=$p[1];
		$this->mdashboard->update_data("user_table",$data,$where);
	    endforeach;
	}
	
	
	public function update_sortable_onglet()
	{
	    $positions=$this->request->getVar("positions");
	    foreach($positions as $p):
		$where["id_user_table_onglet"]=$p[0];
		$data["rank"]=$p[1];
		$this->mdashboard->update_data("user_table_onglet",$data,$where);
	    endforeach;
	}
	public function update_sortable_field($id)
	{
	    $positions=$this->request->getVar("positions");
	    $new_field=array();
	    
	    foreach($positions as $p):
		
		array_push($new_field,$p[0]);
		
	    endforeach;
	    $where["id_user_table"]=$id;
	    $data["field"]=implode(",",$new_field);
	    $this->mdashboard->update_data("user_table",$data,$where);
	}
	
	
	public function load_table($id,$rank=0)
	{
	 
	    echo $this->ldashboard->get_interface_one($id,$rank); 
	}
        
        public function load_table_data_ajax($id,$rank=0)
	{
	     $draw = intval($this->request->getVar("draw"));
             $start = intval($this->request->getVar("start"));
             $length = intval($this->request->getVar("length"));
             
             $postData=$this->request->getVar();
             //print_r($postData);
            
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
//             echo "<pre>";
//             print_r($postData);
//             echo "</pre>";
             
             $data["datas"]=$this->ldashboard->get_table_data($query,$options,$limit=5,$postData);
             $count_total=$this->ldashboard->get_table_data_total($query,$options,$limit=5,$postData);
             //if($count_total>100): $count_total=100; endif;
             $count_total_search=$this->ldashboard->get_table_data($query,$options,$limit=5,$postData,$total=TRUE);
             //if($count_total_search>100): $count_total_search=100; endif;
//echo '<pre>';
//   print_r($data);
// echo "</pre>";

             $champs_index=explode(",",$options->field);
             $datas_treat=array();
             if(!empty($champs_index[0])):

                 foreach($data["datas"] as $data):
                     unset($da);
                     foreach($champs_index as $c_index):
                        $da[]= $this->ldashboard->link(strip_tags($data->$c_index),$c_index);
                     endforeach;
                      array_push($datas_treat,$da);           
                 endforeach;
            endif;
//             echo '<pre>';
//            print_r($datas_treat);
//            echo '</pre>';
            endforeach;
        
        
              $result = array(

               "draw" => $draw,

                 "iTotalRecords" => $count_total,

                 "iTotalDisplayRecords" => $count_total_search,

                 "data" => $datas_treat

            );


                echo json_encode($result);

                exit();
	}
	
	public function load_table_one($id,$rank=0)
	{
	    $postData=$this->request->getVar();
	   echo json_encode($this->ldashboard->get_interface_ajax_data_one($id,$rank,$postData)); 
	}
	
	public function get_filtre($id)
	{
	    $o=$this->mdashboard->read_data("user_requete","*","id_requete=$id");
	    $data["options"]=$o[0];
	    echo view($this->viewpath."/dash_get_filtre",$data);
	}
        
        public function get_field($id)
	{
	    $o=$this->mdashboard->read_data("user_requete","*","id_requete=$id");
	    $data["options"]=$o[0];
	    echo view($this->viewpath."/dash_get_field",$data);
	}
	
	public function gat_table_ajax($id){
     
     // POST data
	    $postData = $this->request->getVar();

	    // Get data
	    $data = $this->mdashboard->get_table_ajax($id,$postData);

	    echo json_encode($data);
	 }

	 public function update_color_font()
	 {
		 $data["color_police"]=$this->request->getVar("color");
		 $where["id_user_table"]=$this->request->getVar("id_user_table");
		 $this->mdashboard->update_data("user_table",$data,$where);
	 }

	 public function update_color_background()
	 {
		 $data["color_background"]=$this->request->getVar("color");
		 $where["id_user_table"]=$this->request->getVar("id_user_table");
		 $this->mdashboard->update_data("user_table",$data,$where);
	 }

	 public function update_order()
	 {
		 $data["index_order"]=$this->request->getVar("index_order");
		 $data["sort"]=$this->request->getVar("sort");

		 $where["id_user_table"]=$this->request->getVar("id_user_table");
		 $this->mdashboard->update_data("user_table",$data,$where);
	 }

	 public function update_len()
	 {
		 $data["len"]=$this->request->getVar("len");
		 $where["id_user_table"]=$this->request->getVar("id_user_table");
		 $this->mdashboard->update_data("user_table",$data,$where);
	 }

}
