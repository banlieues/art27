<?php
// replace a text variable (placeholder) with a WordFragment doing an inline-block replacement from an existing DOCX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocxFromTemplate('../../files/TemplateHTML.docx');

// content to be added inline when doing the replacement
$textWordFragment = new Phpdocx\Elements\WordFragment($docx, 'document');
$textWordFragment->addText('New paragraph');

// contents to be added
$wordFragment = new Phpdocx\Elements\WordFragment($docx, 'document');
$wordFragment->addWordML($textWordFragment->inlineWordML()); // inline content
$wordFragment->addText('Other paragraph'); // block content

$valuesTable = array(
    array(11, 12, 13, 14),
    array(21, 22, 23, 24),
    array(31, 32, 33, 34),
);

$paramsTable = array(
    'border' => 'single',
    'tableAlign' => 'center',
    'borderWidth' => 10,
    'borderColor' => 'B70000',
);

$wordFragment->addTable($valuesTable, $paramsTable); // block content
$wordFragment->addText('Other paragraph'); // block content

$docx->replaceVariableByWordFragment(array('ADDRESS' => $wordFragment), array('type' => 'inline-block', 'stylesReplacementType' => 'mixPlaceholderStyles', 'stylesReplacementTypeIgnore' => array('w:sz', 'w:szCs')));

$docx->createDocx('example_replaceVariableByWordFragment_9');