<?php
// replace a text variable (placeholder) with HTML mixing placeholder styles and HTML styles from an existing DOCX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocxFromTemplate('../../files/TemplateHTML.docx');

// ignore font size when mixing the styles
$docx->replaceVariableByHTML('ADDRESS', 'inline', '<p>C/ Matías Turrión 24, Madrid 28043 <b>Spain</b></p>', array('stylesReplacementType' => 'mixPlaceholderStyles', 'stylesReplacementTypeIgnore' => array('w:sz', 'w:szCs')));

$docx->createDocx('example_replaceTemplateVariableByHTML_4');