<?php
// transform DOCX to PDF using the conversion plugin based on native PHP classes

require_once '../../../../Classes/Phpdocx/Create/CreateDocx.php';

// include DOMPDF and create an object. DOMPDF isn't bundled in phpdocx, it can be downloaded from https://github.com/dompdf/dompdf
require_once 'dompdf/autoload.inc.php';
$dompdf = new Dompdf\Dompdf();

$docx = new Phpdocx\Create\CreateDocx();
$docx->transformDocument('../../../files/Text.docx', 'transformDocument_native_3.pdf', 'native', array('dompdf' => $dompdf));