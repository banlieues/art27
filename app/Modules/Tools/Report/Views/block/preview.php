<div id="blockPreview<?php echo $id_file;?>" class="p-4">
</div>

<script type="text/javascript">

    $("#blockPreview<?php echo $id_file;?>").ready(function(){
        get_iframe('#blockPreview<?php echo $id_file;?>', "<?php echo $url;?>");
    });

</script>