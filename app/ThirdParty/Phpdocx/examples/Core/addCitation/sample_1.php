<?php
// add a new source and a new citations

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocx();

$author = array(
    'Author' => array(
        array(
            'Last' => 'Doe',
            'First' => 'John',
        ),
    ),
);
$info = array(
    'Title' => 'My book',
    'Year' => '2021',
    'City' => 'My city',
    'Publisher' => 'My publisher',
);

$docx->addSource('JDoe', 'Report', $author, $info);

$docx->addCitation('JDoe');

$docx->createDocx('example_addCitation_1');