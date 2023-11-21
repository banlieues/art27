<script type="text/javascript">

function row_edit_modal(table, id)
{
    $.ajax({
        url: '<?php echo base_url("liste/table");?>/' + table + '/row/' + id + '/modal',
        dataType: 'json',
        cache: false,
        success: function(result) {
            $('.modal-dialog','#modal').addClass('modal-lg');
            $('.modal-title','#modal').html(result.title);
            $('.modal-body','#modal').html(result.body);
            $('.modal-footer','#modal').prepend(result.submit);
            $('#modal').modal('show');
        }
    });     
}

function table_info_modal(table)
{
    $.ajax({
        url: '<?php echo base_url("liste/table");?>/' + table + '/modal',
        dataType: 'json',
        cache: false,
        success: function(result) {
            $('.modal-dialog','#modal').addClass('modal-lg');
            $('.modal-title','#modal').html(result.title);
            $('.modal-body','#modal').html(result.body);
            $('.modal-footer','#modal').prepend(result.submit);
            $('#modal').modal('show');
        }
    });    
}

$(document).ready(function(){
    sort($('#sort'));
});
function sort(elem)
{
    $(elem).sortable({
        update: function(event, ui) {
            data = [];
            $(this).children().each(function(index) {
                data.push({
                    pk_value : $(this).attr('pk_value'),
                    rank : index,
                });
            });

            const url = $(this).attr('action');
            $.post(url, {data : JSON.stringify(data)}, function(response) {
                $(ui.item).find('td').last().append('<button class="btn btn-sm link-success ms-4" title="La liste a bien été ré-ordonnée"> <?php echo fontawesome('check');?> </div>');
            });
        }
   });    
}

function row_new_modal(table)
{
    $.ajax({
        url: '<?php echo base_url("liste/table");?>/' + table + '/row/new/modal',
        dataType: 'json',
        cache: false,
        success: function(result) {
            $('.modal-dialog','#modal').addClass('modal-lg');
            $('.modal-title','#modal').html(result.title);
            $('.modal-body','#modal').html(result.body);
            $('.modal-footer','#modal').prepend(result.submit);
            $('#modal').modal('show');
        }
    });      
}
    
function row_edit(elem, table, id)
{   
    const form_id = $(elem).attr('form');
    const form = $('#' + form_id)[0];
    let formdata = new FormData(form);

    $.ajax({
        url: '<?php echo base_url('liste/table');?>/' + table + '/row/' + id,
        data: formdata,
        type: 'POST',
        method: 'POST',
        processData: false, // important
        contentType: false, // important
        cache: false,
        dataType: 'json',
        success: function(result) 
        {      
            if(!result.error) {
                window.location = '<?php echo base_url('liste/table');?>/' + table;
            } else if(result.error) {
                $('.validation-alert','#modal').html(result.error).show();
            }
        }      
    }); 
}
    
function row_new(elem, table)
{
    const form_id = $(elem).attr('form');
    const form = $('#' + form_id)[0];
    let formdata = new FormData(form);
    for(var pair of formdata.entries()) {
        console.log(pair[0]+ ', '+ pair[1]); 
    }
    $.ajax({
        url: '<?php echo base_url('liste/table');?>/' + table + '/row/new',
        data: formdata,
        type: 'POST',
        method: 'POST',
        processData: false, // important
        contentType: false, // important
        cache: false,
        dataType: 'json',
        success: function(result) 
        {         
            if(!result.error) {
                window.location = '<?php echo base_url('liste/table');?>/' + table;
            } else if(result.error) {
                $('.validation-alert','#modal').html(result.error).show();
            }
        }      
    }); 
}

//$(document).ready(function(){
////   afficher une table sur la page liste
//    $(document).off("click",".table-link").on("click",".table-link",function(){
//        var display = $(this);
//        var href = $(this).attr("href");
//        var link_encours = $(this).attr("link_encours");
//        var dataString = "link_encours=" + link_encours;
//        var url = href;
//              
//        $.ajax({
//            type:'POST',
//            data: dataString,
//            url: href,
//            cache: false,
//            success: function(html) {
//                $(".main").html(html);
//                $("html, body").stop().animate({scrollTop: $(".main").offset().top});
//            }
//        });
//        return false;
//    });
    
//   //déplacer les éléments du tableau d'affichage:
//    $("#sort").sortable({
//        update: function(event,ui){
//            $(this).children().each(function(index){
//                if($(this).attr('data-rank')!== (index+1)){
//                   if($(this).attr('data-rank') === 0){
//                       index = 0 ;
//                       $(this).attr('data-rank',(index)).addClass('updateTab');}
//                   else if($(this).attr('data-rank') < 10 ){
//                       $(this).attr('data-rank',(index+1)).addClass('updateTab');}
//                   else{
//                       $(this).attr('data-rank',((index+1)*10)).addClass('updateTab');
//                   }
//                }
//           });
//         newRank();
//       }
//   });
 
//    //afficher le form edit sur la page liste:
//    $(document).off("click",".edit").on('click','.edit',function(){;
//        var edit = $(this);
//        var href = edit.attr("href");
//        var url = href;
//        
//        $.ajax(
//               {
//                   type:'POST',
//                   url: url,
//                   cache: false,
//                   success: function(html)
//                   {
//                       $("html,body").stop().animate({scrollTop: $("#show").offset().top});
//                        $('#show').html(html);
//                        
//                   }
//                } 
//        );
//        
//       return false;
//        
//    });
    
// //cacher le form avec annuler
//    $(document).off("click",'#btn-edit').on('click','#btn-edit', function(){
//       $('#formEdit').css('display', 'none');
//       $("html,body").stop().animate({scrollTop: $("body").offset().top});
//        return false;
//    });
    
// //ajax sur le formumaire : 
//    $(document).off('submit','#form_chgt_select').on('submit','#form_chgt_select', function(){
//        var form = $(this);
//        var link_encours = form.closest("#container_link_encours").attr("link_encours");
//        var table = link_encours;
//        var id = form.attr("id_encours");
//       
//       
//        var dataString=form.serialize();
//        var url = "<?php echo site_url();?>/ListeController/update/"+table+"/"+id;
//        $.ajax(
//               {
//                   type:'POST',
//                   data: dataString,
//                   url: url,
//                   cache: false,
//                   success: function(html)
//                   { 
//                        $(".a"+link_encours).trigger("click");
//                        $("html,body").stop().animate({scrollTop: $("body").offset().top});
//                   }
//                   
//               } 
//                
//                );
//        
//        return false;
//    });
//});
////enregistrer le changement de rank:
// function newRank(){
//    var positions = [];
//    $('.updateTab').each(function(){
//        positions.push([$(this).attr('data-index'),$(this).attr('data-rank')]);
//        $(this).removeClass('updateTab');
//    });
//     
//    var url = $('.ui-sortable-handle').attr('data-table');
//    
//  $.ajax({
//      url: url ,
//      method: 'POST',
//      dataType: 'text',
//      data: {
//             update: 1,
//             positions: positions
//            }, 
//            success: function(response){
//              console.log(response);
//            }
//            
//        });
//    }


</script>
