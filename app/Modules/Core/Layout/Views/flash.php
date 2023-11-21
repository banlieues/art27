<?php
/**
 * Flash Switch v0.1 by djphil (CC-BY-NC-SA 4.0)
 * A simple array swith to display flash message
**/
?>

<div id="flashContainer">
    <?php $flash = session()->getFlashdata();?>
    <?php $keys = ['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'light', 'dark'];?>

    <?php if(!empty((array) $flash) && count($flash)>0):?>
        <?php foreach($flash as $key => $value):?>
            <?php if(!in_array($key, $keys)) $key = 'primary';?>
            
            <?php echo view('Layout\flash_one', ['key' => $key, 'value' => $value]);?>

            <!-- <div class="alert alert-<?php //echo $key;?> alert-dismissible fade show" role="alert">
                <i class="<?php //echo icon($key);?>"></i>
                <?php //echo $value;?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div> -->

        <?php endforeach;?>
    <?php endif;?>
</div>

