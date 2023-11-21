<?php $this->extend('Layout\tamo/index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    <?php echo translator("Règles à respecter dans le formulaire Gravity Forms");?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub_title'); ?>
    <?php echo translator("Règles à respecter dans le formulaire Gravity Forms");?>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('DemandeWeb\js/js_demande');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>        

Ci-dessous est repris l'ensemble des caractéristiques nécessaires pour la connexion entre le formulaire Gravity Forms et le CRM. Le formulaire GF doit respecter ABOLUMENT les règles suivantes. <br><br>
<ul>
    <li> Le formulaire de demande de contact doit avoir son ID = <?php echo $id_form;?> </li>
    <li> Au niveau du formulaire GravityForms, tous les éléments de la colonne ci-dessous <b> Référence </b> doivent être repris dans : <br>
        <ul>
            <li> le champ "<b>Libellé du champ d'administration</b>" <small>(Réglages de champ > Avancé)</small> </li>
            <li> ou le champ "<b>Nom du paramètre</b>" <small>(Réglages de champ > Avancé)</small> </li>
        </ul>
    </li>
    <li> Dans le cas des "Cases à cocher" (checkbox), "Boutons radio" et "Liste déroulante" (select), il faut cocher "afficher les valeurs" <small>(Réglages de champ > Général)</small>. La "Valeur" indiquée doit correspondre au "Libellé" repris dans ce tableau. </li>
</ul>

    <table class="table table-bordered p-4 w-100">
        <thead class="thead-light">
            <tr>
                <th class="col-3"> Label </th>
                <th class="col-1"> Type </th>
                <th class="col-2"> Référence </th>
                <th class="col-1"> Valeurs </th>
                <th> Libellés </th>
            <tr> 
        </thead>
        <tbody>
            <?php foreach($fields as $ref=>$field):?>
                <?php if(isset($field->type)):?>
                    <?php if(in_array($field->type, ['checkbox', 'radio', 'select'])):?>
                        <?php $i=0;?>
                        <?php foreach($lists->$ref as $elem):?>
                            <tr>
                                <?php if($i==0):?>
                                    <td rowspan="<?php echo count($lists->$ref);?>"> <?php echo $field->label_fr;?> </td>
                                    <td rowspan="<?php echo count($lists->$ref);?>"> <?php echo $field->type;?> </td>
                                    <td rowspan="<?php echo count($lists->$ref);?>"> <?php echo $ref;?> </td>
                            <?php endif;?>
                                <td> <?php echo $elem->id;?> </td>
                                <td> <?php echo $elem->label_fr;?> </td>
                            </tr>
                            <?php $i++;?>
                        <?php endforeach;?>
                    <?php else :?>
                        <tr>
                            <td> <?php echo $field->label_fr;?> </td>
                            <td> <?php echo $field->type;?> </td>
                            <td> <?php echo $ref;?> </td>
                            <td> </td>
                            <td> </td>
                        </tr>
                    <?php endif;?>
                <?php endif;?>
            <?php endforeach;?>
        </tbody>
    </table>
