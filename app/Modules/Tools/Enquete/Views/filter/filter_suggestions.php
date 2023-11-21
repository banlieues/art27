<?php $suggestions = session('filter') && isset(session('filter')->suggestions) && isset(session('filter')->suggestions->value) ? session('filter')->suggestions->value : null;?>

<div class="row mb-2">
    <label for="enqueteSuggestions" class="col-form-label col-4"> <strong> Suggestions </strong> </label>
    <div class="col-8">
        <input type="text" class="form-control" name="suggestions" id="enqueteSuggestions"
            <?php if(!empty($suggestions)):?> 
                highlighted value="<?php echo $suggestions;?>" 
            <?php endif;?>
        />
    </div>
</div>