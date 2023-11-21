<?php if(!empty(session('filter'))):?> 
    <div class="alert alert-warning mt-0 mx-0">
        <?php echo trim($filter_active); ?> 
    </div>
<?php endif;?>
