<?php
// sign a PDF multiple times

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$sign = new Phpdocx\Sign\SignPDFPlus();

$sign->setPDF('../../files/Test_sign.pdf');
$sign->setPrivateKey('../../files/key_sample_encrypted.pem', 'phpdocx');
$sign->setX509Certificate('../../files/cert_sample.pem');
$sign->sign('example_signPDF_3_1.pdf');

$sign->setPDF('example_signPDF_3_1.pdf');
$sign->setPrivateKey('../../files/key_sample_encrypted.pem', 'phpdocx');
$sign->setX509Certificate('../../files/cert_sample.pem');
$sign->sign('example_signPDF_3_2.pdf');