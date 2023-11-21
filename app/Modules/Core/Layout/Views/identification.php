<!doctype html>
<html lang="<?php echo service('request')->getLocale(); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> CRM - <?php echo $title . ' ' . $subtitle; ?> </title>
    <meta name="author" content="">
    <meta name="description" content="<?php echo $themes->main->name;?> CRM">
    <link rel="canonical" href="<?php echo base_url(); ?>">
    <link rel="icon" href="<?php echo base_url('images/icon/favicon.ico'); ?>" />
    <link rel="author" href="<?php echo base_url('includes/humans.txt'); ?>" />
    <link rel="author" href="<?php echo base_url('includes/humans.txt'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('node_modules/bootstrap/dist/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('node_modules/@fortawesome/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('styles/style.css'); ?>">

    <script src="<?php echo base_url('node_modules/jquery/dist/jquery.min.js'); ?>" crossorigin="anonymous"></script>
</head>

<body class="bg-light">

<?php if(in_array(base_url(), [$dev_url, "$dev_url/"])):?>
    <div class="fixed-top w-100 bg-danger p-2">
        <small> Ceci est la version développement du CRM depuis l'url : <b> <?php echo base_url();?> </b> </small>
    </div>
<?php elseif(!in_array(base_url(), [$prod_url, "$prod_url/"])):?>
    <div class="fixed-top w-100 bg-primary p-2">
        <small> Ceci est la version locale du CRM depuis l'url : <b> <?php echo base_url();?> </b> </small>
    </div>
<?php endif;?>

<main class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 p-4">
            
                <?php echo $this->renderSection('body'); ?>
            </div>
        </div>
    </div>
</main>

<?php if (COOKIES_CONSENT): ?>
<footer class="footer-off bg-white-off bg-gradient-off">
    <?php echo $this->include('Layout\cookies'); ?>
</footer>
<?php endif; ?>

<script src="<?php echo base_url('node_modules/jquery/dist/jquery.min.js'); ?>" crossorigin="anonymous"></script>
<script src="<?php echo base_url('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>

</body>

</html>
