<?php
// sign a PDF

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$sign = new Phpdocx\Sign\SignPDF();

$sign->setPDF('../../files/Test_sign.pdf');
$sign->setPrivateKey('../../files/key_sample_encrypted.pem', 'phpdocx');
$sign->setX509Certificate('../../files/cert_sample.pem');
$sign->sign('example_signPDF_1.pdf');