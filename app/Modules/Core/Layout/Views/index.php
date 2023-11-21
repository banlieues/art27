<?php
  /**
   * Empêcher la mise en cache des pages avec PHP
   *
   * La fonction doit-être appellée avant toute balise HTML,
   * espace blanc, echo(), print()...
   *
   * @param : void
   * @return : void
   */
  /*function empecherLaMiseEnCache()
  {
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-cache, must-revalidate');
  }*/
?>
<!doctype html>

<html lang="<?php echo service('request')->getLocale(); ?>" class="h-100">

<head>
    <?php echo view('Layout\head');?>
    <?php $this->renderSection('css_injected');?>
    <?php $this->renderSection('script_head_injected');?>
</head>

<body>
    
<?php echo view("Layout\script_head"); ?>

<!-- Champs qui se remplit sur un ajax sur la plage est un succes -->
<input type="hidden" class="ajax_charge" value="">

<!-- FOR ADMINISTRATOR ONLY -->
<?php if (session()->has('loggedUserId') && session('loggedUserRoleId') ==1): ?>

    <?php echo view("Layout\ban-modal"); ?>
    
    <div <?php if(empty($no_menu)):?> id="wrapper" <?php endif;?>>
        <?php if(empty($no_menu)):?>
            <div id="sidebar-wrapper"
                class="
                    bg-<?php echo !empty($themes->sidebar->color) ? $themes->sidebar->color : 'black';?>
                    bg-gradient-off sidebar
                    <?php if(base_url()=='https://dev.crm.homegrade.banlieues.be' || base_url()=='https://dev.crm.homegrade.banlieues.be/'):?>
                        border border-4 border-danger
                    <?php endif;?>
                "
                >
                <?php $sidebar = new Layout\Libraries\SidebarLibrary(); echo $sidebar->getMenu($context ?? null, $context_sub ?? null); ?>

            </div>
        <?php endif?>
        <div
            <?php if(empty($no_menu)):?> id="page-content-wrapper" class="main" <?php endif;?>
            >
            <?php if(empty($no_menu)):?>
                <header class="sticky_header bg-white pb-2">
                    <?php if(in_array(base_url(), [$dev_url, "$dev_url/"])):?>
                        <div class="d-flex bg-danger p-2">
                            <small> Ceci est la version développement du CRM depuis l'url : <b> <?php echo base_url();?> </b> </small>
                        </div>
                    <?php elseif(!in_array(base_url(), [$prod_url, "$prod_url/"])):?>
                        <div class="d-flex bg-primary p-2">
                            <small> Ceci est la version locale du CRM depuis l'url : <b> <?php echo base_url();?> </b> </small>
                        </div>
                    <?php endif;?>

                    <!-- Navbar -->
                    <?php echo $this->include('Layout\navbar'); ?>
                    
                    <!-- Navbarsub -->
                    <form id="searchOrderForm" class="form_with_order" method="get"></form>
                    <?php $this->renderSection('navbarsub');?>
                </header>
            <?php endif;?>
            
            <?php echo $this->include('Layout\main'); ?>

            <?php if(empty($no_menu)):?>

                <!-- Footer -->
                <?php echo $this->include('Layout\footer'); ?>

            <?php endif;?>
        </div>
    </div>

<!-- FOR UTILISATOR ONLY -->
<?php elseif((session()->has('loggedUserId') && session('loggedUserRoleId')==2)): ?>
    <div class="main">
        <?php if(empty($no_menu)):?>
            <header class="bg-white mb-2">

                <div class="d-flex bg-danger px-2">
                    <small> Ceci est la version développement du CRM depuis l'url : <b> <?php echo base_url();?> </b> </small>
                </div>

                <!-- Navbar -->
                <?php echo $this->include('Layout\navbar'); ?>

            </header>
        <?php endif;?>

        <?php echo $this->include('Layout\main'); ?>

        <?php if(!isset($no_menu)||(isset($no_menu)&&!$no_menu)):?>
        
            <!-- Footer -->
            <?php echo $this->include('Layout\footer'); ?>

        <?php endif;?>
    </div>

<!-- FOR LOGOUT -->
<?php else:?>
    <div class="main">
    
        <?php echo $this->include('Layout\main'); ?>

    </div>
<?php endif; ?>

<!-- Modal -->
<?php echo view('Layout\modal');?>
<?php //echo view('Layout\loading');?>

<!-- Script src -->
<?php echo view('Layout\script_foot');?>

<?php echo $this->renderSection('script_foot_injected'); ?>

</body>

</html>
