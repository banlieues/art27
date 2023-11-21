<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


Barcode généré 
<!-- appelle du contenu d'une variable -->
<!-- on affiche le résultat du barcode généré dans le controleur -->
<?php echo '<img src="data:image/png;base64,' . $image . '">'; ?>


<?php $this->endSection(); ?>
