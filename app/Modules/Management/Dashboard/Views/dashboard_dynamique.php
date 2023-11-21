<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $ldashboard = \Config\Services::ldashboard();?>

<style>
  .nav_dasboard>a{
      color:red !important;
  }

  th{
    font-size: 14px !important;
  }

  td{
    font-size: 14px !important;
  }

</style>


<?php $id_permis=array();?>
<?php     if($autorisationManager->is_autorise("dashboard_admin")): $id_permis[]=session("loggedUserId") ;endif;?>
<?php //$id_permis=array(2,23,24,112);?>

<div style="margin-bottom: 20px;" class='entities c_rubrique'>

  
<div id="menu_dashboard" style="margin-bottom:0 !important;" class="">

      <?php $onglets=$ldashboard->get_onglet($id_user);?>
    




    <div class='sticky_button' style='z-index:888; background-color:white !important;'> 

          <ul style="background-color: #eee" id="nav_onglet" class="nav nav-tabs">
                  <?php $onglets=$ldashboard->get_onglet($id_user);?>

                  <?php if(isset($onglets[0]->id_user)):?>
                        <?php foreach($onglets as $onglet):?>
                          <li role="presentation"  <?php if($id_onglet==$onglet->id_user_table_onglet):?>class="nav-item active"<?php else:?>class="nav-item"<?php endif;?>>
                            <a  
                            
                                <?php if($id_onglet==$onglet->id_user_table_onglet):?> style="background-color:white;" <?php endif;?> 
                                  class="card-information nav-link" 
                                  data-index="<?php echo $onglet->id_user_table_onglet;?>" 
                                  href="<?php echo base_url();?>/dashboard/index/<?php echo $id_user;?>/<?php echo $onglet->id_user_table_onglet;?>"
                              >
                                  <?php echo $onglet->nom;?>
                              </a>
                          </li>
                      <?php endforeach;?>


                <?php endif;?>
                
        
        
          </ul>


          <ul class="nav p-2 nav-pills nav-fill bg-light">
                <li class="nav-item">
                <?php if(in_array(session("loggedUserId"),$id_permis)):?>  
                                          <form class="navbar-form navbar-left">
                                                <div class="form-group">
                                              <?php $utilisateurs=$ldashboard->get_utilisateur(); ?>
                                              <select class="form-control selectfiltreuser">
                                            <?php foreach($utilisateurs as $utilisateur):?>
                                            <option <?php if($id_user==$utilisateur->id):?>selected<?php endif;?> value="<?php echo base_url();?>/dashboard/index/<?php echo $utilisateur->id;?>"><?php echo $utilisateur->label;?></option>
                                            <?php endforeach;?>
                                              </select>
                                              
                                                </div>
                                              
                                          </form>
                                    <?php endif;?>
                </li>
                <li class="nav-item px-1">
                <?php if(isset($is_nav_pannel)&&$is_nav_pannel):?>
                        <form class="navbar-form navbar-left">
                          <div class="form-group">
                              <?php $pannels=$ldashboard->get_pannel($id_user,$id_onglet); ?>
                                  <select style="max-width:200px !important" class="form-control go_pannel_go">
                                        <option>Aller vers table…</option>
                                      <?php foreach($pannels as $pannel):?>
                                        <option style="background-color:<?php echo $pannel->color_background;?>; color:<?php echo $pannel->color_police;?>; border-bottom: 1px solid #ecf0f1 !important" value='r<?php echo $pannel->id_user_table;?>'><?php echo $pannel->nom;?></option>
                                      <?php endforeach;?>
                                  </select>
                          </div>
                        </form>
                      <?php endif;?>
                </li>
                <?php if(!is_null($id_onglet)):?>
                 
                 <?php if(isset($is_nav_pannel)&&$is_nav_pannel):?>
                 <li class="nav-item px-1"><a  class="nav-link" id="expand_all" href="#"><i class="fas fa-expand-alt"></i>100%</a></lI>
                 <li class="nav-item px-1">
                    <a class="nav-link" id="compress_all" href="#">
                        <?php echo fontawesome('down-left-and-up-right-to-center');?>
                        50%
                    </a>
                </li>
                 <?php endif;?>

                  <li class="nav-item px-1"><a class="nav-link"  href="<?php echo base_url();?>/dashboard/modifier_onglet/<?php echo $id_user;?>/<?php echo $id_onglet;?>">Modifier onglet</a></lI>
 
                <li class="nav-item px-1"><a class="nav-link"  class="mode_deplace" href="<?php echo base_url();?>/dashboard/deplacer/<?php echo $id_user;?>/<?php echo $id_onglet;?>">Ordre des tables et champs</a></lI>
                <li class="nav-item px-1"><a  class="nav-link" href="<?php echo base_url();?>/dashboard/ajouter/<?php echo $id_user;?>/<?php echo $id_onglet;?>">Ajouter table</a></li>
                 
               <?php endif;?>

               <li class='nav-item px-1'><a class='nav-link' href="<?php echo base_url();?>/dashboard/ajouter_onglet/<?php echo $id_user;?>">Ajouter un onglet</a></li>

          </ul>

          
    </div>

    
    <div style="padding:10px !important; background-color: transparent !important;" class="card-body">
	<?php if(session("loggedUserId")===$id_user||in_array(session("loggedUserId"),$id_permis)):?>
	    <?php echo $view; ?>
	<?php else:?>
	<div class="text-center">
	<p>Vous ne pouvez pas accéder à ce tableau de bord</p>
	</div>
	<?php endif;?>
    </div>
</div>
</div>

<?=view($viewpath."dash_js")?>

<?php $this->endSection(); ?>