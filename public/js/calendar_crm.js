<script>
jQuery(document).ready(function()
{
    $(document).on("change","select[name='user_calendrier']",function() {
        //alert(a_mail);
        
       
        var email=$(this).val();

        var dataString="email="+email;

        var url=$("#url_calendar").attr("url");

        var container=$("#container_calendar_load");
       // alert(dataString);
       // alert(url);
        $("#loading_calendar_load").show();
        container.hide();

        jQuery.ajax
        ({	
            type:'POST',
            url: url,
            data: dataString,
            cache: false,
            success: function(html)
            { 
             
                container.html(html);
                $("#loading_calendar_load").hide();
                container.show();

                //alert(html);

            }

        }); 

        return false;
        //$(this).hide();
      }); 
}); 
</script>