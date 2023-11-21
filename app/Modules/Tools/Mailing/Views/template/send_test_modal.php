<form id="sendingEmailTest" method="post" action="<?php echo base_url('mailing/send/test');?>">

    <input type="hidden" name="ref" value="<?php echo $ref;?>"/>

    <div class="mb-2 row">
        <label class="col-4"> Demande </label>
        <div class="col-8">
            <input type="text" name="id_demande" class="form-control"/>
            <!-- <input type="hidden" name="id_demande"/> -->
        </div>
    </div>
    
</form>