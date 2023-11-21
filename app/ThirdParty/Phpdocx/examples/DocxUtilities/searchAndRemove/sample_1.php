<?php
// search and remove a string in an existing DOCX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$newDocx = new Phpdocx\Utilities\DocxUtilities();

$options = array(
    'document' => true,
    'endnotes' => true,
    'comments' => true,
    'headersAndFooters' => true,
    'footnotes' => true,
);
$newDocx->searchAndRemove('../../files/second.docx', 'example_removedParagraphDocx.docx', 'different', $options);