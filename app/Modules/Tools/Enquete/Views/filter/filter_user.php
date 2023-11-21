<?php $id_user = session('filter') && isset(session('filter')->id_user) ? session('filter')->id_user : null;?>

<div class="form-group row">
    <label for="enqueteIdUser" class="col-form-label col-3"> <strong> User en charge de la demande </strong> </label>
    <div class="col-9">
        <select name="id_user" id="enqueteIdUser"
            class="form-control <?php if($id_user!=session('loggedUserId')):?> highlighted <?php endif;?>"
            >
            <option class="form-control" value=""> Choisir un filtre </option>
            <?php foreach ($user_list as $user):?>
                <option class="form-control" value="<?php echo $user->id_user?>"
                    <?php if($id_user==$user->id_user):?> selected <?php endif;?>
                >
                    <?php echo $user->user_fullname;?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>