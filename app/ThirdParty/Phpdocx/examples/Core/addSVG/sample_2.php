<?php
// add a SVG content

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocx();

$docx->addText('Add an SVG image:');

$svg = '
<svg height="100" width="100">
  <circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" />
</svg>';

$docx->addSVG($svg);

$docx->createDocx('example_addSVG_2');