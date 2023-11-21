<?php
// replace a text variable (placeholder) with HTML using replaceVariableByWordFragment doing an inline-block replacement from an existing DOCX

require_once '../../../Classes/Phpdocx/Create/CreateDocx.php';

$docx = new Phpdocx\Create\CreateDocxFromTemplate('../../files/TemplateHTML.docx');

// content to be added inline when doing the replacement
$htmlWordFragment = new Phpdocx\Elements\WordFragment($docx, 'document');
$htmlWordFragment->embedHTML('<p>C/ Matías Turrión 24, Madrid 28043 <b>Spain</b></p>');

$htmlWordFragment2 = new Phpdocx\Elements\WordFragment($docx, 'document');
$htmlWordFragment2->embedHTML('
  <h1 style="color: #b70000">An embedHTML() example</h1>
  <p>We draw a table with border and rawspans and colspans:</p>
  <table border="1" style="border-collapse: collapse" width="200">
    <tbody>
        <tr>
            <td style="background-color: yellow">1_1</td>
            <td rowspan="3" colspan="2">1_2</td>
        </tr>
        <tr>
            <td>Some random text.</td>
        </tr>
        <tr>
            <td>
                <ul>
                    <li>One</li>
                    <li>Two <b>and a half</b></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>3_2</td>
            <td>3_3</td>
            <td>3_3</td>
        </tr>
    </tbody>
  </table>
');

// contents to be added
$wordFragment = new Phpdocx\Elements\WordFragment($docx, 'document');
$wordFragment->addWordML($htmlWordFragment->inlineWordML()); // inline content
$wordFragment->addWordFragment($htmlWordFragment2); // block content

$docx->replaceVariableByWordFragment(array('ADDRESS' => $wordFragment), array('type' => 'inline-block', 'stylesReplacementType' => 'mixPlaceholderStyles'));

$docx->createDocx('example_replaceTemplateVariableByHTML_5');