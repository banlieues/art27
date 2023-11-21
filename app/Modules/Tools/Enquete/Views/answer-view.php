<table class="table table-striped w-100">
    <tbody>
        <?php foreach($questions as $q):?>
            <?php if(!empty($q->question_fr) && isset($q->answer)):?>
                    <tr>
                        <td> <?php echo $q->question_fr;?> </td>
                        <td> <?php echo $q->answer;?> </td>
                    </tr>
            <?php endif;?>
        <?php endforeach;?>
    </tbody>
</table>
