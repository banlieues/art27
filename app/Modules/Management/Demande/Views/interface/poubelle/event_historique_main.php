<div class="panel panel000 panel-default" style="padding: 5px !important; margin-top:10px">
    <div style="margin:0 !important" class="panel-heading">
	    
	  
		<div class="row">
		   <div class="col-md-11">
	
		       		       <div class="dao_sous_panel_default" style="padding-left:10px; ">
			    			       			     		       </div>
		   </div>
		    
		    
		    <div style="text-align:right;" class="col-md-1">
			 <a style="text-align:right!important; color:grey" class="tab_ajax_dao_2" href=""><i class="fa fa-repeat fa-1x"></i></a>

					   
		    </div>
		</div>
    
    </div>
    
    <div class="panel panel1 panel-default">
        <?php if(empty($events)):?>
        <div style='margin:20px; text-align:center'><h3> Pas d'event associé à cette personne!</h3></div>
        <?php else:?>
        
        <?php print_r($events);?>
        <table>
            
            
        </table>
        <?php endif;?>
        
    </div>
    
</div>