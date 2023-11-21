<?php
// clone a block and replace placeholders in an existing DOCX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocxFromTemplate('../../files/TemplateBlocks_replace.docx');

// clone block
$docx->cloneBlock('FIRST');
$docx->cloneBlock('FIRST');

// replace values in the new blocks using the firstMatch option available in template methods

// first block
$variablesText = array('VAR_INLINE_TEXT' => 'New text 1', 'VAR_TEXT' => 'New block text 1');
$docx->replaceVariableByText($variablesText, array('firstMatch' => true));
$docx->replacePlaceholderImage('IMAGE', '../../img/imageP1.png', array('firstMatch' => true));
$dataTable = array(
    array(
        'ITEM' => 'Product A',
        'REFERENCE' => '107AW3',
    ),
    array(
        'ITEM' => 'Product B',
        'REFERENCE' => '204RS67O',
    ),
);
$docx->replaceTableVariable($dataTable, array('firstMatch' => true));

// second block
$variablesText = array('VAR_INLINE_TEXT' => 'New text 2', 'VAR_TEXT' => 'New block text 2');
$docx->replaceVariableByText($variablesText, array('firstMatch' => true));
$docx->replacePlaceholderImage('IMAGE', '../../img/imageP2.png', array('firstMatch' => true));
$dataTable = array(
    array(
        'ITEM' => 'Product 1',
        'REFERENCE' => 'Ref W3',
    ),
    array(
        'ITEM' => 'Product 2',
        'REFERENCE' => 'Ref 70',
    ),
);
$docx->replaceTableVariable($dataTable, array('firstMatch' => true));

// third block (remaining block, it can be removed if needed using deleteTemplateBlock)
$variablesText = array('VAR_INLINE_TEXT' => 'New text 3', 'VAR_TEXT' => 'New block text 3');
$docx->replaceVariableByText($variablesText, array('firstMatch' => true));
$docx->replacePlaceholderImage('IMAGE', '../../img/imageP3.png', array('firstMatch' => true));
$dataTable = array(
    array(
        'ITEM' => 'Product I',
        'REFERENCE' => 'Ref10',
    ),
    array(
        'ITEM' => 'Product II',
        'REFERENCE' => 'Ref20',
    ),
);
$docx->replaceTableVariable($dataTable, array('firstMatch' => true));

// clean block placeholders
$docx->clearBlocks();

$docx->createDocx('example_cloneBlock_7');