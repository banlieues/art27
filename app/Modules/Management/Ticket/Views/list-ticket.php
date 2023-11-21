<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<a  style="display:none" href="<?=base_url()?>/ticket" id="reload_page_ticket">je recharge</a>
<div class="row banData">

    <div class="col-12">
        <div class="card border-top-<?php echo $themes->ticket->color;?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php //if($autorisationManager->is_autorise("tickets_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbTickets?> ticket<?=plurial_s($pager->getTotal())?></h5>
                                    <?php if(isset($partenaire_culturels)):?>
                      <select type="submit" name="id_partenaire_culturel">
                        <option value="0">Tous les partenaires culturels</option>
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
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?php echo $themes->ticket->color;?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php //endif;?>

                            <?php //if($autorisationManager->is_autorise("tickets_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a class="btn btn-<?php echo $themes->ticket->color;?> ajouter_ticket_modal btn-sm mt-1" id_demande="0" href="#">
                                    <?php echo $themes->ticket->icon;?>  Ajouter ticket
                                    </a>
                                </div>
                            <?php //endif;?>

                        </div>
                    </form>
                </div> 

            <?php //if($autorisationManager->is_autorise("tickets_r")):?>                                
                <?php if ($nbTickets>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->ticket->color )?><?php endif;?>
                        <div class="table-responsive"> 
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($tickets as $ticket):?>
                                <tr>
                                <?=view("Ticket\list_ticket_td",["ticket"=>$ticket]);?>


                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->ticket->color )?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de tickets</h3></div>        
                <?php endif;?>  
            <?php //endif;//autorisation?>  
        </div>        
    </div>

</div>    

<?php $this->endSection(); ?>

<?php $this->section("script_foot_injected"); ?>
<?=view("Ticket\Views\js_ticket")?>

<?php $this->endSection(); ?>

