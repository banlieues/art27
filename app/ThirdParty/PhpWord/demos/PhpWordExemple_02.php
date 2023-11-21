<?php
require_once 'vendor\phpoffice\phpword\bootstrap.php';

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
$section->addText(
    'File Format Developer Guide - '
    . 'Learn about computer files that you come across in '
    . 'your daily work at: www.fileformat.com'
);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('HelloWorld.docx');
?>
