<!DOCTYPE html>

<html lang="fr">
    
<head>

    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <title>
        Homegrade - <?php echo t("Enquête de satisfaction", $namespace, ['lang'=>$lang, 'withButton'=>false]); ?>
    </title>

    <link rel="icon" href="<?php echo base_url('images/favicon.ico'); ?>" />
    <link rel="author" href="<?php echo base_url('includes/humans.txt'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('node_modules/bootstrap/dist/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('node_modules/@fortawesome/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('node_modules/flatpickr/dist/flatpickr.min.js');?>">
    <link rel="stylesheet" href="<?php echo base_url('styles/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('node_modules/jquery-ui/dist/themes/base/jquery-ui.min.css'); ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url("styles/homegrade.brussels.css"); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("styles/homegrade.brussels_custom.css"); ?>" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,500,700" rel="stylesheet">
    
    <script src="<?php echo base_url('node_modules/jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('node_modules/jquery-ui/dist/jquery-ui.min.js'); ?>"></script>
</head>
<body>

    <div class="container p-4">
        <div class="jumbotron jumbotron-fluid p-4 mb-4">
            <div class="text-center focus_box">
                <span> 
                    Homegrade - <?php echo t("Enquête de satisfaction", $namespace, ['lang' => $lang]);?>
                </span>
            </div>
        </div>
        
        <div class="alert alert-success my-4"> 
            <?php //echo lang('thanks');?> 
            <?php echo t("
                Nous vous remercions d'avoir pris le temps de répondre à notre questionnaire.
            ", $namespace, ['lang'=>$lang, 'withButton'=>false, 'ref' => 'Merci_enquete_de_satisfaction']);?> 
        </div>

    </div>
   
</body>

</html>




  




