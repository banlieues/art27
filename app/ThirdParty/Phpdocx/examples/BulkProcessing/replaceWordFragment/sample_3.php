<?php
// replace placeholders by WordFragments with external dependencies using bulk methods, generate a single DOCX output

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$bulk = new Phpdocx\Utilities\BulkProcessing('../../files/bulk.docx');
$docx = new Phpdocx\Create\CreateDocx();

$optionsImage1 = array(
    'src' => '../../img/imageP1.png',
    'scaling' => 50,
);
$optionsImage2 = array(
    'src' => '../../img/imageP2.png',
    'scaling' => 50,
);
$optionsImage3 = array(
    'src' => '../../img/imageP3.png',
    'scaling' => 50,
);

$variables = 
  array(
    // first DOCX
    array('signature' => array('type' => 'image', 'options' => $optionsImage1)),
    // second DOCX
    array('signature' => array('type' => 'image', 'options' => $optionsImage2)),
    // third DOCX
    array('signature' => array('type' => 'image', 'options' => $optionsImage3)),
  );

$options = array(
  'type' => 'inline',
);

$bulk->replaceWordFragment($variables, $options);
$documents = $bulk->getDocuments();

$documents[0]->saveDocx('example_replaceWordFragment_3_1');
$documents[1]->saveDocx('example_replaceWordFragment_3_2');
$documents[2]->saveDocx('example_replaceWordFragment_3_3');