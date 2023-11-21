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
                                            <a href="<?=base_url()?>partenaire_culturel/fiche/<?=$ticket->id_partenaire_culturel;?>" class="btn btn-success btn-small">
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