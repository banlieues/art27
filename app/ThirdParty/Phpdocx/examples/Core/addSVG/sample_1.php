<?php
// add a SVG image file

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocx();

$docx->addText('Add an SVG image:');

$options = array(
    'imageAlign' => 'center',
    'spacingTop' => 10,
    'spacingBottom' => 0,
    'spacingLeft' => 0,
    'spacingRight' => 20,
    'textWrap' => 0,
    'borderStyle' => 'lgDash',
    'borderWidth' => 6,
    'borderColor' => 'FF0000',
);

$docx->addSVG('../../files/phpdocx.svg', $options);

$docx->createDocx('example_addSVG_1');