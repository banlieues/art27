<?php
// generate a DOCX with lists and transform it to PDF using the conversion plugin based on native PHP classes

require_once '../../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocx();

$itemList= array(
    'Line 1',
    array(
        'Line A',
        'Line B',
        'Line C',
        array(
            'Line 1.1',
            'Line 1.2',
            'Line 1.3',
        ),
        'Line D',
    ),
    'Line 2',
);

$docx->addList($itemList, 2);

$textData = new Phpdocx\Elements\WordFragment($docx);
$textData->addText('Bold text', array('bold' => true));

$htmlData = new Phpdocx\Elements\WordFragment($docx);
$html = '<i>Some HTML code</i> with a <a href="http://www.phpdocx.com">link</a>';
$htmlData->embedHTML($html);

$itemList= array(
    'In this example we use a list is added.',
    array(
        $textData,
        'Line B',
        'Line C'
    ),
    $htmlData,
    'Line 3',
);

$options = array(
    'italic' => true,
    'fontSize' => 14,
    'color' => 'b70000'
);

$docx->addList($itemList, 1, $options);

$docx->createDocx('transformDocument_native_6.docx');

// include DOMPDF and create an object. DOMPDF isn't bundled in phpdocx, it can be downloaded from https://github.com/dompdf/dompdf
require_once 'dompdf/autoload.inc.php';
$dompdf = new Dompdf\Dompdf();

$transform = new Phpdocx\Transform\TransformDocAdvNative();
$transform->transformDocument('transformDocument_native_6.docx', 'transformDocument_native_6.pdf', array('dompdf' => $dompdf));