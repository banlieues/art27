<?php
// merge two documents that include custom paragraph styles with the same names using the default options and enabling the renameStyles option

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

// first DOCX
$docxA = new Phpdocx\Create\CreateDocx();
$style = array(
    'bold' => true,
    'italic' => true,
);
$docxA->createParagraphStyle('myStyle', $style);
$docxA->addText('New content', array('pStyle' => 'myStyle'));
$docxA->createDocx('document_A.docx');

// second DOCX
$docxB = new Phpdocx\Create\CreateDocx();
$style = array(
    'italic' => true,
    'underline' => 'dash',
);
$docxB->createParagraphStyle('myStyle', $style);
$docxB->addText('New content', array('pStyle' => 'myStyle'));
$docxB->createDocx('document_B.docx');

// merge the documents using the default options: myStyle is added from the first DOCX, but myStyle from the second DOCX is ignored because there's a style with the same name already added. This is the default behaviour of MS Word when a content is copied and pasted from a DOCX to other
$merge = new Phpdocx\Utilities\MultiMerge();
$merge->mergeDocx('document_A.docx', array('document_B.docx'), 'output_5_default.docx', array());

// merge the documents enabling the renameStyles option: myStyle comes from the first DOCX and the style with the same name from the second DOCX is renamed to add it too
$merge = new Phpdocx\Utilities\MultiMerge();
$merge->mergeDocx('document_A.docx', array('document_B.docx'), 'output_5_rename_styles.docx', array('renameStyles' => true));