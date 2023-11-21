<style>
    .fc-center h2{
        font-size: 20px !important;

      
    }

    .fc-icon{
        font-size: 14px !important;

    }
</style>

<div id="calendar_outlook_view"></div>

<script type="text/javascript">
  
   jQuery(document).ready(function(){
        
        var json_events=<?= json_encode($events); ?>;
        $('#calendar_outlook_view').fullCalendar({
            timeFormat: 'H:mm',
            lang: 'fr',
            header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listYear'
            },

            // defaultView: 'listYear',
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: json_events,

            eventClick: function(calEvent){
                // Set currentEvent variable according to the event clicked in the calendar toSource()

                var currentEvent = calEvent;
                var tab = currentEvent.start['_i'].split('T');
                var date = convert_date_fr(tab[0]);
                var start = tab[1].substring(0,5);
                tab = currentEvent.end['_i'].split('T');
                var date_end = convert_date_fr(tab[0]);
                var end = tab[1].substring(0,5);

                var title = currentEvent.title;
                
                if(date===date_end){
                    var content = 
                        '<table class="table" style="font-size:small;">\n\
                            <tr>\n\
                               <th>Date : </th>\n\
                               <td>'+date+' - '+start+' à '+end+'</td>\n\
                            </tr>';
                }else{
                     var content = 
                        '<table class="table" style="font-size:small;">\n\
                            <tr>\n\
                               <th>Date : </th>\n\
                               <td>'+date+' à '+start+' au '+date_end+' à '+end+' </td>\n\
                            </tr>';
                }
                
                if(currentEvent.hasOwnProperty("location")&&currentEvent.location!==""){
                     var contentp = '<tr><th>Lieu : </th><td>'+currentEvent.location+' </td></tr>';
                       content = content+contentp;
                }
                
                if(currentEvent.hasOwnProperty("attendees")&&currentEvent.attendees!==""){
                     var contentp = '<tr><th>Participant(s) : </th><td>'+currentEvent.attendees+' </td></tr>';
                       content = content+contentp;
                }
                
                if(currentEvent.hasOwnProperty("organize")&&currentEvent.organize!==""){
                     var contentp = '<tr><th>Organiser par : </th><td>'+currentEvent.organize+' </td></tr>';
                       content = content+contentp;
                }
                
                if(currentEvent.hasOwnProperty("description")&&currentEvent.description!==""){
                        var contentp = '<tr><th colspan="2">Description :</th></tr>\n\
                                        <tr><td colspan="2"><div style="height:150px;width:300px;overflow: auto;font-size:small;">'+currentEvent.description+'</div></td></tr></table>';
                        content = content+contentp;
                }

                if($(".fh_dao_fiche_modal").length){
		    var mymodal="#fh_dao_fiche_modal";
		    $(".fh_dao_fiche_modal").each(function(){
			if($(this).is(":visible"))
			{
			    mymodal=$(this).attr("id");
			    mymodal="#"+mymodal;
			}
		    });
                    $(this).webuiPopover({
                        title: title,
                        content: content,
                        container: mymodal,
                        width:350,
                        height:265,
                        animation:'pop',
                        backdrop:false,
                        cache:false,
                        closeable:true,
                        trigger:'manual'
                    });
                }else{
                    $(this).webuiPopover({
                        title: title,
                        content: content,
                        container: "body",
                        width:350,
                        height:265,
                        animation:'pop',
                        backdrop:true,
                        cache:false,
                        closeable:true,
                        trigger:'manual'
                    });
                }
                $(this).webuiPopover("show");		
        }
        });
	
        function convert_date_fr(date){
            if(date!= ''){
                var tab = date.split("-");
                var date_en = tab[2]+'/'+tab[1]+'/'+tab[0];
                return date_en;
            }else{
                return date;
            }
        }
    });
</script>

		