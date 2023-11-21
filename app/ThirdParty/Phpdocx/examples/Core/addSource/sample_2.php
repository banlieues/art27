<?php
// add new sources

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocx();

$author = array(
    'Author' => array(
        array(
            'Last' => 'Doe',
            'First' => 'John',
        ),
        array(
            'Last' => 'Doe',
            'First' => 'Jane',
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

$author = array(
    'Author' => array(
        array(
            'Last' => 'Eod',
            'First' => 'John',
        ),
    ),
);
$info = array(
    'Title' => 'My book 2',
    'Year' => '2022',
    'City' => 'My city 2',
    'Publisher' => 'My publisher 2',
);

$docx->addSource('JDoe', 'Report', $author, $info);

$docx->createDocx('example_addSource_2');