<?php
// sign a PPTX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$sign = new Phpdocx\Sign\SignPPTX();

copy('../../files/sample.pptx', 'sample.pptx');
$sign->setPptx('sample.pptx');
$sign->setPrivateKey('../../files/key_sample_encrypted.pem', 'phpdocx');
$sign->setX509Certificate('../../files/cert_sample.pem');
$sign->setSignatureComments('This document has been signed.');
$sign->sign();