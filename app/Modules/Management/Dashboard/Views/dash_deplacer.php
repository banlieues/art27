<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel(); ?>
<?php $descriptor=$dataViewConstructorModel->getDescriptorBrut();?>
<div class="container">
      <div class='alert alert-info'>
          Pour modifier l'ordre des colonnes d'un tableau, cliquer sur le nom d'un tableau, les champs des colonnes apparaissent, cliquer sur l'un des champs et glisser-déposer pour le changer de place. Le système enregistre automatiquement les nouvelles positions.
      </div>

      <div class="accordion accordion-flush" id="accordionChampTable">
      <?php foreach($pannels as $card):?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?php echo $card->id_user_table;?>">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $card->id_user_table;?>" aria-expanded="false" aria-controls="collapse<?php echo $card->id_user_table;?>">
                  <strong><?php echo $card->nom;?></strong>
              </button>
            </h2>
            <div id="collapse<?php echo $card->id_user_table;?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $card->id_user_table;?>" data-bs-parent="#accordionChampTable">
              <?php $fields=explode(",",$card->field);?>
              <div class="accordion-body">
                        <div class="retrie">
                              <?php foreach($fields as $field):?>
                                  <div style="cursor:grab" class="card card-info">
                                    <div data-id="<?php echo $card->id_user_table;?>" data-index="<?php echo $field;?>" class="card-body card-information">
                                        <b><?php echo  strip_tags($descriptor[$field]["label"]);?></b>
                                        <small>(SQL:<?php echo $field;?>)</small>
                                    </div>
                                  </div>
                              <?php endforeach;?>
                      
                        </div>
              </div>
            </div>
          </div>
      <?php endforeach;?>
      </div>
</div>
        

