<div class="alert alert-warning mb-4"> Le style de l'aperçu peut changer par rapport à celui du fichier Word. Cette différence ne sera pas prise en compte lors de la génération de rapport. </div>

<fieldset id="reportSectionsFieldset">
    <legend id="reportSectionsLegend"> Blocs </legend>
    <div class="plusminus-group">
        <?php $i=0;?>
        <?php if(!empty($sections)):?>
            <?php foreach ($sections as $section) : ?>
                <?php echo view('Report\report/form/fieldset_section_row', ['i' => $i, 'section' => $section]);?>
                <?php $i++; ?>
            <?php endforeach ;?>
        <?php else:?>
            <?php echo view('Report\report/form/fieldset_section_row', ['i' => $i]);?>
        <?php endif;?>
    </div>
</fieldset>