<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>
    <?php if(in_array(base_url(), [$prod_url, "$prod_url/"])):?> CRM
    <?php elseif(in_array(base_url(), [$dev_url, "$dev_url/"])):?> DEV
    <?php else:?> LOC
    <?php endif;?>
    -
    <?php if(!empty($titleView)) :?>
        <?php echo strip_tags($titleView);?>
    <?php else :?>
        <?php $this->renderSection('title');?>
    <?php endif;?>
</title>

<meta name="author" content="">
<meta name="description" content="<?php echo $themes->main->name;?> CRM">

<link rel="canonical" href="<?php echo base_url(); ?>">
<link rel="icon" href="<?php echo base_url('images/icon/favicon.ico'); ?>" />
<link rel="author" href="<?php echo base_url('includes/humans.txt'); ?>" />
<link rel="stylesheet" href="<?php echo base_url('node_modules/bootstrap/dist/css/bootstrap.min.css'); ?>">
<!-- <link rel="stylesheet" href="<?php //echo base_url('node_modules/@fortawesome/fontawesome-free/css/all.min.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo base_url('styles/sidebar.css'); ?>">



<link rel="stylesheet" href="<?php echo base_url('node_modules/jquery-ui/dist/themes/base/jquery-ui.min.css'); ?>">
<!--<link rel="stylesheet" href="<?php //echo base_url('public/fullcalendar/lib/main.min.css'); ?>">-->
<link rel="stylesheet" href="<?php echo base_url('node_modules/flatpickr/dist/flatpickr.min.css');?>">

<link rel="stylesheet" href="<?php echo base_url('node_modules/chosen-js/chosen.min.css');?>">
<!-- <link rel="stylesheet" href="<?php //echo base_url("node_modules/bootstrap-select/dist/css/bootstrap-select.min.css"); ?>"/> -->
<!--<link rel="stylesheet" href="<?php //echo base_url("public/bootstrap-select/docs/docs/dist/css/custom.css"); ?>"/> -->
<link rel="stylesheet" href="<?php echo base_url('node_modules/dropzone/dist/dropzone.css')?>" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url('node_modules/@dashboardcode/bsmultiselect/dist/css/BsMultiSelect.min.css'); ?>">

<!-- <link rel="stylesheet" href="<?php echo base_url('node_modules/fullcalendar-scheduler/lib/main.css'); ?>"/> -->

<!-- <link rel="stylesheet" href="<?php //echo base_url('node_modules/fullcalendar/css/fullcalendar.min.css')?>" /> -->
<!-- <link rel="stylesheet" href="<?php //echo base_url('node_modules/fullcalendar/css/fullcalendar.print.css')?>" media='print' /> -->

<link rel="stylesheet" href="<?php echo base_url('node_modules/leaflet/dist/leaflet.css'); ?>"/>
<link rel="stylesheet" href="<?php echo base_url('node_modules/leaflet.markercluster/dist/MarkerCluster.css'); ?>"/>
<link rel="stylesheet" href="<?php echo base_url('node_modules/leaflet.markercluster/dist/MarkerCluster.Default.css'); ?>"/>

<link rel="stylesheet" href="<?php echo base_url('node_modules/summernote/dist/summernote-lite.min.css')?>">
<link rel="stylesheet" href="<?php echo base_url('styles/summernote_custom.css'); ?>">

<link rel="stylesheet" href="<?php echo base_url('styles/sticky.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('styles/style.css'); ?>">

<link href="<?php echo base_url("node_modules/bootstrap5-toggle/css/bootstrap5-toggle.min.css");?>" rel="stylesheet">

<!-- fullcalendar /> -->
<link href="<?php echo base_url('fullcalendar/css/fullcalendar.css')?>" rel='stylesheet' />
<link href="<?php echo base_url('fullcalendar/css/fullcalendar.print.css')?>" rel='stylesheet' media='print' />
