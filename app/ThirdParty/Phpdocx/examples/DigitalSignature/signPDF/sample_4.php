<?php
// sign a PDF multiple times adding image and signature options

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$sign = new Phpdocx\Sign\SignPDFPlus();

$optionsSignature = array(
    'x' => 100,
    'y' => 400,
    'w' => 50,
    'h' => 50,
    'page' => 1,
);

$optionsImage = array(
    'src' => '../../files/image.png',
);

$sign->setPDF('../../files/Test_sign.pdf');
$sign->setPrivateKey('../../files/key_sample_encrypted.pem', 'phpdocx');
$sign->setX509Certificate('../../files/cert_sample.pem');
$sign->sign('example_signPDF_4_1.pdf', $optionsSignature, $optionsImage);

$optionsSignature = array(
    'x' => 400,
    'y' => 400,
    'w' => 50,
    'h' => 50,
    'page' => 1,
);

$sign = new Phpdocx\Sign\SignPDFPlus();
$sign->setPDF('example_signPDF_4_1.pdf');
$sign->setPrivateKey('../../files/key_sample_encrypted.pem', 'phpdocx');
$sign->setX509Certificate('../../files/cert_sample.pem');
$sign->sign('example_signPDF_4_2.pdf', $optionsSignature, $optionsImage);