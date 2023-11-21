<div class="container p-4">
    <div class="jumbotron jumbotron-fluid p-4 mb-4">
        <div class="text-center focus_box">
            <span> 
                Homegrade - <?php echo t("Enquête de satisfaction", $namespace, ['lang' => $lang]);?>
            </span>
        </div>
        <p> 
            <?php echo t("
                Afin d'améliorer notre service, nous réalisons une enquête de satisfaction auprès des personnes qui ont fait appel à nos services. Nous vous remercions d'avance pour votre temps, vos réponses sont essentielles et précieuses pour notre évaluation.
            ", $namespace, ['lang' => $lang, 'ref' => 'Intro_enquete_de_satisfaction']);?> 
        </p>
    </div>
    <?php if(!empty($validation) && !empty($validation->listErrors())):?>
        <div id="formErrorValues" class="alert alert-warning my-4"> 
            <?php echo $validation->listErrors();?>
        </div>
    <?php endif;?>
    <div id="formMissingValues" class="alert alert-warning my-4" hidden> 
        <?php echo t("Certaines réponses sont manquantes.", $namespace, ['lang' => $lang]);?> 
    </div>
    
    <?php foreach($questions as $question):?>
        <?php echo $question->preview->$lang;?>
    <?php endforeach;?>

</div>



