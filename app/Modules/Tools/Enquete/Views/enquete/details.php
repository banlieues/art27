<div class="container">
    <nav>
        <div class="nav nav-tabs" role="tablist">
            <a class="nav-item nav-link active text-body" id="nav-form-tab" data-bs-toggle="tab" href="#nav-form" role="tab"     
                aria-controls="nav-form" aria-selected="true"
                > 
                Formulaire 
            </a>
            <a class="nav-item nav-link text-body" id="nav-prevfr-tab" data-bs-toggle="tab" href="#nav-prevfr" role="tab" 
                aria-controls="nav-prevfr" aria-selected="false"
                >
                Aperçu FR
            </a>
            <a class="nav-item nav-link text-body" id="nav-prevnl-tab" data-bs-toggle="tab" href="#nav-prevnl" role="tab" 
                aria-controls="nav-prevnl" aria-selected="false"
                >
                Aperçu NL
            </a>
        </div>
    </nav>
    <div class="tab-content">
        <div class="tab-pane fade show active py-4" id="nav-form" role="tabpanel" aria-labelledby="nav-form-tab">
            <form id="enqueteUpdateForm" method="post" action="<?php echo base_url('enquete/form/update/' . $enquete->id_enquete);?>">
                <div class="form-group row">
                    <label for="enquete_label" class="col-2 col-form-label">Nom</label>
                    <div class="col-10">
                        <input type="text" class="form-control disabled" name="enquete_label" id="enquete_label" value="<?php if(!empty($enquete->path_fr)) echo $enquete->path_fr;?>" disabled/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_enquete" class="col-2 col-form-label pt-0">Questions</label>
                    <div class="col-10">
                        <?php foreach($question_list as $question):?>
                            <div class="form-check">
                                <input class="form-check-input formQuestion" type="checkbox" name="ids_question[]" 
                                    value="<?php echo $question->id_question;?>" id="formQuestion<?php echo $question->id_question;?>"
                                    <?php if(!empty($enquete->ids_question)):?>
                                        <?php foreach($enquete->ids_question as $id_q):?>
                                            <?php if($id_q == $question->id_question):?> checked <?php endif;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                    >
                                <label class="form-check-label" for="formQuestion<?php echo $question->id_question;?>">
                                    <?php echo $question->num_question;?> - <?php echo $question->question_fr;?>
                                </label>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>            
            </div>
            <div class="tab-pane fade" id="nav-prevfr" role="tabpanel" aria-labelledby="nav-prevfr-tab">
                <div class="alert alert-warning m-4"> 
                    Ceci est l'aperçu de l'enquête de satisfaction en français. Vous pouvez tester les questions, rien ne sera enregistré.
                </div> 
                <iframe class="w-100" src="<?php echo $enquete_iframe['fr'];?>" scrolling="no" frameborder="0"></iframe>
            </div>
            <div class="tab-pane fade" id="nav-prevnl" role="tabpanel" aria-labelledby="nav-prevnl-tab">
                <div class="alert alert-warning m-4"> 
                    Ceci est l'aperçu de l'enquête de satisfaction en néerlandais. Vous pouvez tester les questions, rien ne sera enregistré.
                </div> 
                <iframe class="w-100" src="<?php echo $enquete_iframe['nl'];?>" scrolling="no" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

  




