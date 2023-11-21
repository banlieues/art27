<?php $autorisationManager = \Config\Services::autorisationModel();?>

<?php


$is_autorise=true;

if(!empty(trim($autorisation))&&!$autorisationManager->is_autorise($autorisation))
{
    $is_autorise=false;
}

?>

<?php $id_name="selectfiltre".rand(5,15).$index;?>

<select 
    id="<?=$id_name?>"
    class="form-select form-control" 
    <?php if(!$is_autorise):?> disabled <?php endif;?>
       <?php if($multiple):?>multiple<?php endif;?>
        name="<?php echo trim($index)?><?php if($multiple):?>[]<?php endif;?>">
      
        <?php if(!$multiple):?>
            <option value="0">Choisir</Option>
        <?php endif;?>
            <?php $values=explode(",",$value)?>
    <?php foreach($selects as $select):?>
        <option <?php if(!empty($values) && in_array($select->key,$values)):?>selected<?php endif;?> value="<?=$select->key?>"><?=$select->label?></option>
    <?php endforeach;?>    
</select>


<?php if($is_autorise):?>
<script>
$(document).ready( function () {
  
    
	//$(".selectfiltre").selectpicker();
   
   $("#<?=$id_name?>").chosen({
            disable_search_threshold: 10,
            search_contains: true,
            no_results_text: "Pas de résultats pour ",
            placeholder_text_multiple: "Vous pouvez choisir plusieurs éléments",
            width: "100%"
         }); 
    
} );
</script>
<?php endif;?>
