<div class="mt-4">
    <?php if(!isset($sections) || count($sections)==0):?>
        <div class="d-flex justify-content-center mt-4">
            <p> Aucun résultat trouvé </p>
        </div>
    <?php else:?>
        <div class="d-flex justify-content-center mt-4">
            <p>
                <?php if(count($sections)==1):?> 1 résultat trouvé
                <?php elseif(count($sections)>1):?> <?php echo count($sections);?> résultats trouvés
                <?php endif;?>
            </p>
        </div>
        <div class="border rounded p-4">
            <?php foreach($sections as $section):?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="reportSectionResult_<?php echo $section['id_section'];?>" name="id_section" 
                        value="<?php echo $section['id_section'];?>" 
                        onclick="$('#modal').find('#sectionSelect')
                            .removeClass('d-none')
                            .attr('id_section', <?php echo $section['id_section'];?>)
                            .attr('id_file', <?php echo $section['id_file'];?>)
                            .attr('section_name', '<?php echo $section['section_name'];?>')
                            ;"
                        />
                    <label class="form-check-label" for="reportSectionResult_<?php echo $section['id_section'];?>">
                        <?php echo $section['section_name'];?>
                    </label>
                </div>
            <?php endforeach;?>
        </div>
    <?php endif;?>
</div>