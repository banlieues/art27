<?php
// generate a DOCX with a TOC and transform it to PDF using the conversion plugin based on MS Word updating the TOC

require_once '../../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocx();

$docx->addText('Table of Contents', array('bold' => true, 'fontSize' => 14));
$legend = array(
    'text' => 'Click here to update the TOC', 
    'color' => 'B70000', 
    'bold' => true, 
    'fontSize' => 12
);
$docx->addTableContents(array('autoUpdate' => true), $legend, '../../../files/crazyTOC.docx');

$docx->addText('Chapter 1', array('pStyle' => 'Heading1PHPDOCX'));
$docx->addText('Section', array('pStyle' => 'Heading2PHPDOCX'));
$docx->addText('Another TOC entry', array('pStyle' => 'Heading3PHPDOCX'));

$docx->createDocx('transformDocument_msword_3');

$docx->transformDocument('transformDocument_msword_3.docx', 'transformDocument_msword_3.pdf', 'msword', array('toc' => true));