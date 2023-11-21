<?php
// replace text variables (placeholders) with new text in headers and footers from an existing DOCX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocxFromTemplate('../../files/TemplateSimpleText_header_footer.docx');

$first = 'PHPDocX';
$multiline = 'This is the first line.\nThis is the second line of text.';

$variablesHeaders = array('VAR_HEADER' => $first);
// replace \n with line breaks in headers
$options = array('parseLineBreaks' => true, 'target' => 'header');
$docx->replaceVariableByText($variablesHeaders, $options);

$variablesFooters = array('VAR_FOOTER' => $multiline);
// replace \n with line breaks in headers
$options = array('parseLineBreaks' => true, 'target' => 'footer');
$docx->replaceVariableByText($variablesFooters, $options);

$docx->createDocx('example_replaceVariableByText_3');