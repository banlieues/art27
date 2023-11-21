<!DOCTYPE html>

<html lang="fr">
    
<head>

    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <title>
        Homegrade - <?php echo t("Enquête de satisfaction", $namespace, ['lang'=>$lang, 'withButton'=>false]); ?>
    </title>

    <link rel="canonical" href="<?php echo base_url(); ?>">
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
    <?php if(isset($token) && isset($id_demande) && isset($id_contact_profil)):?>
    
        <form class="needs-validation m-4 p-4" method="POST" action="<?php echo base_url('enquete/external/save/' . $token . '/' . $id_demande . '/' . $id_contact_profil . '/' . $lang);?>">

            <?php echo $preview[$lang];?>

            <div class="row justify-content-center">
                <button type="button" class="btn btn-success" onclick="js_enquete_submit_with_validation(this)"> 
                    <?php //echo lang('envoyer_vos_resultats');?> 
                    <?php echo t("Envoyer vos résultats", $namespace, ['lang'=>$lang, 'withButton'=>false]);?> 
                </button>
            </div>
        </form>
    
    <?php else:?>
    
        <?php echo $preview[$lang];?>
    
    <?php endif;?>
    
    <?php echo view('Enquete\js/js_external.php');?>
    
</body>

</html>




  




