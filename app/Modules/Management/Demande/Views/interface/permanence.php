
<?php 

if(!isset($interface)): 
		$interface=NULL; 
endif;
//echo $interface; die();

?>

<?php //debug($info_origine_form,true);?>

<?php $sths_information=array(
		"demande_id_prime",
		"demande_id_th_acoustique",
		"demande_id_th_energie",
		"demande_id_ths_isolation",
		"demande_id_ths_energie_renouvelable",
		"demande_id_ths_peb",
		"demande_id_th_logement",
		"demande_id_ths_aide_locative",
		"demande_id_ths_aide_achat",
		"demande_id_ths_aide_juridique",
		"demande_id_ths_insalubrite",
		"demande_id_ths_logement_inoccupe",
		"demande_id_th_renovation",
		"demande_id_th_patrimoine",
		"demande_id_ths_petit_patrimoine",
		
		"demande_id_th_urbanisme",
		"demande_id_ths_reglementation",
		"demande_id_ths_batiment_durable",); ?>


<?php $sths_accompagnement=array(
		"accompagnement_demande_id_prime",
		"accompagnement_demande_id_th_acoustique",
		"accompagnement_demande_id_th_energie",
		 "accompagnement_demande_id_ths_isolation",
		"accompagnement_demande_id_ths_energie_renouvelable",
		"accompagnement_demande_id_ths_peb",
		"accompagnement_demande_id_th_logement",
		"accompagnement_demande_id_ths_aide_locative",
		"accompagnement_demande_id_ths_aide_achat",
		"accompagnement_demande_id_ths_aide_juridique",
		"accompagnement_demande_id_ths_insalubrite",
		"accompagnement_demande_id_ths_logement_inoccupe",
		"accompagnement_demande_id_th_renovation",
		"accompagnement_demande_id_th_patrimoine",
		"accompagnement_demande_id_ths_petit_patrimoine",
		
		"accompagnement_demande_id_th_urbanisme",
		"accompagnement_id_ths_reglementation",
		"accompagnement_demande_id_ths_batiment_durable",
    
		"accompagnement_demande_th_regularisation",
		"accompagnement_demande_nb_coproprio",
		"accompagnement_demande_id_th_ag",
		"accompagnement_demande_id_type_projet",
		"accompagnement_demande_id_objet_pvb",
		"accompagnement_demande_date_pvb",
		"accompagnement_demande_id_credit_pvb",
		"accompagnement_demande_id_revenu",
		"accompagnement_demande_id_objet_permis",
		"accompagnement_demande_id_raison_permis",
		"accompagnement_demande_id_ths_petit_patrimoine_acc"
		
    
    
    ); ?>

<?php $sths_visite=array(
		"visite_demande_id_prime",
		"visite_demande_id_th_acoustique",
		"visite_demande_id_th_energie",
		 "visite_demande_id_ths_isolation",
		"visite_demande_id_ths_energie_renouvelable",
		"visite_demande_id_ths_peb",
		"visite_demande_id_th_logement",
		"visite_demande_id_ths_aide_locative",
		"visite_demande_id_ths_aide_achat",
		"visite_demande_id_ths_aide_juridique",
		"visite_demande_id_ths_insalubrite",
		"visite_demande_id_ths_logement_inoccupe",
		"visite_demande_id_th_renovation",
		"visite_demande_id_th_patrimoine",
		"visite_demande_id_ths_petit_patrimoine",
		
		"visite_demande_id_th_urbanisme",
		"visite_id_ths_reglementation",
		"visite_demande_id_ths_batiment_durable",); ?>





<div class='entities c_rubrique'>

<input type="hidden" name="interface" value="<?=$interface?>">

<div id="modal_content_mail_outlook" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		  </div>
		  <div style="height:1000px" class="modal-body modal_content_mail_outlook-body"></div>
		</div>
	  </div>
	</div>

<div style="background:white; padding-top: 20px!important" class="container-fluid container_insert_demande_principale"> 
    

    
    <div style="display:none" class="permanence_loading">
	<div style='text-align:center; margin:100px 0;'><i class='fa fa-spin fa-spinner fa-4x'></i><br>Veuillez patienter…</div>
	
    </div>  
    <?php if($interface==="outlook" ):?>
    	<h3 id="tr_fiche_id_joindre">Joindre à une demande</h3>
    <div class='row container_liste_demande'>
	<div class='col-md-10'>
	    <div  has_attachement_direct="1" class="well  has_attachement_direct">
	    <?php if(!is_null($demande_by_email)):?>
	    <strong>Liste des demandes liées au mail "<?php echo $email_demande;?> "</strong> <br><i>(Cliquer sur une des demandes pour l'ouvrir et ensuite sur le bouton Attacher, pour ajouter le mail à cette demande. Sinon, remplisser le formulaire pour attacher le message à une nouvelle demande)</i><br>
		<?php echo $demande_by_email;?>
	  <?php else: ?>
	    Aucune demande n'est attachée au mail <b>"<?php echo $email_demande;?> "</b>
	    <?php endif;?>
	    <hr>
	    <div style="text-align:center">
	    <!-- <button class="btn btn-success">Attacher à une demande pré-existante non liée préalablement au mail</button>-->
	     
	     
	     <button id_message="<?php echo $id_message;?>" href="<?=base_url();?>app/load_demandes/" class="btn btn-success edit_email_outlook"><i class="fa fa-pencil"></i>Attacher à une demande pré-existante non liée préalablement au mail</button>

	    </div>
	    </div>
	</div>
	
    </div>
    
    
      <?php endif;?>
	
	
	  
	
	 
<div class="row container_form_permanence">
    <!--- first tableau -->

<?php if(isset($info_origine_form)&&!empty($info_origine_form)&&($interface=="web"||$interface==="renolution")):?>
	
	<div class="col-md-10">
		<button type="button" class="btn btn-sm btn-info mb-4" data-toggle="collapse" data-target="#requestInfo">
			<i class="fa fa-eye"></i> Prévisualiser les données provenant du formulaire du site Web
			</button>
			<div class="collapse bg-light border rounded mb-4" id="requestInfo">
				<table class="table table-sm">
				<?php foreach($info_origine_form as $info):?>
					
					<tr>
						<td class="px-4 text-nowrap">
							<small> <?php echo $info->title;?> </small>
						</td>
						<td class="px-4">
							<small> 
								<strong> 
									<?php if(is_array($info->label)):?>
										<ul>
											<?php foreach($info->label as $il):?>
												<lI>
												<?php if($info->title=="Fichiers"):?>
													<a href="<?=$il?>" target="blank">
												<?php endif;?>
													<?=$il?>
												</lI>
												<?php if($info->title=="Fichiers"):?>
												</a>
											<?php endif;?> 
											<?php endforeach;?>		
										</ul>
									<?php else:?>	
											
												
												
												<?php echo $info->label;?>
											
										
									<?php endif;?>
								</strong> 
						</small>
						</td>
					</tr>
				<?php endforeach;?>
				</table>
			</div>
	</div>
<?php endif;?>
    <div class="col-md-10 load_ajax">
	 
	<h3 id="tr_fiche_id_joindre">Nouvelle demande</h3>

	<div id="tr_fiche_id_debut" class="well">
	    <div class="row">

		<div class="col-lg-6">
		    
		    <div class="row">
			<div class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("demande_contact_premier","demande");?></div>
			<div class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("demande_origine","demande");?></div>
			<div class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("langue_personne","demande");?></div>
		    </div>
		</div>
		<div class="col-lg-6">
		    <div class="row">
			<div class="col-lg-6"><?php echo $this->fh_dao->get_dao_input_insert("id_type_demande","demande","noligne");?></div>
			<?php if($interface!=="outlook"&&$interface!=="web"&&$interface!=="renolution"): ?>
			<div class="col-lg-6"><?php echo $this->fh_dao->get_dao_input_insert("demande_contact_duree","demande");?></div>
			<?php endif;?>
			
		    </div>
		    
		</div>
	    </div>

		<?php if(($interface==="web"||$interface==="renolution")&&isset($id_bien_tamo)):?>
			<form class="form_id_bien_tamo">
				<input type="hidden"  class="id_bien_tamo" name="id_bien_tamo" value="<?=$id_bien_tamo?>">
			</form>
		<?php else:?>
			<form class="form_id_bien_tamo">
				<input type="hidden" class="id_bien_tamo"  name="id_bien_tamo" value="0">
			</form>
		<?php endif;?>		

		<?php if(($interface==="web"||$interface==="renolution")&&isset($id_deposit)):?>
			<form class="form_id_deposit">
				<input type="hidden"  class="id_deposit" name="id_deposit" value="<?=$id_deposit?>">
			</form>
		<?php else:?>
			<form class="form_id_deposit">
				<input type="hidden" class="id_deposit"  name="id_deposit" value="0">
			</form>
		<?php endif;?>

		<?php if(isset($urls_file)):?>
			<form class="form_url_file">
			<?php foreach($urls_file as $url_file):?>
				
					<input type="hidden" class="url_file" name="urls_file[]" value="<?=$url_file;?>">
				
				<input  type="hidden" class="has_url_file" value="1">

			<?php endforeach;?>	
			</form>	
			<?php else:?>
				<form class="form_url_file">
				<input  type="hidden" class="has_url_file" value="0">
				</form>	
		<?php endif;?>		
	    
	</div>
    </div>
    
    <!--- Information conseil -->
    <div style='display:none' class="col-md-10 c_demande_information load_ajax">
	<div id="tr_fiche_information" class="well">
	    <h5><b>Informations/conseil</b></h5>
		<?php if($this->fh_dao->get_autorisation("upole")):?>
				<div class="row">
					<div  class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("demande_pole","demande");?></div>

				</div>

			<?php endif;?>
	    <div class="row">
		
		<div class="col-lg-6">
			
			<div class="row">
					<div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("id_type_info_conseil","demande");?></div>

				</div>
			<div class="row">

			
			    <div  class="col-lg-6"><?php echo $this->fh_dao->get_dao_input_insert("demande_id_thematique_principal","demande","noligne");?></div>
			    <div  class="col-lg-6"><?php echo $this->fh_dao->get_dao_input_insert("demande_id_thematique_secondaire","demande","noligne");?></div>
			</div>
			<div class="row">
			     <div class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("demande_id_accompagnement","demande", TRUE);?></div>
			    <div class="col-lg-8">
				<div class="row">
				    <?php if($interface==="outlook"||$interface==="web"||$interface==="renolution"):?>
				     <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("nom_demande","demande",false,$nom_demande);?></div>
				       <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("descriptif_demande","demande",false,nl2br($descriptif_demande));?></div>
				    <?php else:?>
				       <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("nom_demande","demande");?></div>
				       <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("descriptif_demande","demande");?></div>
				       <?php endif;?>
				       <div class="col-lg-12">
					   
					   <div style="margin-top:10px; margin-bottom:10px" class="tr_is_occupe">
					    <form>
						<div class="form-group tr_is_occupe">
						<input type="checkbox" name="is_occupe" value="1"> 
						</div>
					   <label>Je m'en occupe</label>

					    </form>
					 </div>
								      </div>
								  </div>
							   </div>
			</div>
		</div>
		
		<div class="col-lg-6">
		    <div class="row">
			<?php foreach($sths_information as $sthi): ?>
			<div class="col-lg-12">
			    
			    <?php echo $this->fh_dao->get_dao_input_insert($sthi,"demande",FALSE);?>
			    
			</div>
			<?php endforeach;?>
			
		    </div>
		</div>   
	    </div>
	</div>
    </div>	
    <!--- fin Information conseil -->
    
    <!--- demandeur/bien -->
    <div class="col-md-10 load_ajax">
	<div class="row">
	     <!--- deamdneur -->
	    <div class="col-md-6"> 
		<div id="tr_fiche_id_demandeur" has_attachement_direct="1" class="well tr_fiche_id_demandeur  has_attachement_direct">


		    <h5><strong>Demandeur</strong></h5>
		    <div>
			<?php if(isset($demandeurs)&&!is_null($demandeurs)):?>
			<p><b><i>Liste de demandeurs attachés à des demandes en lien avec le mail</i></b></p>
			<?php

			echo $demandeurs;

		    ?>
		     <?php endif;?>
		    </div>
		    
		    <div class="row">
			<div class="col-lg-6">
			    <?php echo $this->fh_dao->get_dao_input_insert("rel_personne_bien","personne",FALSE);?>   
			</div>
			<div class="col-lg-6">    
			    <?php echo $this->fh_dao->get_dao_input_insert("civilite_personne","personne",FALSE);?>  
			</div>
		    </div>
		    
		    <div class="row">
			<div class="col-lg-6">
			    <?php 
			    echo $this->fh_dao->get_dao_input_insert("prenom_personne_no_obligatoire","personne");
			    ?>
			</div>
			<div class="col-lg-6">    
			    <?php 
			    echo $this->fh_dao->get_dao_input_insert("nom_personne_no_obligatoire","personne");
			    ?>
			    
			</div>
		    </div>
		    
		    
		    <div class="row">
			<div style='display:none' class="col-lg-12 message_obligatoire_contact">
			    <i>** Veuillez remplir soit le téléphone, l'email ou l'adresse/localité/pays </i>  
			</div>
			<div class="col-lg-12">
			    <?php if($interface==="outlook"):?>
			    	<?php echo $this->fh_dao->get_dao_input_insert("email_personne","personne",FALSE,$email_demande);?>  
			    
			    <?php else:?>
			    	<?php echo $this->fh_dao->get_dao_input_insert("email_personne","personne",FALSE);?>  
			    <?php endif;?>
			</div>
			<div class="col-lg-12">    
			    <?php echo $this->fh_dao->get_dao_input_insert("telephone_personne","personne",FALSE);?>  
			</div>
		    </div>
		    
		    <div class="row">
			<div class="col-lg-12">
			    <?php echo $this->fh_dao->get_dao_input_insert("adresse_personne","personne",FALSE);?>   
			</div>
			
		    </div>
		    
		    <div class="row">
			<div class="col-lg-6">    
			    <?php echo $this->fh_dao->get_dao_input_insert("localite_personne","personne",FALSE);?>  
			</div>
			<div class="col-lg-6">
			    <?php echo $this->fh_dao->get_dao_input_insert("pays_personne","personne",FALSE);?>   
			</div>
			
		    </div>

		<?php 
		    str_replace("* Nom","Nom",$form_insert_personne);
		    str_replace("* Prénom","Prénom",$form_insert_personne);
		   // echo $form_insert_personne;
		    ?>
		    <form><input type="hidden" name="id_entity_personne" value="0"></form>
		</div>
	    </div>
	    <!-- fin de demandeur -->	
	    <!--- bien -->
	    <div class="col-md-6 load_ajax"> 
		<div id="tr_fiche_id_bien" has_attachement_direct="1" class="well tr_fiche_id_bien has_attachement_direct ">
		    <h5><strong>Bien</strong></h5>
		    <div id="biens_du_demandeur"></div>
		    <div class="row">
			<div class="col-lg-6">
			    <?php echo $this->fh_dao->get_dao_input_insert("adresse_fr_cp","bien",FALSE);?>   
			</div>
			<div class="col-lg-6">    
			    <?php echo $this->fh_dao->get_dao_input_insert("id_type_bien_coche","bien");?>  
			</div>
		    </div>
		    <div class="row">
			
			<div class="col-lg-6">
			    <div class="row">
				<div class="col-lg-12">
				    <?php echo $this->fh_dao->get_dao_input_insert("etage_logement_bien","bien",FALSE);?> 
				</div>
				<div class="col-lg-12">
			    <?php echo $this->fh_dao->get_dao_input_insert("id_nombre_bien","bien",FALSE);?>   
			</div>
			    </div>
			</div>
			
			<div class="col-lg-6">
			    <?php echo $this->fh_dao->get_dao_input_insert("id_chauffage_bien","bien",FALSE);?>   
			</div>
		
		    </div>
		    <div class="row c_adresse_permanence">
			<div class="col-lg-12">
			    <i>Taper la rue puis le numéro dans l'un des champs adresse. Dès qu'une rue et un numéro sont entrés dans les champs, le système va rechercher sur Brugis un ou plusieurs correspondants à cette adresse dans la langue du champ. Vous pouvez aussi obtenir la traduction d'une adresse en cliquant sur le bouton recherche brugis.</i>
			</div>
			<div class="col-lg-12 c_translate">
			    <?php echo $this->fh_dao->get_dao_input_insert("adresse_fr_bien","bien",FALSE);?> 
<!--			    <button class='btn btn-default btn-xs translatebrugis'>Recherche Brugis FR</button>-->
			</div>
			<div class="col-lg-12 c_translate">    
			    <?php echo $this->fh_dao->get_dao_input_insert("adresse_nl_bien","bien");?>  
<!--			     <button class='btn btn-default btn-xs translatebrugis'>Recherche Brugis NL</button>-->
			</div>
			<div style='margin-top: 10px'  class="col-lg-12 verif_doublon_adresse">
			    <button class="btn btn-success btn-xs">Vérifier si un bien encodé dans le CRM pourrait correspondre à l'adresse</button>
			</div>
			<div style='margin-top: 10px' class="col-lg-12 search_doublon_adresse">
			    
			</div>
		    </div>
		    
		    <div class="row">
			<div class="col-lg-6">
			    <?php echo $this->fh_dao->get_dao_input_insert("adresse_bt","bien",FALSE);?>   
			</div>
			
		    </div>
		    
	       <?php //echo $form_insert_bien;?>
		    <form><input type="hidden" name="id_entity_bien" value="0"></form>
	       </div>
	     </div>
	    <!-- fin de bien -->
	   
	    
	
	</div>
	
    </div>
<!-- fin de demandeur bien -->
     <!-- accompgnement -->
	    <div style="display:none" class="col-md-10 c_demande_accompagnement load_ajax">
		<div id="tr_fiche_accompagnement" class="well">
		    <h5><b>Demande d'accompagnement</b></h5>
		    
		    <div class="row">
			
			<div class="col-lg-12">
			<?php if($this->fh_dao->get_autorisation("upole")):?>
				<div class="row">
					<div  class="col-lg-3"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_demande_pole","demande");?></div>

				</div>

			<?php endif;?>
			    <span class='alert_accomp'><i>** Veuillez choisir soit un type d'accompagnement spécifique, soit une thématique d'entrée</i></span>
			</div>
			
			<div class="col-lg-7">
			<div class="row">
					<div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_id_type_suivi_accompagnement","demande");?></div>

				</div>
				<div class="row">

				    <div class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_demande_id_type_accompagnement","demande", TRUE);?></div>
				    <div  class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_demande_id_thematique_principal","demande");?></div>
				    <div  class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_demande_id_thematique_secondaire","demande");?></div>
				</div>
				<div class="row">
				     <div class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_demande_id_accompagnement","demande", TRUE);?></div>
				    <div class="col-lg-8">
					<div class="row">
					    <?php if($interface==="outlook"||$interface==="web"||$interface==="renolution"):?>
					       <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_nom_demande","demande",false,$nom_demande);?></div>
					       <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_descriptif_demande","demande",false,nl2br($descriptif_demande));?></div>
					       <?php else :?>
					        <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_nom_demande","demande");?></div>
					       <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("accompagnement_descriptif_demande","demande");?></div>
					       <?php endif;?>
					       <div class="col-lg-12">

						   <div style="margin-top:10px; margin-bottom:10px" class="tr_is_occupe">
		     <form>
			  <div class="form-group tr_is_occupe">
			 <input type="checkbox" name="accompagnement_is_occupe" value="1"> 
		    <label>Je m'en occupe</label>
			  </div>
		     </form>
		  </div>
					       </div>
					   </div>
				    </div>
				</div>
			</div>

			<div class="col-lg-5">
			    <div class="row">
				<?php foreach($sths_accompagnement as $stha): ?>
				<div class="col-lg-12">

				    <?php echo $this->fh_dao->get_dao_input_insert($stha,"demande",FALSE);?>

				</div>
				<?php endforeach;?>

			    </div>
			</div>   
		    </div>
		</div>
	    </div>
	    
	    <!-- fin accompagnement -->
	    
	     <!-- visite -->
	    <div style="display:none"  class="col-md-10 c_demande_visite load_ajax">
		<div id="tr_fiche_visite" class="well">
		    <h5><b>Demande de visite</b></h5>
		    <div class="row">
			<div class="col-lg-7">


				<?php if($this->fh_dao->get_autorisation("upole")):?>
					<div class="row">
						<div  class="col-lg-5"><?php echo $this->fh_dao->get_dao_input_insert("visite_demande_pole","demande");?></div>

					</div>

				<?php endif;?>

				<div class="row">
			

				    <div class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("visite_demande_id_visite","demande", TRUE);?></div>
				    <div  class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("visite_demande_id_thematique_principal","demande");?></div>
				    <div  class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("visite_demande_id_thematique_secondaire","demande");?></div>
				</div>
				<div class="row">
				     <div class="col-lg-4"><?php echo $this->fh_dao->get_dao_input_insert("visite_demande_id_accompagnement","demande", TRUE);?></div>
				    <div class="col-lg-8">
					<div class="row">
					      
					       <?php if($interface==="outlook"||$interface==="web"||$interface==="renolution"):?>
					     <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("visite_nom_demande","demande",false,$nom_demande);?></div>
					       <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("visite_descriptif_demande","demande",false,nl2br($descriptif_demande));?></div>
		       
					       <?php else:?>
					        <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("visite_nom_demande","demande");?></div>
					       <div  class="col-lg-12"><?php echo $this->fh_dao->get_dao_input_insert("visite_descriptif_demande","demande",FALSE);?></div>
					       <?php endif; ?>
					       <div class="col-lg-12">

						   <div style="margin-top:10px; margin-bottom:10px" class="tr_is_occupe">
		     <form>
			  <div class="form-group tr_is_occupe">
			 <input type="checkbox" name="visite_is_occupe" value="1"> 
		    <label>Je m'en occupe</label>
			  </div>

		     </form>
		  </div>
					       </div>
					   </div>
				    </div>
				</div>
			</div>

			<div class="col-lg-5">
			    <div class="row">
				<?php foreach($sths_visite as $sthv): ?>
				<div class="col-lg-12">

				    <?php echo $this->fh_dao->get_dao_input_insert($sthv,"demande",FALSE);?>

				</div>
				<?php endforeach;?>

			    </div>
			</div>   
		    </div>
		</div>
	    </div>
	    
	    <!-- fin visite -->
     <div class="panel position_hide" style="position:fixed; right:40px; top:200px">
    
   <ul class="list-group">
					 <?php if($interface==="outlook" ):?>
					<li href=""  style="cursor:pointer" href=""  class="list-group-item">
					     <button id_message="<?php echo $id_message;?>" class="btn btn-warning message_lire get_content_modal_p">Lire message</button>
					 </li>
					 <li href="#tr_fiche_id_joindre"  style="cursor:pointer" href=""  class="ascroll">
					     Joindre à une demande <span class="label label-danger"></span>
					 </li>
					 <?php endif;?>
					 <li href="#tr_fiche_new_demande"  style="cursor:pointer" href=""  class="ascroll"><strong>Nouvelle demande</strong></li>
					 <li href="#tr_fiche_id_debut"  style="cursor:pointer" href=""  class="list-group-item ascroll">
					     Début <span class="error_general_count label label-danger"></span>
					 </li>
					 <li href="#tr_fiche_information"  style="cursor:pointer; display:none" href=""  class="list-group-item ascroll scinformation">
					     Information <span class="error_fiche_information_count label label-danger"></span>
					 </li>
					 <li href="#tr_fiche_id_demandeur" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					    Demandeurs <span class="error_demandeur_count label label-danger"></span>
					    
					 </li>
					 <li href="#tr_fiche_id_bien" style="cursor:pointer" class="list-group-item tr_fiche_id_bien ascroll">
					    Bien <span class="error_bien_count label label-danger"></span>
					 </li>
					 <li href="#tr_fiche_accompagnement"  style="cursor:pointer; display:none" href=""  class="list-group-item ascroll scaccompagnement">
					     Accompagnement <span class="error_fiche_accompagnement_count label label-danger"></span>
					 </li>
					 <li href="#tr_fiche_visite"  style="cursor:pointer; display:none" href=""  class="list-group-item ascroll scvisite">
					     Visite <span class="error_fiche_visite_count label label-danger"></span>
					 </li>
					
					 <li class="list-group-item">
				      <button class="btn btn-success submit_permanence"
					      <?php if($interface==="telephone" ):?>
					      style="background-color: green; border-color:green" 
					      <?php endif; ?>
					       <?php if($interface==="bureau" ):?>
					      style="background-color: mediumpurple; border-color:mediumpurple" 
					      <?php endif; ?>
					       <?php if($interface==="outlook" ):?>
					      style="background-color: slategray; border-color:slategray" 
					      <?php endif; ?>
					       <?php if($interface==="guichet" ):?>
					      style="background-color: orange; border-color:orange" 
					      <?php endif; ?>
					      <?php if($interface==="stand" ):?>
					      style="background-color: salmon; border-color:salmon" 
					      <?php endif; ?>
					      
					      >Enregistrer</button>
	 <button style="display:none" class="btn btn-default attente_permanence"><i class='fa fa-spin fa-spinner'></i> Enregistrement en cours…</button>
    
					 </li>
					 <li class="list-group-item">
					     <a href="" class="btn btn-danger">Reset</a>
					 </li>
					 
				     </ul>

</div>
     
     </div>	
    </div>
	 <form><input type="hidden" name="id_message" value="<?php if(isset($id_message)): echo $id_message; else: echo 0; endif;?>"></form>
	 <!--	
	 <button class="btn btn-success submit_permanence">Enregistrer</button>
	 <button style="display:none" class="btn btn-default attente_permanence"><i class='fa fa-spin fa-spinner'></i> Enregistrement en cours…</button>
  --> 
 
  
  
 
     
     </div> 
 	
</div> <!-- fin container -->
</div>    


</div>
<?php 

$data["interface"]=$interface;
$this->load->view("interface/permanence_js",$data);

?>