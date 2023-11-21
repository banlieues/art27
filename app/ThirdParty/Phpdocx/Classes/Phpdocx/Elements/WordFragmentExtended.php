<?php
namespace Phpdocx\Elements;

use Phpdocx\Create\CreateDocxFromTemplate;
/**
 * Extra features to work with WordFragments
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    phpdocx LICENSE
 * @link       https://www.phpdocx.com
 */
class WordFragmentExtended
{
    /**
     * Replace a WordFragment placeholder keeping pPr and rPr styles
     *
     * @access public
     * @param string $variableKey
     * @param WordFragment $variableFragment
     * @param string $content
     * @param array $options
     */
    public function setStylesReplacementType($variableKey, $variableFragment, $content, $options)
    {
        // get placeholder pPr styles
        $domDocument = new \DOMDocument();
        if (PHP_VERSION_ID < 80000) {
            $optionEntityLoader = libxml_disable_entity_loader(true);
        }
        $domDocument->loadXML($content);
        if (PHP_VERSION_ID < 80000) {
            libxml_disable_entity_loader($optionEntityLoader);
        }

        $nodeXPath = new \DOMXPath($domDocument);
        $nodeXPath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $query = '//w:p[w:r/w:t[text()[contains(.,"'.CreateDocxFromTemplate::$_templateSymbolStart.$variableKey.CreateDocxFromTemplate::$_templateSymbolEnd.'")]]]';

        $pPrStyles = $nodeXPath->query($query);

        if ($pPrStyles->length > 0) {
            // get pPr styles
            $pPrStylesNode = $pPrStyles->item(0)->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'pPr');
            if ($pPrStylesNode->length > 0) {
                $pPrStylesXML = $pPrStylesNode->item(0)->ownerDocument->saveXML($pPrStylesNode->item(0));

                // replace the pPr styles with the new ones from the placeholder
                $newFragment = new \DOMDocument();
                if (PHP_VERSION_ID < 80000) {
                    $optionEntityLoader = libxml_disable_entity_loader(true);
                }
                $newFragment->loadXML('<w:root xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" 
                                   xmlns:mo="http://schemas.microsoft.com/office/mac/office/2008/main" 
                                   xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" 
                                   xmlns:mv="urn:schemas-microsoft-com:mac:vml" 
                                   xmlns:o="urn:schemas-microsoft-com:office:office" 
                                   xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" 
                                   xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" 
                                   xmlns:v="urn:schemas-microsoft-com:vml" 
                                   xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing" 
                                   xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" 
                                   xmlns:w10="urn:schemas-microsoft-com:office:word" 
                                   xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" 
                                   xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml" 
                                   xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup" 
                                   xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk" 
                                   xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" 
                                   xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" 
                                   mc:Ignorable="w14 wp14">' . (string)$variableFragment . '</w:root>');
                if (PHP_VERSION_ID < 80000) {
                    libxml_disable_entity_loader($optionEntityLoader);
                }
                $newFragmentXpath = new \DOMXPath($newFragment);
                $newFragmentXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
                $query = '//w:pPr';
                $pPrStylesFragment = $newFragmentXpath->query($query);
                // the new content can have more than one pPr, replace all
                if ($pPrStylesFragment->length > 0) {
                    foreach ($pPrStylesFragment as $pPrStyleFragment) {
                        // keep original to not overwrite pPr styles in each iteration
                        $pPrStylesXMLOriginal = $pPrStylesXML;
                        // mix both styles
                        if ($options['stylesReplacementType'] == 'mixPlaceholderStyles') {
                            // get internal styles
                            foreach ($pPrStyleFragment->childNodes as $pPrStyleFragmentNode) {
                                if (isset($pPrStyleFragmentNode->tagName) && !strstr($pPrStylesXML, $pPrStyleFragmentNode->tagName)) {
                                    // don't add the style if it's ignored
                                    if (!isset($options['stylesReplacementTypeIgnore']) ||(isset($options['stylesReplacementTypeIgnore']) && !in_array($pPrStyleFragmentNode->tagName, $options['stylesReplacementTypeIgnore']))) {
                                        $pPrStylesXML = str_replace('</w:pPr>', $pPrStyleFragmentNode->ownerDocument->saveXML($pPrStyleFragmentNode) . '</w:pPr>', $pPrStylesXML);
                                    }
                                }
                            }
                        }
                        $newPpr = $newFragment->createDocumentFragment();
                        @$newPpr->appendXML($pPrStylesXML);
                        $newFragment->importNode($newPpr, true);
                        $pPrStyleFragment->parentNode->replaceChild($newPpr, $pPrStyleFragment);
                        $pPrStylesXML = $pPrStylesXMLOriginal;
                    }
                }

                // generate the new pPr keeping all children in the XML
                $newWordFragment = '';
                $rootNode = $newFragment->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'root');
                if ($rootNode->length > 0) {
                    foreach ($rootNode->item(0)->childNodes as $childNodeRoot) {
                        $newWordFragment .= $childNodeRoot->ownerDocument->saveXML($childNodeRoot);
                    }

                    $variableFragment->setWordML($newWordFragment);
                }
            }
        }

        // get placeholder rPr styles
        $query = '//w:r[w:t[text()[contains(.,"'.CreateDocxFromTemplate::$_templateSymbolStart.$variableKey.CreateDocxFromTemplate::$_templateSymbolEnd.'")]]]';
        $rPrStyles = $nodeXPath->query($query);
        if ($rPrStyles->length > 0) {
            // get rPr styles
            $rPrStylesNode = $rPrStyles->item(0)->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'rPr');
            if ($rPrStylesNode->length > 0) {
                $rPrStylesXML = $rPrStylesNode->item(0)->ownerDocument->saveXML($rPrStylesNode->item(0));

                // replace the rPr styles with the new ones from the placeholder
                $newFragment = new \DOMDocument();
                if (PHP_VERSION_ID < 80000) {
                    $optionEntityLoader = libxml_disable_entity_loader(true);
                }
                $newFragment->loadXML('<w:root xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" 
                                   xmlns:mo="http://schemas.microsoft.com/office/mac/office/2008/main" 
                                   xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" 
                                   xmlns:mv="urn:schemas-microsoft-com:mac:vml" 
                                   xmlns:o="urn:schemas-microsoft-com:office:office" 
                                   xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" 
                                   xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" 
                                   xmlns:v="urn:schemas-microsoft-com:vml" 
                                   xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing" 
                                   xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" 
                                   xmlns:w10="urn:schemas-microsoft-com:office:word" 
                                   xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" 
                                   xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml" 
                                   xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup" 
                                   xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk" 
                                   xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" 
                                   xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" 
                                   mc:Ignorable="w14 wp14">' . (string)$variableFragment . '</w:root>');
                if (PHP_VERSION_ID < 80000) {
                    libxml_disable_entity_loader($optionEntityLoader);
                }
                $newFragmentXpath = new \DOMXPath($newFragment);
                $newFragmentXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
                $query = '//w:rPr';
                $rPrStylesFragment = $newFragmentXpath->query($query);
                // the new content can have more than one rPr, replace all
                if ($rPrStylesFragment->length > 0) {
                    foreach ($rPrStylesFragment as $rPrStyleFragment) {
                        // keep original to not overwrite rPr styles in each iteration
                        $rPrStylesXMLOriginal = $rPrStylesXML;
                        // mix both styles
                        if ($options['stylesReplacementType'] == 'mixPlaceholderStyles') {
                            // get internal styles
                            foreach ($rPrStyleFragment->childNodes as $rPrStyleFragmentNode) {
                                // don't add the style if already exits
                                if (!strstr($rPrStylesXML, $rPrStyleFragmentNode->tagName)) {
                                    // don't add the style if it's ignored
                                    if (!isset($options['stylesReplacementTypeIgnore']) || (isset($options['stylesReplacementTypeIgnore']) && !in_array($rPrStyleFragmentNode->tagName, $options['stylesReplacementTypeIgnore']))) {
                                        $rPrStylesXML = str_replace('</w:rPr>', $rPrStyleFragmentNode->ownerDocument->saveXML($rPrStyleFragmentNode) . '</w:rPr>', $rPrStylesXML);
                                    }
                                }
                            }
                        }
                        $newRpr = $newFragment->createDocumentFragment();
                        @$newRpr->appendXML($rPrStylesXML);
                        $newFragment->importNode($newRpr, true);
                        $rPrStyleFragment->parentNode->replaceChild($newRpr, $rPrStyleFragment);
                        $rPrStylesXML = $rPrStylesXMLOriginal;
                    }
                }

                // generate the new rPr keeping all children in the XML
                $newWordFragment = '';
                $rootNode = $newFragment->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'root');
                if ($rootNode->length > 0) {
                    foreach ($rootNode->item(0)->childNodes as $childNodeRoot) {
                        $newWordFragment .= $childNodeRoot->ownerDocument->saveXML($childNodeRoot);
                    }

                    $variableFragment->setWordML($newWordFragment);
                }
            } else {
                // there's no rPr styles, remove the existing ones
                $newFragment = new \DOMDocument();
                if (PHP_VERSION_ID < 80000) {
                    $optionEntityLoader = libxml_disable_entity_loader(true);
                }
                $newFragment->loadXML('<w:root xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" 
                                   xmlns:mo="http://schemas.microsoft.com/office/mac/office/2008/main" 
                                   xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" 
                                   xmlns:mv="urn:schemas-microsoft-com:mac:vml" 
                                   xmlns:o="urn:schemas-microsoft-com:office:office" 
                                   xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" 
                                   xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" 
                                   xmlns:v="urn:schemas-microsoft-com:vml" 
                                   xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing" 
                                   xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" 
                                   xmlns:w10="urn:schemas-microsoft-com:office:word" 
                                   xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" 
                                   xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml" 
                                   xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup" 
                                   xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk" 
                                   xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" 
                                   xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" 
                                   mc:Ignorable="w14 wp14">' . (string)$variableFragment . '</w:root>');
                if (PHP_VERSION_ID < 80000) {
                    libxml_disable_entity_loader($optionEntityLoader);
                }
                $newFragmentXpath = new \DOMXPath($newFragment);
                $newFragmentXpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
                $query = '//w:rPr';
                $rPrStylesFragment = $newFragmentXpath->query($query);
                // the new content can have more than one rPr, replace all
                if ($rPrStylesFragment->length > 0) {
                    foreach ($rPrStylesFragment as $rPrStyleFragment) {
                        if ($options['stylesReplacementType'] == 'mixPlaceholderStyles') {
                            // get internal HTML styles
                            $rPrStylesXML = '<w:rPr></w:rPr>';
                            // add the styles from the HTML but those ignored
                            foreach ($rPrStyleFragment->childNodes as $rPrStyleFragmentNode) {
                                // don't add the style if it's ignored
                                if (!isset($options['stylesReplacementTypeIgnore']) || (isset($options['stylesReplacementTypeIgnore']) && !in_array($rPrStyleFragmentNode->tagName, $options['stylesReplacementTypeIgnore']))) {
                                    $rPrStylesXML = str_replace('</w:rPr>', $rPrStyleFragmentNode->ownerDocument->saveXML($rPrStyleFragmentNode) . '</w:rPr>', $rPrStylesXML);
                                }
                            }
                            $newRpr = $newFragment->createDocumentFragment();
                            @$newRpr->appendXML($rPrStylesXML);
                            $newFragment->importNode($newRpr, true);
                            $rPrStyleFragment->parentNode->insertBefore($newRpr, $rPrStyleFragment);

                            $rPrStyleFragment->parentNode->removeChild($rPrStyleFragment);
                        } else {
                            // remove all HTML rPr styles to use the placeholder styles
                            $rPrStyleFragment->parentNode->removeChild($rPrStyleFragment);
                        }
                    }
                }

                // generate the new rPr keeping all children in the XML
                $newWordFragment = '';
                $rootNode = $newFragment->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'root');
                if ($rootNode->length > 0) {
                    foreach ($rootNode->item(0)->childNodes as $childNodeRoot) {
                        $newWordFragment .= $childNodeRoot->ownerDocument->saveXML($childNodeRoot);
                    }

                    $variableFragment->setWordML($newWordFragment);
                }
            }
        }

        return $variableFragment;
    }
}
