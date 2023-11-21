<select class="btn btn-sm border mx-1" onchange="language_set($(this).val());">
    <option disabled> 
        <?php echo t("Langue", $namespace, ['withButton'=>false]);?> 
    </option>
    <option <?php if($locale=='fr'):?> selected <?php endif;?> value="fr"> 
        FR 
    </option>
    <option <?php if($locale=='nl'):?> selected <?php endif;?> value="nl"> 
        NL 
    </option>
    <option <?php if($locale=='bi'):?> selected <?php endif;?> value="bi"> 
        FR/NL
    </option>
</select>