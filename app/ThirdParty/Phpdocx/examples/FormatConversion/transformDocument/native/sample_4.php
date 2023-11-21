<?php
// generate a DOCX with text contents and transform it to PDF using the conversion plugin based on native PHP classes

require_once '../../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocx();

$text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
$docx->addText($text);

$paragraphOptions = array(
    'backgroundColor' => 'FF0000',
    'bold' => true,
    'color' => '00FF00',
    'font' => 'Arial',
    'fontSize' => 18,
    'italic' => true,
    'pageBreakBefore' => true,
    'firstLineIndent' => 280,
);

$docx->addText($text, $paragraphOptions);

$textArray = array();
$textArray[] =
    array(
        'text' => 'We know this looks ugly',
        'underline' => 'single',
        'color' => 'FF0000',
        'font' => 'Times',
        'italic' => true,
        'highlightColor' => 'blue',
);
$textArray[] =
    array(
        'text' => ' but we only want to illustrate some of the functionality of the addText method.',
        'bold' => true,
        'color' => '00FF00',
        'fontSize' => 16,
        'font' => 'Arial',
        'strikeThrough' => true,
);
$docx->addText($textArray);

$docx->createDocx('transformDocument_native_4.docx');

// include DOMPDF and create an object. DOMPDF isn't bundled in phpdocx, it can be downloaded from https://github.com/dompdf/dompdf
require_once 'dompdf/autoload.inc.php';
$dompdf = new Dompdf\Dompdf();

$transform = new Phpdocx\Transform\TransformDocAdvNative();
$transform->transformDocument('transformDocument_native_4.docx', 'transformDocument_native_4.pdf', array('dompdf' => $dompdf));