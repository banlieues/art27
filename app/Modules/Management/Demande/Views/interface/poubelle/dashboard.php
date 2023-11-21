<?php $this->load->view("interface/dashboard_js"); ?>


<?php $this->load->view("interface/choice_module"); ?>
<?php $this->load->view("interface/search_all"); ?>



<div style="margin-bottom: 20px; background-color: transparent" class='entities c_rubrique'>

  
<div style="margin-bottom:0 !important; margin:10px; border-color: MEDIUMPURPLE !important; background-color: transparent" class="panel panel-info">
    <div style='background-color: MEDIUMPURPLE !important; border-color: MEDIUMPURPLE !important' class="panel-heading">
	<div class='row'>
	    <div style='font-size:24px; min-height: 40px' class='col-lg-6'>
	    <?php echo icon("bureau");?> Tableau de bord
	   
	    </div>

	</div>
    </div>

    <div style="padding:0 !important; background-color: transparent !important" class="panel-body">
  <!-- Nav tabs -->
  <ul style="z-index: 500 !important; background-color: #eaeaea" id="nav_dashboard" class="nav nav-tabs" role="tablist">
         <li role="presentation" class="active"><a style="cursor:pointer" class="" href="#tabbureau" aria-controls="tabbureau" role="tab" data-toggle="tab"><i class="fa fa-desktop fa-1x"></i> Mon bureau</a> </li>
 
    <li role="presentation"><a  class="reload_aja is_empty bt_charge_pot" href="#tabpot" aria-controls="tabpot" role="tab" data-toggle="tab"><i class="fa fa-archive"></i> Pot commun</a></li>
    <?php if(
	    $this->fh_dao->get_autorisation("infop1")||
	    $this->fh_dao->get_autorisation("visip1")||
	    $this->fh_dao->get_autorisation("accompp1")||
	    $this->fh_dao->get_autorisation("pretvp1")||
	    $this->fh_dao->get_autorisation("facip2")||
	    $this->fh_dao->get_autorisation("petitcopp2")||
	    $this->fh_dao->get_autorisation("beexp2")||
	    $this->fh_dao->get_autorisation("allocp2")||
	    $this->fh_dao->get_autorisation("ptpatrip3")||
	    $this->fh_dao->get_autorisation("locaprop3")||
	    $this->fh_dao->get_autorisation("acousp3")||
	    $this->fh_dao->get_autorisation("enrrevp3")||
	      $this->fh_dao->get_autorisation("patrip4")||
	    $this->fh_dao->get_autorisation("ptinterventionp4") 
	    ):
	?>
  
    <li role="presentation" ><a class="reload_aja is_empty bt_charge_equipe" href="#tabequipe" aria-controls="tabequipe" role="tab" data-toggle="tab"><i class="fa fa-user-o"></i> Equipes</a></li>    
  <?php endif;?>
  </ul>

  <!-- Tab panes -->
  <div style="background-color: transparent !important"  class="tab-content">
     <div style="background-color: transparent !important" role="tabpanel" class="tab-pane active tab-pane-list" id="tabbureau"><?php $this->load->view("interface/dash_board_bureau");?></div>  

     <div role="tabpanel" class="tab-pane tab-pane-list" id="tabpot"><?php //$this->load->view("interface/dash_board_pot");?></div> 
       <?php if(
	    $this->fh_dao->get_autorisation("infop1")||
	    $this->fh_dao->get_autorisation("visip1")||
	    $this->fh_dao->get_autorisation("accompp1")||
	    $this->fh_dao->get_autorisation("pretvp1")||
	    $this->fh_dao->get_autorisation("facip2")||
	    $this->fh_dao->get_autorisation("petitcopp2")||
	    $this->fh_dao->get_autorisation("beexp2")||
	    $this->fh_dao->get_autorisation("allocp2")||
	    $this->fh_dao->get_autorisation("ptpatrip3")||
	    $this->fh_dao->get_autorisation("locaprop3")||
	    $this->fh_dao->get_autorisation("acousp3")||
	    $this->fh_dao->get_autorisation("enrrevp3")||
	     $this->fh_dao->get_autorisation("patrip4")||
	    $this->fh_dao->get_autorisation("ptinterventionp4")  
	    ):
	?>
    <div role="tabpanel" class="tab-pane  tab-pane-list" id="tabequipe"><?php //$this->load->view("interface/dash_board_equipe");?></div>  
   <?php endif;?>
   

  </div>

</div>
</div>
</div>