<div class="c_liste_operateur">
<select style="max-width:140px"  class="select_r_chosen operateur" name="operateur<?php if(!is_null($i)): echo "_##$i"; endif;?>">
      <?php //if( $type_input!="select"):?>
	<option <?php if($operator_select=="egal"):?>selected<?php endif;?> value="egal">Egal à</option>
  
    
	<option <?php if($operator_select=="different"):?>selected<?php endif;?> value="different">Différent de</option>
     <?php //if( $type_input!="select"):?>
    <option <?php if($operator_select=="contient"):?>selected<?php endif;?> value="contient">Contient</option>
    <option <?php if($operator_select=="contient_pas"):?>selected<?php endif;?> value="contient_pas">Ne contient pas</option>
    <?php //endif;?>
    <option <?php if($operator_select=="vide"):?>selected<?php endif;?> value="vide">Est vide</option>
    <option <?php if($operator_select=="vide_pas"):?>selected<?php endif;?> value="vide_pas">N'est pas vide</option>
    <?php if( $type_input!="select"):?>
    <option <?php if($operator_select=="superieur"):?>selected<?php endif;?> value="superieur">Supérieur à</option>
    <option <?php if($operator_select=="inferieur"):?>selected<?php endif;?> value="inferieur">Inférieur à</option>
  <!--   <option value="entre">Entre</option> -->
<!--    <option value="commence">Commence par</option>
    <option value="commence_pas">Ne commence pas par</option>
    <option value="termine">Termine par</option>
    <option value="termine_pas">Ne se termine pas par</option>-->
    <?php endif;?>
</select>

</div>