<?php
// generate a DOCX with a header, and change the styles of the header

require_once '../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocx();

$headerText = new Phpdocx\Elements\WordFragment($docx, 'defaultHeader');
$headerText->addText('Text header.');

$docx->addHeader(array('default' => $headerText));

$text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
    'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut ' .
    'enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut' .
    'aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit ' .
    'in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
    'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ' .
    'officia deserunt mollit anim id est laborum.';

$docx->addText($text);

// get the content to be changed
$referenceNode = array(
    'target' => 'header',
    'type' => 'paragraph',
);

$docx->customizeWordContent($referenceNode, 
    array(
        'bold' => true,
        'italic' => true,
        'font' => 'Arial',
        'fontSize' => 14,
    )
);

$docx->createDocx('example_customizer_13');