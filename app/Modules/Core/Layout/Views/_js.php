<script>




// $(document).ready(function() 
// {
//     $(".submit_form").click(function() {
//         $(this).closest("form").submit();
//         $(".card_form_load").html('<div class="text-center m-5"><i class="fa fa-spin fa-spinner fa-2x"></i></div>');
//         // $("i", this).toggleClass("fa-bars fa-times");
//         // .fadeToggle(1000)
//     });

//     $(".submit_form_select").change(function() {
//         $(this).closest("form").submit();
//         $(".card_form_load").html('<div class="text-center m-5"><i class="fa fa-spin fa-spinner fa-2x"></i></div>');
//         // $("i", this).toggleClass("fa-bars fa-times");
//         // .fadeToggle(1000)
//     });
// });



//desactive tous les autocomplete formulaire
    
// window.addEventListener("beforeunload", function (e) {
//     //To do something (Remember, redirects or alerts are blocked here by most browsers):
//     $("body").fadeIn();
 
//     //To show a dialog (uncomment to test):
//     //return showADialog(e);  
// });






// // ------------------------------------------------------------------
// // Collapse map iframe
// // ------------------------------------------------------------------
// if(typeof collapse_map_iframe == 'undefined') {
//     function collapse_map_iframe(elem, module_name)
//     {
//         const target = $(elem).attr('data-bs-target');
//         if($(target).html().trim()==0) {
//             $.get("<?php echo base_url();?>" + module_name + "/external/mapping/iframe", function(view) {
//                 $(target).html(view);
//             });
//         }
//     }
// }

</script>

<?php echo view('Components\js/anchor');?>
<?php echo view('Components\js/ajax');?>
<?php echo view('Components\js/caret');?>
<?php echo view('Components\js/check-all');?>
<?php echo view('Components\js/checkbox-collapse');?>
<?php echo view('Components\js/checkbox-like-radio');?>
<?php echo view('Components\js/collapse-onblur');?>
<?php echo view('Components\js/console-formdata');?>
<?php echo view('Components\js/datepicker');?>
<?php echo view('Components\js/delete');?>
<?php echo view('Components\js/download');?>
<?php echo view('Components\js/form');?>
<?php echo view('Components\js/input-nullable');?>
<?php echo view('Components\js/mask');?>
<?php echo view('Components\js/modal');?>
<?php echo view('Components\js/plusminus');?>
<?php echo view('Components\js/read-update-form');?>
<?php echo view('Components\js/select');?>
<?php echo view('Components\js/sidebar');?>
<?php echo view('Components\js/sticky');?>
<?php echo view('Components\js/table-fullview');?>
<?php echo view('Components\js/textarea-autosize');?>
<?php echo view('Components\js/translator');?>
<?php echo view('Components\js/zindex-max');?>

<?php echo view("Layout\js_view_message"); ?>
<?php echo view("Layout\js_outlook"); ?>
<?php echo view("Layout\js_brugis"); ?>


