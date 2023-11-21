<form id="filterForm" action="<?php echo $target->url;?>" class="p-2" method="post">
    <?php echo view('Enquete\filter/filter_date');?> </div>
    <?php echo view('Enquete\filter/filter_person');?> </div>
    <?php echo view('Enquete\filter/filter_language');?> </div>
    <?php if(isset($data_filter->user_list)):?> 
        <?php echo view('Enquete\filter/filter_user');?> 
    <?php endif;?>  
    <?php if($target->name=='send'):?> 
        <?php echo view('Enquete\filter/filter_statut_answer');?>
    <?php endif;?>
    <?php echo view('Enquete\filter/filter_type_demande');?>
    <?php echo view('Enquete\filter/filter_question');?>
    <?php echo view('Enquete\filter/filter_suggestions');?>
</form>

    
    