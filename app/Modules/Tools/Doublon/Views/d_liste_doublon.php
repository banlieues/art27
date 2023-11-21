<?php $this->extend('Layout\index'); ?>
<?php $this->section("body"); ?>
<h3><i class="<?=icon("doublon")?>"></i> <?=$titleView?> <small>(<?=count($listes)?> cas trouvés</small>)</h3>
<b>Critères utilisés: </b> <?=$label_string?>

        <style>
            .trw{background-color:white !important}
            .trr{background-color:red !important}
            
        </style>

        <?php 
        $i=0;


        ?>

        <div class="accordion" id="accordionDoublon">
        <?php foreach($listes as $list):?>
          <div target=".target<?=$i?>" class="accordion-item target<?=$i?>">
             
                  <h2 class="accordion-header" id="heading<?=$i?>">
                    <button class="accordion-button collapsed doublon_affiche" data-bs-toggle="collapse" id_doublon='<?=$list->id_entity;?>' entity="<?=$entity_dynamique;?>" data-bs-toggle="collapse" data-bs-target="#collapse<?=$i?>" aria-expanded="true" aria-controls="collapse<?=$i?>">
                            <?php echo $list->nbr_doublon;?> doublons 
                            <?php foreach($input as $index=>$value): ?>
                              <?php if(isset($descriptor[$index])):?>
                                <?php $critere=$descriptor[$index]["field_sql"];?>
                                <?php if(!empty($list->$critere)): echo ' '.$list->$critere; echo " | "; endif;?>
                              <?php endif;?>
                            <?php endforeach;?>
                            
                    </button>
                  </h2>
                  
              </form>

                <div id="collapse<?php echo $i;?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$i?>" data-bs-parent="#accordionDoublon">
                  <div style="min-height:340px !important" class="accordion-body">
                   
                  </div>
                </div>
          </div>
          <?php $i=$i+1;;?>
        <?php endforeach;?>
        </div>

<?php $this->endSection(); ?>

<?php $this->section("script_foot_injected")?>
<script>

jQuery(document).ready(function()
		{  
				$(document).off("click",".doublon_affiche").on("click",".doublon_affiche",function(){
            var e=$(this);
            var id_doublons=e.attr("id_doublon");
            var entity=e.attr("entity");
            var dataString="id_doublons="+id_doublons;
            var url="<?php echo base_url();?>/doublon/get_tableau_fusion/"+entity;
            
            
            //alert(url);
            var container=$(this).closest(".accordion-item");
            var target=container.attr("target");
            //alert(target);
            var container_result=container.find(".accordion-collapse");
            
     
            container_result.html('<div style="text-align:center; padding: 100px 0"><i class="fa fa-spinner fa-spin fa-3x"></i><br>Veuillez patienter pendant que je crée le tableau de fusion</div>');
            jQuery.ajax
            ({  
              type:'POST',
              url: url,
              cache: false,
              data: dataString,

              success: function(html)
              { 
                
                  container_result.html(html);
                  
                  $('html, body').stop().animate({scrollTop: $(target).offset().top}, 500 );
              }
            });
   
        });

    $(document).off("submit",".form_fusion").on("submit",".form_fusion",function()
    {

      var form=$(this);
      var entity=$(".entity_en_cours").val();
      var accordion_item=form.closest(".accordion-item");
      
      $(".btn_submit",form).html('<i class="fa fa-spin fa-spinner"></i> Fusion en cours…');
      $(".table_fusion",form).css("opacity",'0.3');

      var dataString=form.serialize();
      var url="<?=base_url()?>/doublon/fusion/"+entity;
      
      jQuery.ajax
            ({  
              type:'POST',
              url: url,
              cache: false,
              data: dataString,

              success: function(html)
              { 
                
                  form.html(html);
                  $(".accordion-button",accordion_item).append(" Fusion ok");
                 
              }

            })
      return false;
      

      
    });
	});	
</script>
<?php $this->endSection(); ?>

