<style>
    body
    {font-size:10px!important;}
</style>


<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $contactConstructor=\Config\Services::contact();?>



<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

    <div class="row">

        <div class="col-auto">
            <h3 class="fs-4"></h3>
        </div>
        
    
        <?php //return view("Demande\Views\interface/search_all"); ?>
        <div style="margin-bottom:20px" class='entities c_rubrique'>
            <div style="margin-bottom:0 !important; margin:10px; border-color: GREEN !important" class="card card-info">
                <div style="padding:0 !important" class="card-body">
                    <?php 
                        // Ici le coeur du formulaire 
                    ?>

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





<div class='entities c_rubrique form_gestion_entity load_ajax'>

<input type="hidden" name="interface" value="<?=$interface?>">
<input type="hidden"  id="create_demande_context" value="create_demande_context">

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
			<div style=""  class='row container_liste_demande'>
					<div class='col-md-10'>
						<div  style='background-color:#ecf0f1'  has_attachement_direct="1" class="well card p-3 mb-3 has_attachement_direct">
						<?php if(!is_null($demande_by_email)):?>
						<strong>Liste des demandes qui sont liées au mail "<?php echo $email_demande;?>"</strong> <br><i>(Cliquer sur une des demandes pour l'ouvrir et ensuite sur le bouton Attacher, pour ajouter le mail à cette demande. Sinon, remplisser le formulaire pour attacher le message à une nouvelle demande)</i><br>
						
						<div style=" max-height: 40vh !important; overflow: auto"  class='row container_liste_demande'>
							<?php echo $demande_by_email;?>
						</div>

					<?php else: ?>
						Aucune demande n'est attachée au mail <b>"<?php echo $email_demande;?> "</b>
						<?php endif;?>
						<hr>
						<div style="text-align:center">
						<!-- <button class="btn btn-success">Attacher à une demande pré-existante non liée préalablement au mail</button>-->
						
						
						<a href="<?=base_url();?>demande/associe_message_demande/<?=$id_message?>" class="btn btn-success"><i class="fa fa-link"></i>Rechercher une demande à laquelle rattacher le message #<?=$id_message?> </a>

						</div>
						</div>
					</div>
			
			</div>
			
    
      <?php endif;?>
	
	
	  
	
	 
<div class="row container_form_permanence">
    <!--- first tableau -->

<?php if(isset($info_origine_form)&&!empty($info_origine_form)):?>
	
	<div class="col-md-10">
	<button type="button" class="btn btn-sm btn-<?php echo $themes->demande_web->color;?> mb-4" data-bs-toggle="collapse" data-bs-target="#demandeInfo">
        <?php echo fontawesome('eye');?> Prévisualiser les données provenant du formulaire
    </button>
    <div class="collapse bg-light border rounded mb-4" id="demandeInfo">
        <table class="table table-sm">
        <?php foreach($info_origine_form as $ref=>$info):?>
            <tr>
                <td class="px-4 text-nowrap">
                    <small> <?php echo $info->title;?> </small>
                </td>
                <td class="px-4">
                    <?php if(is_array($info->label)):?>
                        <?php if($ref=='urls_file'):?>
                            <ul class="pl-0">
                                <?php foreach($info->label as $label):?>
                                    <li class="list-unstyled">
                                        <a class="text-body" href="<?php echo $label;?>" target="_blank"> <small> <strong> -  <?php echo pathinfo($label)['basename'];?> </strong> </small> </a>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    <?php else:?>
                        <small> <strong> <?php echo $info->label;?> </strong> </small>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </table>
    </div>
	</div>
<?php endif;?>
    <!--- Encodage conseil -->
    <div class="col-md-10">
	 
            <h3 id="tr_fiche_id_joindre">Nouvelle demande par <?=affiche_type_demande($type)?></h3>

            <div style="background-color:#ecf0f1"  id="tr_fiche_id_debut" class="card p-3 mb-3 well">
				<form class="form_form_submit form_submit fh_dao_insert">
						<div class="row">

								<div class="col-lg-6">
									
									<div class="row">
										<div class="col-lg-4">
											<?php echo $dataview->getElementFormByIndex("demande_is_premier_contact","demande");?>
										</div>
										<div class="col-lg-4">
											<?php echo $dataview->getElementFormByIndex("demande_origine","demande");?>
										</div>
										<div class="col-lg-4">
										<?php if(isset($contact->id_langue)): $contact_id_langue=$contact->id_langue; else: $contact_id_langue=NULL; endif;?>

											<?php echo $dataview->getElementFormByIndex("langue_personne","demande",$contact_id_langue);?>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="row">
										<div class="col-lg-6">
											<?php echo $dataview->getElementFormByIndex("id_type_demande","demande","noligne");?>
										</div>
										<?php if($interface!=="outlook"&&$interface!=="web"&&$interface!=="renolution"): ?>
											<div class="col-lg-6">
												<?php echo $dataview->getElementFormByIndex("demande_contact_duree","demande");?>
											</div>
										<?php endif;?>
									
									</div>
									
								</div>
						</div>

                        <?php if(($interface==="web"||$interface==="renolution")&&isset($id_bien_tamo)):?>
                            <form class="form_form_submit form_id_bien_tamo">
                                <input type="hidden"  class="id_bien_tamo" name="id_bien_tamo" value="<?=$id_bien_tamo?>">
                            </form>
                        <?php else:?>
                            <form class="form_form_submit form_id_bien_tamo">
                                <input type="hidden" class="id_bien_tamo"  name="id_bien_tamo" value="0">
                            </form>
                        <?php endif;?>		

                        <?php if(($interface==="web"||$interface==="renolution")&&isset($id_deposit)):?>
                            <form class="form_form_submit form_id_deposit">
                                <input type="hidden"  class="id_deposit" name="id_deposit" value="<?=$id_deposit?>">
                            </form>
                        <?php else:?>
                            <form class="form_form_submit form_id_deposit">
                                <input type="hidden" class="id_deposit"  name="id_deposit" value="0">
                            </form>
                        <?php endif;?>

                        <?php if(isset($importDemande->urls_file)):?>
                            <form class="form_form_submit form_url_file">
                            <?php foreach($importDemande->urls_file as $url_file):?>
                                
                                    <input type="hidden" class="url_file" name="urls_file[]" value="<?=$url_file;?>">
                                
                                <input  type="hidden" class="has_url_file" value="1">

                            <?php endforeach;?>	
                            </form>	
                            <?php else:?>
                                <form class="form_form_submit form_url_file">
                                <input  type="hidden" class="has_url_file" value="0">
                                </form>	
                        <?php endif;?>		
				</form>
            </div>
    </div>
    
    <!--- Information conseil -->
    <div style='display:none' class="col-md-10 c_demande_information load_ajax ">
        <div style='background-color:#ecf0f1' id="tr_fiche_information" class="well card p-3 mb-3">
		<form class="form_form_submit form_submit fh_dao_insert">
            <h5><b>Informations/conseil</b></h5>
            <?php if($autorisationManager->is_autorise("pole_u")):?>
                    <div class="row">
                        <div  class="col-lg-4"><?php echo $dataview->getElementFormByIndex("demande_pole","demande");?></div>

                    </div>

                <?php endif;?>
            <div class="row">
            
            <div class="col-lg-6">
                
                <div class="row">
                        <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("id_type_info_conseil","demande");?></div>

                </div>

                <div class="row">

                
                    <div  class="col-lg-6"><?php echo $dataview->getElementFormByIndex("demande_id_thematique_principal","demande","noligne");?></div>
                    <div  class="col-lg-6"><?php echo $dataview->getElementFormByIndex("demande_id_thematique_secondaire","demande","noligne");?></div>
                </div>

                <div class="row">
                    <div class="col-lg-12"><?php echo $dataview->getElementFormByIndex("demande_id_accompagnement","demande", NULL,TRUE);?></div>
                    <div class="col-lg-12">
                    <div class="row">
                        <?php if($interface==="outlook"||$interface==="web"||$interface==="renolution"):?>
                        <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("nom_demande","demande",$nom_demande);?></div>
                        <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("descriptif_demande","demande",$descriptif_demande);?></div>
                        <?php else:?>
                        <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("nom_demande","demande");?></div>
                        <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("descriptif_demande","demande");?></div>
                        <?php endif;?>
                        <div class="col-lg-12">
                        
                        <div style="margin-top:10px; margin-bottom:10px" class="tr_is_occupe">
                            <form class="class="form_form_submit">
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
                    
                    <?php echo $dataview->getElementFormByIndex($sthi,"demande",NULL,true);?>
                    
                </div>
                <?php endforeach;?>
                
                </div>
            </div>   
            </div>
		</form>
        </div>
    </div>	
    <!--- fin Information conseil -->
    
    <!--- demandeur/bien -->
    <div class="col-md-10 load_ajax">
	<div class="row">
	     <!--- deamdneur -->
		
	    <div class="col-md-6"> 
		<div style='background-color:#ecf0f1'  id="tr_fiche_id_demandeur" has_attachement_direct="1" class="card p-3 mb-3 well tr_fiche_id_demandeur">
		<form class="form_form_submit form_submit fh_dao_insert">

		    <h5><strong>Demandeur</strong></h5>
		    <div class="mb-3">
		



			<div  id="demandeurs_possible" <?php if(isset($demandeurs_bien)&&!empty($demandeurs_bien)):?> <?php else:?> style="display:none" <?php endif;?>>
				<p><b><i>Liste de demandeurs possibles</i></b></p>
				<div  id="containeur_demandeurs_possible" style="max-height: 40vh !important; overflow: auto"  class='container_liste_demande'>


						<?php
						if(isset($demandeurs_bien)&&!empty($demandeurs_bien)):
							echo $demandeurs_bien;
						endif;
						?>

						
				</div>
			</div>

			
			 </div>


		  
		    
		    <div class="row">
			<div class="col-lg-6">
			    <?php echo $dataview->getElementFormByIndex("rel_personne_bien","personne",$rel_personne_bien);?>   
			</div>
			<div class="col-lg-6 ">    

				
			<?php if(isset($contact->id_civilite)): $contact_id_civilite=$contact->id_civilite; else: $contact_id_civilite=NULL; endif;?>

			    <?php echo $dataview->getElementFormByIndex("civilite_personne","personne",$contact_id_civilite);?>  


			</div>
		    </div>
		    
			<div class="my-2 p-2 bg-light container_form_search_link">
				<i>Vous pouvez effectuer une recherche afin d'associer un contact déjà existant dans le CRM</i>
				<?=$contactConstructor->form_search_link();?>
			</div>

		    <div class="row">
			<div class="col-lg-6">

			<?php if(isset($contact->nom_contact)): $contact_nom_contact=$contact->nom_contact; else: $contact_nom_contact=NULL; endif;?>

			    <?php 
			    echo $dataview->getElementFormByIndex("prenom_personne","personne",$contact_nom_contact);
			    ?>
			</div>
			<div class="col-lg-6">    

			<?php if(isset($contact->prenom_contact)): $contact_prenom_contact=$contact->prenom_contact; else: $contact_prenom_contact=NULL; endif;?>

			    <?php 
			    echo $dataview->getElementFormByIndex("nom_personne","personne",$contact_prenom_contact);
			    ?>
			    
			</div>
		    </div>
		    
		    
		    <div class="row">
			<div style='display:none' class="col-lg-12 message_obligatoire_contact">
			    <i>** Veuillez remplir soit le téléphone, l'email ou l'adresse/localité/pays </i>  
			</div>
			<div class="col-lg-12">
			    <?php if($interface==="outlook"):?>
					
			    	<?php echo $dataview->getElementFormByIndex("email_personne","personne",FALSE,$email_demande);?>  
			    
			    <?php else:?>


					<?php if(isset($contact_profil->email)): $contact_profil_email=$contact_profil->email; else: $contact_profil_email=NULL; endif;?>

			    	<?php echo $dataview->getElementFormByIndex("email_personne","personne",$contact_profil_email);?>  
			    <?php endif;?>
			</div>
			<div class="col-lg-12">  
				
			<?php if(isset($contact_profil->telephone)): $contact_profil_telephone=$contact_profil->telephone; else: $contact_profil_telephone=NULL; endif;?>

			    <?php echo $dataview->getElementFormByIndex("telephone_personne","personne",$contact_profil_telephone);?>  
			</div>
		    </div>
		    
		    <div class="row">
			<div class="col-lg-12">
			<?php if(isset($contact_profil->adresse)): $contact_profil_adresse=$contact_profil->adresse; else: $contact_profil_adresse=NULL; endif;?>

			    <?php echo $dataview->getElementFormByIndex("adresse_personne","personne",$contact_profil_adresse);?>   
			</div>
			
		    </div>
		    
		    <div class="row">
			<div class="col-lg-6">    
			<?php if(isset($contact_profil->localite)): $contact_profil_localite=$contact_profil->localite; else: $contact_profil_localite=NULL; endif;?>

			    <?php echo $dataview->getElementFormByIndex("localite_personne","personne",$contact_profil_localite);?>  
			</div>
			<div class="col-lg-6">
			<?php if(isset($contact_profil->pays)): $contact_profil_pays=$contact_profil->pays; else: $contact_profil_pays=NULL; endif;?>

			    <?php echo $dataview->getElementFormByIndex("pays_personne","personne",$contact_profil_pays);?>   
			</div>
			
		    </div>

		<?php 
		    str_replace("* Nom","Nom",$form_insert_personne);
		    str_replace("* Prénom","Prénom",$form_insert_personne);
		   // echo $form_insert_personne;
		    ?>
		    <input id="id_contact_form" type="hidden" name="id_contact" value="<?=$id_contact?>">
			<input id="id_contact_profil_form" type="hidden" name="id_contact_profil" value="<?=$id_contact_profil?>">

			</form>
			<button id="renitialiser_demandeur" class="btn btn-danger">Réinitialiser les données du demandeur</button>
		</div>
	    </div>
		
	    <!-- fin de demandeur -->	
	    <!--- bien -->
		
	    <div class="col-md-6 load_ajax"> 
		<div style='background-color:#ecf0f1'  id="tr_fiche_id_bien" has_attachement_direct="1" class="well card p-3 mb-3 tr_fiche_id_bien has_attachement_direct ">
		<form class="form_form_submit form_submit fh_dao_insert">
			<h5><strong>Bien</strong></h5>
		    <div id="biens_du_demandeur">
					<?php if(isset($bien_possibles)):?>
						<?php echo $bien_possibles;?>
					<?php endif;?>
					

			</div>
		    <div class="row">
			<div class="col-lg-6">
			<?php if(isset($bien->adresse_fr_cp)): $bien_adresse_fr_cp=$bien->adresse_fr_cp; else: $bien_adresse_fr_cp=NULL; endif;?>

			    <?php echo $dataview->getElementFormByIndex("adresse_fr_cp","bien",$bien_adresse_fr_cp);?>   
			</div>
			<div class="col-lg-6">    
			<?php if(isset($bien->id_type)): $bien_id_type=$bien->id_type; else: $bien_id_type=NULL; endif;?>
			    <?php echo $dataview->getElementFormByIndex("id_type_bien_coche","bien",$bien_id_type);?>  
			</div>
		    </div>
		    <div class="row">
			
			<div class="col-lg-6">
			    <div class="row">
				<div class="col-lg-12">
				<?php if(isset($bien->etage_logement)): $bien_etage_logement=$bien->etage_logement; else: $bien_etage_logement=NULL; endif;?>

				    <?php echo $dataview->getElementFormByIndex("etage_logement_bien","bien",$bien_etage_logement);?> 
				</div>
				<div class="col-lg-12">

				<?php if(isset($bien->id_nombre_logement)): $bien_id_nombre_logement=$bien->id_nombre_logement; else: $bien_id_nombre_logement=NULL; endif;?>

			    <?php echo $dataview->getElementFormByIndex("id_nombre_bien","bien",$bien_id_nombre_logement);?>   
			</div>
			    </div>
			</div>
			
			<div class="col-lg-6">
			
			<?php if(isset($bien->id_chauffage)): $bien_id_chauffage=$bien->id_chauffage; else: $bien_id_chauffage=NULL; endif;?>

			    <?php echo $dataview->getElementFormByIndex("id_chauffage_bien","bien",$bien_id_chauffage);?>   
			</div>
		
		    </div>
		    <div class="row c_adresse_permanence">
			<div class="col-lg-12">
			    <i>Taper la rue puis le numéro dans l'un des champs adresse. Dès qu'une rue et un numéro sont entrés dans les champs, le système va rechercher sur Brugis un ou plusieurs correspondants à cette adresse dans la langue du champ. Vous pouvez aussi obtenir la traduction d'une adresse en cliquant sur le bouton recherche brugis.</i>
			</div>
			<div class="col-lg-12 c_translate">
				
				<?php if(isset($bien->adresse_fr)): $bien_adresse_fr=$bien->adresse_fr; else: $bien_adresse_fr=NULL; endif;?>
				
			    <?php echo $dataview->getElementFormByIndex("adresse_fr_bien","bien",$bien_adresse_fr);?> 
<!--			    <button class='btn btn-default btn-xs translatebrugis'>Recherche Brugis FR</button>-->
			</div>
			<div class="col-lg-12 c_translate"> 
				
			<?php if(isset($bien->adresse_nl)): $bien_adresse_nl=$bien->adresse_nl; else: $bien_adresse_nl=NULL; endif;?>	

			    <?php echo $dataview->getElementFormByIndex("adresse_nl_bien","bien",$bien_adresse_nl);?>  
<!--			     <button class='btn btn-default btn-xs translatebrugis'>Recherche Brugis NL</button>-->
			</div>

			<div class="my-2 p-2 bg-light">
				<div style='margin-top: 10px'  class="col-lg-12 verif_doublon_adresse">
					<button class="btn btn-success btn-xs">Vérifier si un bien encodé dans le CRM pourrait correspondre à l'adresse</button>
				</div>
				<div style='margin-top: 10px; max-height: 40vh !important; overflow: auto;' class="col-lg-12 search_doublon_adresse p-2">

					
				</div>
			</div>


		    </div>
		    
		    <div class="row">
			<div class="col-lg-6">
			<?php if(isset($bien->bt)): $bien_bt=$bien->bt; else: $bien_bt=NULL; endif;?>	

			    <?php echo $dataview->getElementFormByIndex("adresse_bt","bien",$bien_bt);?>   
			</div>
			
		    </div>
		    
	       <?php //echo $form_insert_bien;?>
		    <form class="form_form_submit"><input type="hidden" name="id_entity_bien" value="<?=$id_bien?>"></form>
	       </div>
	     </div>
		</form>
	    <!-- fin de bien -->
	   
	    
	
	</div>
	
    </div>
<!-- fin de demandeur bien -->
     <!-- accompgnement -->
	    <div style="display:none" class="col-md-10 c_demande_accompagnement load_ajax">
		<form class="form_form_submit form_submit fh_dao_insert">
		<div style='background-color:#ecf0f1'  id="tr_fiche_accompagnement" class="well card p-3 mb-3">
		    <h5><b>Demande d'accompagnement</b></h5>
		    
		    <div class="row">
			
			<div class="col-lg-12">
			<?php if($autorisationManager->is_autorise("pole_u")):?>
				<div class="row">
					<div  class="col-lg-3"><?php echo $dataview->getElementFormByIndex("accompagnement_demande_pole","demande");?></div>

				</div>

			<?php endif;?>
			</div>
			
			<div class="col-lg-7">
			
				<span class='alert_accomp'><i>** Veuillez choisir soit un type d'accompagnement spécifique, soit une thématique d'entrée</i></span>

				<div class="row">

				    <div class="col-lg-4"><?php echo $dataview->getElementFormByIndex("accompagnement_demande_id_type_accompagnement","demande");?></div>
				    <div  class="col-lg-4"><?php echo $dataview->getElementFormByIndex("accompagnement_demande_id_thematique_principal","demande");?></div>
				    <div  class="col-lg-4"><?php echo $dataview->getElementFormByIndex("accompagnement_demande_id_thematique_secondaire","demande");?></div>
				</div>
				<div class="row">
					<div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("accompagnement_id_type_suivi_accompagnement","demande");?></div>

				</div>
				<div class="row">
				     <div class="col-lg-12"><?php echo $dataview->getElementFormByIndex("accompagnement_demande_id_accompagnement","demande", NULL,TRUE);?></div>
				    <div class="col-lg-12">
					<div class="row">
					    <?php if($interface==="outlook"||$interface==="web"||$interface==="renolution"):?>
							
					       <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("accompagnement_nom_demande","demande",$nom_demande);?></div>
					       <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("accompagnement_descriptif_demande","demande",$descriptif_demande);?></div>
						 
					       <?php else :?>
					        <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("accompagnement_nom_demande","demande");?></div>
					       <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("accompagnement_descriptif_demande","demande");?></div>
					       <?php endif;?>
					       <div class="col-lg-12">

						   <div style="margin-top:10px; margin-bottom:10px" class="tr_is_occupe">
		     <form class="class="form_form_submit">
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

				    <?php echo $dataview->getElementFormByIndex($stha,"demande",NULL,true);?>

				</div>
				<?php endforeach;?>

			    </div>
			</div>   
		    </div>
		</div>
		</form>
	    </div>
	    
	    <!-- fin accompagnement -->
	    
	     <!-- visite -->
	    <div style="display:none"  class="col-md-10 c_demande_visite load_ajax">
		<form class="form_form_submit form_submit fh_dao_insert">
		<div style='background-color:#ecf0f1'  id="tr_fiche_visite" class="well card p-3 mb-3">
		    <h5><b>Demande de visite</b></h5>
		    <div class="row">
			<div class="col-lg-7">


				<?php if($autorisationManager->is_autorise("pole_u")):?>
					<div class="row">
						<div  class="col-lg-5"><?php echo $dataview->getElementFormByIndex("visite_demande_pole","demande");?></div>

					</div>

				<?php endif;?>

				<div class="row">
			

				    <div class="col-lg-4"><?php echo $dataview->getElementFormByIndex("visite_demande_id_visite","demande", TRUE);?></div>
				    <div  class="col-lg-4"><?php echo $dataview->getElementFormByIndex("visite_demande_id_thematique_principal","demande");?></div>
				    <div  class="col-lg-4"><?php echo $dataview->getElementFormByIndex("visite_demande_id_thematique_secondaire","demande");?></div>
				</div>
				<div class="row">
				     <div class="col-lg-4"><?php echo $dataview->getElementFormByIndex("visite_demande_id_accompagnement","demande", TRUE);?></div>
				    <div class="col-lg-8">
					<div class="row">
					      
					       <?php if($interface==="outlook"||$interface==="web"||$interface==="renolution"):?>
					     <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("visite_nom_demande","demande",false,$nom_demande);?></div>
					       <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("visite_descriptif_demande","demande",false,$descriptif_demande);?></div>
		       
					       <?php else:?>
					        <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("visite_nom_demande","demande");?></div>
					       <div  class="col-lg-12"><?php echo $dataview->getElementFormByIndex("visite_descriptif_demande","demande",FALSE);?></div>
					       <?php endif; ?>
					       <div class="col-lg-12">

						   <div style="margin-top:10px; margin-bottom:10px" class="tr_is_occupe">
		     <form class="class="form_form_submit">
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

				    <?php echo $dataview->getElementFormByIndex($sthv,"demande",FALSE);?>

				</div>
				<?php endforeach;?>

			    </div>
			</div>   
		    </div>
		</div>
				</form>
	    </div>
	    
	    <!-- fin visite -->
     <div class="position_hide" style="position:fixed; right:40px; top:200px; width:200px">
    
        <ul class="list-group">
					 <?php if($interface==="outlook" ):?>
					<li href=""  style="cursor:pointer" href=""  class="list-group-item">
					     <a href="<?=base_url()?>outlook/message_view/<?=$id_message?>" id_message="<?php echo $id_message;?>" class="view_message btn btn-warning message_lire get_content_modal_p"><i class="<?=icon("message_view")?>"></i> Lire message</a>
					 </li>
					 <li href="#tr_fiche_id_joindre"  style="cursor:pointer" href=""  class="ascroll">
					     Joindre à une demande <span class="badge bg-danger"></span>
					 </li>
					 <?php endif;?>
					 <li href="#tr_fiche_id_debut"  style="cursor:pointer" href=""  class="list-group-item ascroll">
					     Début <span class="error_general_count badge bg-danger"></span>
					 </li>
					 <li href="#tr_fiche_information"  style="cursor:pointer; display:none" href=""  class="list-group-item ascroll scinformation">
					     Information <span class="error_fiche_information_count badge bg-danger"></span>
					 </li>
					 <li href="#tr_fiche_id_demandeur" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					    Demandeurs <span class="error_demandeur_count badge bg-danger"></span>
					    
					 </li>
					 <li href="#tr_fiche_id_bien" style="cursor:pointer" class="list-group-item tr_fiche_id_bien ascroll">
					    Bien <span class="error_bien_count badge bg-danger"></span>
					 </li>
					 <li href="#tr_fiche_accompagnement"  style="cursor:pointer; display:none" href=""  class="list-group-item ascroll scaccompagnement">
					     Accompagnement <span class="error_fiche_accompagnement_count badge bg-danger"></span>
					 </li>
					 <li href="#tr_fiche_visite"  style="cursor:pointer; display:none" href=""  class="list-group-item ascroll scvisite">
					     Visite <span class="error_fiche_visite_count badge bg-danger"></span>
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
	 <form class="class="form_form_submit"><input type="hidden" name="id_message" value="<?php if(isset($id_message)): echo $id_message; else: echo 0; endif;?>"></form>
	 <!--	
	 <button class="btn btn-success submit_permanence">Enregistrer</button>
	 <button style="display:none" class="btn btn-default attente_permanence"><i class='fa fa-spin fa-spinner'></i> Enregistrement en cours…</button>
  --> 
 
  
  
 
     
     </div> 
 	
</div> <!-- fin container -->
</div>    


</div>



                </div>
            </div>

    </div>
<?php $this->endSection(); ?>

<?php $this->section("script_foot_injected"); ?>
    <?php echo view($path . "\js_form"); ?>
    <?php $data["interface"] = $interface;?>
    <?php echo view($path . "\permanence_js", $data);?>
	<?php //echo view($path."\js_demande")?>
    <?php if(isset($importDemande)) echo view($path."\web_js");?>
<?php $this->endSection(); ?>
