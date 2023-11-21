<?php
// sign a DOCX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$sign = new Phpdocx\Sign\SignDOCX();

copy('../../files/Text.docx', 'Text.docx');
$sign->setDocx('Text.docx');
$sign->setPrivateKey('../../files/key_sample_encrypted.pem', 'phpdocx');
$sign->setX509Certificate('../../files/cert_sample.pem');
$sign->setSignatureComments('This document has been signed.');
$sign->sign();