<?php
// add new sources, citations and a bibliopgraphy

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

$docx->addSource('JEod', 'Book', $author, $info);

$docx->addCitation('JDoe');

$docx->addText('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');

$docx->addCitation('JEod', array('Author', 'City', 'Title'), array('bold' => true, 'fontSize' => 18));

$citation = new Phpdocx\Elements\WordFragment($docx);
$citation->addCitation('JEod', array('Author', 'City'), array('bold' => true));

$text = array();
$text[] = array(
    'text' => 'Citation added as WordFragment: '
);
$text[] = $citation;
$text[] = array(
    'text' => '.'
);

$docx->addText($text);

$legend = array(
    'text' => 'Document bibliography', 
    'color' => 'B70000', 
    'bold' => true, 
    'fontSize' => 12,
);

$docx->addBibliography(array('autoUpdate' => true), $legend);

$docx->createDocx('example_addBibliography_1');