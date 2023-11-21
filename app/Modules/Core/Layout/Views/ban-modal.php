<div style="display:none" id="loading_layer">
  <div style='z-index:9999999;  position:fixed; width:100%; height:100%; background-color:black; opacity:0.5' class='loading_layer'> <div style='top:50%; left:50%; position:fixed'> <i class="fas fa-circle-notch fa-spin fa-4x"></i> <br> Loading… </div> </div>
  <div style='z-index:19999999; position:fixed; width:100%; height:100%; background-color:transparent; ' class='loading_layer'> <div style='top:50%; left:50%; position:fixed'> <i class="fas fa-circle-notch fa-spin fa-4x"></i> <br> Loading… </div> </div>
</div>
<div class="modal fade" id="modalAlert" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div style="background-color:#565e64" class="modal-header">
                <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle"></i> <span id="dataAlertTitre"></span></h5>
            </div>
            <div id="modalAlertBody" class="modal-body">
                Voulez-vous <span  id="dataAlertContent"></span>&nbsp;?
            </div>
            <div class="modal-footer">
                <button id="ModalAlertConfirm" data-type-confirm="" data-id-from="" type="button" class="btn btn-secondary">Oui</button>
                <button id="ModalAlertCancel" data-type-confirm="" data-id-from="" data-value-origin="" type="button" class="btn btn-danger">Non</button>
            </div>
        </div>
    </div>
</div>
<?php //modal pour delete url?>
<div class="modal fade" id="modalAlertDelete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div style="background-color:#565e64" class="modal-header">
                <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle"></i> <span id="dataAlerDeleteTitre"></span></h5>
            </div>
            <div id="modalAlertDeleteBody" class="modal-body">
                Voulez-vous effacer <span  id="dataAlertContentDelete"></span>&nbsp;? 
            </div>
            <div class="modal-footer">
                <a href="" id="ModalAlertDeleteConfirm" data-type-confirm="" data-id-from="" type="button" class="btn btn-secondary">Oui</a>
                <button id="ModalAlertDeleteCancel" data-type-confirm="" data-id-from="" data-value-origin="" type="button" class="btn btn-danger">Non</button>
            </div>
        </div>
    </div>
</div>

<?php //modal pour delete systeme de form plus sécrurisé A PRIVILIGIE ?>
<div class="modal fade" id="modalAlertDeleteForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div style="background-color:#565e64" class="modal-header">
                <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle"></i> <span id="dataAlertFormDeleteTitre"></span>Demande de suppression</h5>
            </div>
            <div id="modalAlertDeleteBody" class="modal-body">
                Voulez-vous effacer <span  id="dataAlertFormContentDelete"></span>&nbsp;? 
            </div>
            <div class="modal-footer">
                <form action="#" id="formDelete" method="post">
                    <input type="hidden" id="idDelete" name="idDelete" value="">
                    <input type="hidden"  name="uriReturn" value="<?=current_url(true)?>">
                    <button href="" id="ModalAlertFormDeleteConfirm" data-type-confirm="" data-id-from="" type="submit" class="btn btn-secondary">Oui</button>
                    <button id="ModalAlertFormDeleteCancel" data-type-confirm="" data-id-from="" data-value-origin="" type="button" class="btn btn-danger">Non</button>
                </form>   
                <div style="display:none" class="text-center ModalFormDeleteLoader">
                    <i class="fa fa-spin fa-spinner fa-2x"></i> En cours de suppression…
                </div> 
            </div>
        </div>
    </div>
</div>
<!--- Collection of view  --->
<div class="modal fade" id="modalView" tabindex="-1" aria-labelledby="modalViewTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div style="background-color:#565e64" class="modal-header">
        <h5 class="modal-title text-white" id="modalViewTitle">Modal title</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="modalViewBody" class="modal-body">
      </div>
    </div>
  </div>
</div>

<!-- modal pour la demande de l'id d'action for InjectedForm-->
<div class="modal fade" id="modalAlertInjectedForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div style="background-color:#565e64" class="modal-header">
                <h5 class="modal-title text-white">Pour prévisualiser, entrer un id d'action</h5>
            </div>
            <div id="modalAlertBody" class="modal-body">
                <label>Id article:</label>
                <input type="text" id="iframeIdActivite"></input>
                <input type="text" id="iframeSrcInput"></input>
            </div>
            <div class="modal-footer">
                <button id="ModalConfirmPrevisualisation" data-type-confirm="" data-id-from="" type="button" class="btn btn-secondary">Prévisualiser</button>
                <button id="ModalCancelPrevisualisation" data-type-confirm="" data-id-from="" data-value-origin="" type="button" class="btn btn-danger">Annuler</button>
            </div>
        </div>
    </div>
</div>

<!-- modal pour afficher un iframe -->
<div src-default="<?=base_url("iframe/index")?>" class="modal fade" id="modalViewFrame" tabindex="-1" aria-labelledby="modalViewTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div style="background-color:#565e64" class="modal-header">
        <h5 class="modal-title text-white" id="modalViewTitleIFrame">Modal title</h5>
        <button type="button" class="btn-close btn-close-white  btn-close btn-close-white -white text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="modalViewBody" class="modal-body">
          <div class="embed-responsive">
            <iframe style="min-height: 80vh;" id="iframeSrc" class="embed-responsive-item" src="<?=base_url("iframe/index")?>" width="100%" frameborder="0"></iframe>
          </div>
      </div>
    </div>
  </div>
</div>


<!-- modal pour enregistrer une requête-->
<?php if(isset($query)):?>
<div class="modal fade" id="modalSaveQuery" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div style="background-color:#565e64" class="modal-header">
                <h5 class="modal-title text-white">Créer une nouvelle requête</h5>
            </div>
            <div id="modalQueryBody" class="modal-body">
                <label>Nom de la nouvelle requête:</label>
                <?php if(isset($query)):?>
                <form action="<?php echo base_url()?>queries/save_query" id="formSaveQuery">
                  <input class="query_name form-control" name="nom" type="text" value="" ></input>
                  <input name="uri" type="hidden" value="<?=$uri?>" ></input>
                  <!--<textarea class="d-none"  name="query"><?=$query?></textarea>-->
                                  <input name="query" type="hidden" value="<?=htmlentities($query)?>"></input>

                  <input name="field" type="hidden" value="<?=implode(",",$getVar["fields_select"])?>"></input>
                </form>
                <?php endif;?>
            </div>
            <div class="modal-footer">
                <button id="ModalConfirmSaveQuery" data-type-confirm="" data-id-from="" type="button" class="btn btn-secondary">Enregistrer</button>
                <button id="ModalCancelSaveQuery" data-type-confirm="" data-id-from="" data-value-origin="" type="button" class="btn btn-danger">Annuler</button>
            </div>
        </div>
    </div>
</div>
<?php endif;?>


<!-- modal pour updater une requête-->
<?php if(isset($id_requete)&&isset($nom_requete)):?>
<div class="modal fade" id="modalUpdateQuery" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div style="background-color:#565e64" class="modal-header">
                <h5 class="modal-title text-white">Modifier la requête n°<?=$id_requete?></h5>
            </div>
            <div id="modalQueryBody" class="modal-body">
                <label>Si vous introduisez un autre nom de requête, cela aura pour action de changer le nom de la requête:</label>
                <?php if(isset($query)):?>
                  <form action="<?php echo base_url()?>queries/update_query/<?=$id_requete?>" id="formUpdateQuery">
                    <input class="query_name form-control" name="nom" type="text" value="<?=$nom_requete?>" ></input>
                    <input name="uri" type="hidden" value="<?=$uri?>" ></input>
                    <input name="query" type="hidden" value="<?=htmlentities($query)?>"></input>
                    <input name="field" type="hidden" value="<?=implode(",",$getVar["fields_select"])?>"></input>
                  </form>
                <?php endif;?>
            </div>
            <div class="modal-footer">
                <button id="ModalConfirmUpdateQuery" data-type-confirm="" data-id-from="" type="button" class="btn btn-secondary">Enregistrer</button>
                <button id="ModalCancelUpdateQuery" data-type-confirm="" data-id-from="" data-value-origin="" type="button" class="btn btn-danger">Annuler</button>
            </div>
        </div>
    </div>
</div>
<?php endif;?>

<!-- modal pour inserer un document -->
<div class="modal fade" id="AjouterDocumentModal" tabindex="-1" aria-labelledby="AjouterDocumentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
   
      <div class="modal-body p-0">
        <?php //https://docs.dropzone.dev/getting-started/setup/server-side-implementation ?>
          

            <form method="POST" enctype="multipart/form-data" action="<?=base_url()?>/documentUpload/upload_file" class="dropzone" id="my-dropzone">
              <div class="dz-message" data-dz-message><span><h4>Uploader des documents</h4>Cliquer ici pour ajouter des documents ou Glisser et déposer vos document dans cette fenêtre</span>
                  <input type="hidden" class="drop_zone_id_demande" value="" name="id_demande">
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger close_AjouterRdvModal">Fermer</button>
       
      </div>
    </div>
  </div>
</div>


<!-- modal pour inserer un document -->
<div class="modal fade" id="AjouterTicketModal" tabindex="-1" aria-labelledby="AjouterTicketModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
   
      <div class="modal-body p-0">
        <?php //https://docs.dropzone.dev/getting-started/setup/server-side-implementation ?>
        

            <form method="POST" enctype="multipart/form-data" action="<?=base_url()?>ticket/upload_file" class="dropzone" id="my-dropzone">
                <div class="row">
                <div class="col-lg-auto p-1 align-self-center text-center">
                  <?php if(isset($partenaire_culturels)):?>
                      <select name="id_partenaire_culturel">
                        <option value="0">Choisir un partenaire culturel</option>
                        <?php foreach($partenaire_culturels as $partenaire_culturel):?>
                            <option <?php if($id_partenaire_culturel==$partenaire_culturel->id_partenaire_culturel):?>selected<?php endif;?> value="<?=$partenaire_culturel->id_partenaire_culturel?>"><?=$partenaire_culturel->nom_partenaire_culturel?> (N°<?=$partenaire_culturel->numero_partenaire_culturel?>)</option>
                        <?php endforeach;?>
                      </select>
                  <?php endif;?>
                  <?php $annee_bottom="2011"; ?>

                  <?php if(isset($annee_select)):?>
                  <select name="annee_select">
                          <option value="0">Choisir une annee</option>
                          <?php for($annee=date("Y");$annee>=$annee_bottom;$annee--):?>
                              <option value="<?=$annee?>" <?php if($annee_select==$annee):?>selected<?php endif;?>><?=$annee?></option>
                          <?php endfor;?>
                  </select>  
                  <?php endif;?>

                  <?php if(isset($mois)):?>
                      <select name="mois_select">
                        <option value="0">Choisir un mois</option>
                        <?php foreach($mois as $index_mois=>$label_mois):?>
                            <option value="<?=$index_mois?>"><?=$label_mois?></option>
                        <?php endforeach;?>
                      </select>
                  <?php endif;?>
                </div>
                        </div>
                
                
               



              <div class="dz-message" data-dz-message><span><h4>Uploader des tickets</h4>Cliquer ici pour ajouter des tickets ou Glisser et déposer vos tickets dans cette fenêtre</span>
                  <input type="hidden" class="drop_zone_id_ticket" value="" name="id_ticket">
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger close_AjouterTicketModal">Fermer</button>
       
      </div>
    </div>
  </div>
</div>


<!-- modal pour inserer un document dans la boite de dépot de la demande alors qu'il existe dans le CRM -->
<div class="modal fade" id="GererDocumentModalCRM" tabindex="-1" aria-labelledby="GererDocumentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
    <div class="modal-content">

    <div style="background-color:#565e64" class="modal-header">
                <h5 class="modal-title text-white">Liste des documents de la demande n°<span id="id_demande_GererDocumentModalCRM"></span></h5>
            </div>
     
      <div class="modal-body p-0 container_GererDocumentModalCRM">
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger close_GererDocumentModalCRM">Fermer</button>
       
      </div>
    </div>
  </div>
</div>

<!-- modal pour inserer un document dans la boite de dépot de la demande alors qu'il existe dans le CRM -->
<div class="modal fade" id="AjouterDocumentModalCRM" tabindex="-1" aria-labelledby="AjouterDocumentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
    <div class="modal-content">

    <div style="background-color:#565e64" class="modal-header">
                <h5 class="modal-title text-white">Ajouter un document à la demande n°<span id="id_demande_AjouterDocumentModalCRM"></span></h5>
            </div>
     
      <div class="modal-body p-0 container_AjouterDocumentModalCRM">
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger close_AjouterDocumentModalCRM">Fermer</button>
       
      </div>
    </div>
  </div>
</div>


<!-- modal pour inserer un rdv -->
<div class="modal fade" id="AjouterRdvModal" tabindex="-1" aria-labelledby="AjouterRdvModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
     
      <div class="modal-body p-0">
          
          <div id="calendar"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
     
      </div>
    </div>
  </div>
</div>

<!-- modal pour voir un mail entier -->
<div class="modal fade" id="MessageModal" tabindex="-1" aria-labelledby="MessageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
     
      <div class="modal-body p-0">
          
          <div id="modal_message_mail">

                  
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
     
      </div>
    </div>
  </div>
</div>

<!-- modal pour gérer un rdv -->
<div class="modal fade" id="GestionRdvModalCRM" tabindex="-1" aria-labelledby="GestionRdvModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
    <div class="modal-content">

    <div style="background-color:#565e64" class="modal-header">
                <h5 class="modal-title text-white">Rendez-vous<span id="id_demande_GestionRdvModalCRM"></span></h5>
            </div>
     
      <div class="modal-body p-0 container_GestionRdvModalCRM p-2">
     
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-success enregistrer_GestionRdvModalCRM">Enregistrer</button>
        <button type="button" class="btn btn-danger close_GestionRdvModalCRM">Fermer</button>
       
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function() 
{
   // $("#loading_layer").hide();

    $(window).on('beforeunload', function()
    {
       $("#loading_layer").show();
         
    });
});


</script>

<!-- End Collection of modal -->


<!-- End Collection of modal -->

<!-- End Collection of modal -->
