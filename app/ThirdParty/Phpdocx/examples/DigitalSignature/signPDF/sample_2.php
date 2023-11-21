<?php
// sign a PDF adding image and signature options

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$sign = new Phpdocx\Sign\SignPDF();

$sign->setPDF('../../files/Test_sign.pdf');
$sign->setPrivateKey('../../files/key_sample_encrypted.pem', 'phpdocx');
$sign->setX509Certificate('../../files/cert_sample.pem');

$optionsSignature = array(
    'x' => 0,
    'y' => 200,
    'w' => 50,
    'h' => 50,
    'page' => 1,
);

$optionsImage = array(
    'src' => '../../files/image.png',
    'x' => 0,
    'y' => 200,
    'w' => 50,
    'h' => 50,
);

$sign->sign('example_signPDF_2.pdf', $optionsSignature, $optionsImage);