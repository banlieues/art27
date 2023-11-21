<?php $this->extend('Layout\tamo/index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Tableau de bord
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub_title'); ?>
    Tableau de bord
<?php $this->endSection(); ?>

<?php $this->section('css_injected');?>
    <link rel="stylesheet" href="<?php echo base_url('node_modules/leaflet/dist/leaflet.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('node_modules/leaflet.markercluster/dist/MarkerCluster.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('node_modules/leaflet.markercluster/dist/MarkerCluster.Default.css'); ?>"/>
<?php $this->endSection();?>

<?php $this->section('script_foot_injected');?>
    <script src="<?php echo base_url('node_modules/leaflet/dist/leaflet.js'); ?>"></script>
    <script src="<?php echo base_url('node_modules/leaflet.markercluster/dist/leaflet.markercluster.js'); ?>"></script>
    <?php echo view('Mapping\js/openstreetmap');?>
<?php $this->endSection();?>

<!-- --------------------------------------------------------------------------- -->

<?php $this->section("body"); ?>

<?php $this->endSection(); ?>
