<?php
namespace Phpdocx\Elements;

/**
 * Create citation
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    phpdocx LICENSE
 * @link       https://www.phpdocx.com
 */
class CreateCitation
{
    /**
     *
     * @access private
     * @var string
     */
    private static $_instance = NULL;

    /**
     *
     * @access private
     * @var string
     */
    private $_xml;

    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {
        
    }

    /**
     * Destruct
     *
     * @access public
     */
    public function __destruct()
    {
        
    }

    /**
     * Magic method, returns current XML
     *
     * @access public
     * @return string Return current XML
     */
    public function __toString()
    {
        return $this->_xml;
    }

    /**
     * Singleton, return instance of class
     *
     * @access public
     * @return CreateCitation
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateCitation();
        }
        return self::$_instance;
    }

    /**
     * Create citation tag
     *
     * @access public
     * @param mixed $args[0]
     */
    public function createCitation()
    {
        $args = func_get_args();
        $this->_xml = '';
        $lockValues = array('sdtLocked', 'contentLocked', 'unlocked', 'sdtContentLocked');
        $id = rand(100000000, 999999999);
        $sourceEntry = $args[0];
        $infoSource = $args[1];
        $styles = $args[2];
        $tag = '';
        $tagNodes = $sourceEntry->getElementsByTagNameNS('http://schemas.openxmlformats.org/officeDocument/2006/bibliography', 'Tag');
        if ($tagNodes->length > 0) {
            $tag = $tagNodes->item(0)->textContent;
        }

        $sdtPr = '<w:sdtPr>';
        $sdtPr .= '<w:citation/>';
        $sdtPr .= '</w:sdtPr>';

        $rPrContent = $this->generateRPr($styles);

        $sourceInfoData = array();
        foreach ($infoSource as $info) {
            if ($info == 'Author') {
                // get authors
                $queryPersonNodes = $sourceEntry->getElementsByTagNameNS('http://schemas.openxmlformats.org/officeDocument/2006/bibliography', 'Person');
                if ($queryPersonNodes->length > 0) {
                    $authorsInfo = array();
                    foreach ($queryPersonNodes as $queryPersonNode) {
                        $childrenPersonNodes = $queryPersonNode->childNodes;
                        if ($childrenPersonNodes->length > 0) {
                            $authorData = array();
                            foreach ($childrenPersonNodes as $childrenPersonNode) {
                                $authorData[] = $childrenPersonNode->textContent;
                            }
                            $authorsInfo[] .= implode(', ' , $authorData);
                        }
                    }
                    // clean extra ; at the end
                    $sourceInfoData[] = implode(';', $authorsInfo);
                }
            } else {
                // get other info
                $infoNodes = $sourceEntry->getElementsByTagNameNS('http://schemas.openxmlformats.org/officeDocument/2006/bibliography', $info);
                if ($infoNodes->length > 0) {
                    $sourceInfoData[] = $infoNodes->item(0)->textContent;
                }
            }
        }
        // clean extra , at the end
        $sourceInfo = '(' . implode(', ' , $sourceInfoData) . ')';

        $runs = '<w:r>' . $rPrContent . '<w:fldChar w:fldCharType="begin" /></w:r>';
        $runs .= '<w:r>' . $rPrContent . '<w:instrText xml:space="preserve">CITATION ' . $tag .' \l 3082</w:instrText></w:r>';
        $runs .= '<w:r>' . $rPrContent . '<w:fldChar w:fldCharType="separate" /></w:r>';
        $runs .= '<w:r>' . $rPrContent . '<w:t>' . htmlspecialchars($sourceInfo) . '</w:t></w:r>';
        $runs .= '<w:r>' . $rPrContent . '<w:fldChar w:fldCharType="end" /></w:r>';

        $sdtContent = '<w:sdtContent>' . $runs . '</w:sdtContent>';

        $this->_xml = '<w:p><w:sdt>' . $sdtPr . $sdtContent . '</w:sdt></w:p>';
    }

    /**
     * Generates a rPr node
     *
     * @access private
     * @param array $options
     */
    private function generateRPr($options)
    {
        $rPr = '<w:rPr>';
        if (!empty($options['font'])) {
            $rPr .= '<w:rFonts w:ascii="' . $options['font'] . '" w:hAnsi="' . $options['font'] . '" w:eastAsia="' . $options['font'] . '" w:cs="' . $options['font'] . '" />';
        }
        if (!empty($options['b'])) {
            $rPr .= '<w:b w:val="' . $options['b'] . '" />';
        }
        if (!empty($options['i'])) {
            $rPr .= '<w:i w:val="' . $options['i'] . '" />';
        }
        $rPr .= '<w:noProof/>';
        if (!empty($options['color'])) {
            $rPr .= '<w:color w:val="' . $options['color'] . '" />';
        }
        if (!empty($options['sz'])) {
            $rPr .= '<w:sz w:val="' . (2 * $options['sz']) . '" />';
        }
        if (!empty($options['szCs'])) {
            $rPr .= '<w:szCs w:val="' . $options['sz'] . '" />';
        }
        if (!empty($options['u'])) {
            $rPr .= '<w:u w:val="' . $options['u'] . '" />';
        }
        $rPr .= '</w:rPr>';

        return $rPr;
    }

}
