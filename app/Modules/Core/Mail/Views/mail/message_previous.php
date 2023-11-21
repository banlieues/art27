<br>
<br>
<?php if(isset($bootstrap) && strpos($bootstrap, '3') != false):?>
    <blockquote style="margin-left: 10px; padding-left: 10px; border-left: 1px solid #ced4da; font-size: 1em;">
<?php else:?>
    <blockquote style="margin-left: 10px; padding-left: 10px; border-left: 1px solid #ced4da;">
<?php endif;?>
    Le <?php echo $send_datetime;?>, 
    <?php echo htmlentities($sender_old->name . ' ' . $sender_old->lastname . ' <' . $sender_old->email . '> ');?>
    a écrit : <br><br>
    <?php echo $message;?>
</blockquote>