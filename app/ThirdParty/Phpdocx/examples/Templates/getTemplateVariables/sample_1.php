<?php
// return the variables (placeholders) from an existing DOCX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocxFromTemplate('../../files/TemplateVariables.docx');

print_r($docx->getTemplateVariables());