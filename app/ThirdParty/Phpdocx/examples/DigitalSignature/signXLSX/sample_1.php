<?php
// sign a XLSX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$sign = new Phpdocx\Sign\SignXLSX();

copy('../../files/Book.xlsx', 'Book.xlsx');
$sign->setXlsx('Book.xlsx');
$sign->setPrivateKey('../../files/key_sample_encrypted.pem', 'phpdocx');
$sign->setX509Certificate('../../files/cert_sample.pem');
$sign->setSignatureComments('This document has been signed.');
$sign->sign();