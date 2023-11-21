<div class="col-sm-10">
    <input type="file" class="form-control" name="attachment_upload[]" multiple onchange="attachment_plusminus_process(this);"/>
</div>
<div class="col-sm-2">
    <?php echo view('Mail\mail/form/button_row_delete');?>
    <?php echo view('Mail\mail/form/button_row_add');?>
</div>