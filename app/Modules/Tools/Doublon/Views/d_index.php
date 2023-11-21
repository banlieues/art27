<?php $this->extend('Layout\index'); ?>
<?php $this->section("body"); ?>
<h3><i class="<?=icon("doublon")?>"></i> <?=$titleView?></h3>

<?=$champSelection?>



<?php $this->endSection(); ?>
<?php $this->section("script_foot_injected"); ?>
<script>

    jQuery(document).ready(function()
    {
        $(document).off("change",".select_fusion").on("change",".select_fusion", function(){

            var checked=$(this);
            var card_select=checked.closest(".card_select_fusion");
            var container_form=checked.closest(".container_fusion").find(".id_doublon_container");
            var container_no_fiche=checked.closest(".container_fusion").find(".no_fiche");

            if(checked.is(':checked'))
            {
                var id_doublon_select=checked.val();
                var container_a_cloner=card_select.clone().addClass("mb-3");
            
                container_form.append(container_a_cloner);
                container_form.find(".select_fusion").removeClass("select_fusion").addClass("id_doublon_select");
                container_no_fiche.hide();

                url = new URL(window.location);
                url.searchParams.append('id_doublons[]', id_doublon_select);
                
                window.history.pushState({}, '', url);


    
            }
            else
            {
                var id_doublon=checked.val();
                $(".id_doublon_select",container_form).each(function()
                {
                    var id_doublon_select=$(this).val();

                    

                    if(id_doublon_select==id_doublon)
                    {
                        $(this).closest(".card_select_fusion").remove();
                     
                        var nb=$(".id_doublon_select",container_form).length;

                        url = new URL(window.location);
                        url.searchParams.delete('id_doublons[]', id_doublon);
                
                        window.history.pushState({}, '', url);

                        if(nb>0){container_no_fiche.hide()}else{container_no_fiche.show()}
                    }
                   
                }

                );
            }


        })

        $(document).off("change",".id_doublon_select").on("change",".id_doublon_select", function()
        {

          
            var checked=$(this);
            var container_fusion=checked.closest(".container_fusion");
            var container_no_fiche=checked.closest(".container_fusion").find(".no_fiche");

            if(checked.is(':checked'))
            {


            }
            else
            {
                var id_doublon=checked.val();
             
                $(".select_fusion",container_fusion).each(function()
                {
                    var id_doublon_select=$(this).val();

                    checked.closest(".card_select_fusion").remove();

                    var nb=$(".id_doublon_select",container_fusion).length;

                    if(nb>0){container_no_fiche.hide()}else{container_no_fiche.show()}      

                    if(id_doublon_select==id_doublon)
                    {
                        $(this).prop("checked",false);
                     
                       
                    }
                }

                );
            }


        });

        $(document).on("change",".ajax_charge", function()
        {
            $(".container_fusion").each(function()
            {
                var container=$(this);

                //id_doublon_select

                //select_fusion

                $(".select_fusion",container).each(function()
                {
                    var id_select_fusion=$(this).val();
                    var select_fusion=$(this);
                    $(".id_doublon_select",container).each(function()
                    {
                        var id_doublon_fusion=$(this).val();

                        if(id_doublon_fusion==id_select_fusion)
                        {
                            select_fusion.prop("checked",true);
                        }
                    });

                   

                })
                

            });

        });   
       
        
    });


</script>

<?php $this->endSection();?>




