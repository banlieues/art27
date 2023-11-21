<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>

<?php $this->extend('\Partenaire_culturel\view-partenaire_culturel-base'); ?>

<?php $this->section('partenaire_culturel-body');?>

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
                                <td class="modif_container">

<?php 
        echo view('Ticket\form_ticket_num_code', [
                "ticket" => $ticket,
            
                
            ]);
            ?>

</td>

                                    <td>
                                    <a class="my-lightbox-toggle" data-toggle="lightbox" data-gallery="gallery-images" href="<?=base_url()?>tickets/individuel/<?=$ticket->url_file?>">
                                        <img width="100px" src="<?=base_url()?>tickets/individuel/<?=$ticket->url_file?>">
                                    </a>
                                        
                                    </td>
                                    <td>
                                        <?php if(!empty($ticket->id_partenaire_culturel)):?>
                                            <a href="<?=base_url()?>partenaire_culturel/<?=$ticket->id_partenaire_culturel;?>" class="btn btn-success btn-small">
                                                <?=$ticket->nom_partenaire_culturel?>
                                            </a>
                                        <?php endif;?>
                                       
                                    </td>

                                   
                                 
                                    <td>
                                        <?=$ticket->label_mois_ticket?>
                                        
                                    </td>
                                   
                                   

           

                                    <td class="modif_container CSelectTypeDocument<?=$ticket->id_ticket?> data_id_type_<?=$ticket->id_ticket?>">

                                            <?php 
                                            echo view('Ticket\form_ticket_statut', [
                                                    "ticket" => $ticket,
                                                    "statut_ticket"=>$statuts_ticket,
                                                    
                                                ]);
                                                ?>
  
                                    </td>

                                    <td class="modif_container">

                                            <?php 
                                                    echo view('Ticket\form_ticket_commentaire', [
                                                            "ticket" => $ticket,
                                                        
                                                            
                                                        ]);
                                                        ?>
   
                                    </td>

                                  
                                    <td>
                                        <?=convert_date_en_to_fr_with_h($ticket->date_scanning)?>

                                    </td>
                                    <td>
                                        <?=$ticket->scannor?>

                                    </td>

                                    <td>
                                       <?php if(!empty($ticket->id_partenaire_social)):?>

                                        <a class="btn btn-orange btn-small" href="<?=base_url()?>partenaire_social/fiche/<?=$ticket->id_partenaire_social?>"><?=$ticket->nom_partenaire_social?></a>
                                        <?php endif;?>

                                    </td>

                                    <td>
                                    <?php if(!empty($ticket->id_partenaire_social)):?>
                                        <?=$ticket->num_code?>
                                    <?php endif;?>

                                    </td>

                                    <td>

                                    <?php if(!empty($ticket->id_partenaire_social)):?>
                                        <?php echo '<img width="100px" src="data:image/png;base64,' . $ticket->barcode. '">'; ?>
                                    <?php endif;?>
                                    </td>

                                   
                             
                                   

                                
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


    
<?php $this->endSection();?>