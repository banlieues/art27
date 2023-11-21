<form id="questionForm" method="POST" 
    <?php if(!empty($question->id_question)):?>
        action="<?php echo base_url("enquete/question/$question->id_question/update");?>"
    <?php else:?>
        action="<?php echo base_url('enquete/question/new');?>"
    <?php endif;?>
    >

    <?php echo view('Enquete\question-form');?>
    
</form>



