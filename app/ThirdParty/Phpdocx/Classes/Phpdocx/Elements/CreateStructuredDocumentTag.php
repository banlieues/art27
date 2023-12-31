<?php
namespace Phpdocx\Elements;
/**
 * Create structured document tag
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    phpdocx LICENSE
 * @link       https://www.phpdocx.com
 */
class CreateStructuredDocumentTag
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
     * @return CreateText
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateStructuredDocumentTag();
        }
        return self::$_instance;
    }

    /**
     * Create Structured Document Tag
     *
     * @access public
     * @param mixed $args[0]
     */
    public function createStructuredDocumentTag()
    {
        $args = func_get_args();
        $this->_xml = '';
        $lockValues = array('sdtLocked', 'contentLocked', 'unlocked', 'sdtContentLocked');
        $id = rand(100000000, 999999999);

        //1. First construct the sdtPr element(structured document tag properties)     
        $sdtPr = '<w:sdtPr>';
        $sdtPr .= $this->generateRPr($args[1]);
        if (!empty($args[1]['alias'])) {
            $sdtPr .= '<w:alias w:val="' . htmlspecialchars($args[1]['alias']) . '" />';
        }
        if (!empty($args[1]['lock']) && in_array($args[1]['lock'], $lockValues)) {
            $sdtPr .= '<w:lock w:val="' . $args[1]['lock'] . '" />';
        }
        $sdtPr .= '<w:id w:val="' . $id . '" />';
        if (!empty($args[1]['tag'])) {
            $sdtPr .= '<w:tag w:val="' . $args[1]['tag'] . '" />';
        }
        if (isset($args[1]['temporary']) && $args[1]['tag'] == true) {
            $sdtPr .= '<w:temporary />';
        }
        // now we have to add the elements associated to each particular type
        // combo box
        if (isset($args[0]) && ($args[0] == 'comboBox' || $args[0] == 'dropDownList')) {
            $sdtPr .= '<w:' . $args[0] . '>';
            if (!empty($args[1]['listItems']) && is_array($args[1]['listItems'])) {
                foreach ($args[1]['listItems'] as $key => $value) {
                    $sdtPr .= '<w:listItem w:displayText="' . htmlspecialchars($value[0]) . '" w:value="' . htmlspecialchars($value[1]) . '" />';
                }
            }
            $sdtPr .= '</w:' . $args[0] . '>';
        }
        // date format
        if (isset($args[0]) && $args[0] == 'date') {
            $sdtPr .= '<w:date>';
            if (!empty($args[1]['dateFormat'])) {
                $sdtPr .= '<w:dateFormat w:val="' . $args[1]['dateFormat'] . '" />';
            } else {
                $sdtPr .= '<w:dateFormat w:val="M/d/yyyy" />';
            }
            if (!empty($args[1]['local'])) {
                $sdtPr .= '<w:lid w:val="' . $args[1]['local'] . '" />';
            } else {
                $sdtPr .= '<w:lid w:val="en-US" />';
            }
            if (!empty($args[1]['calendar'])) {
                $sdtPr .= '<w:calendar w:val="' . $args[1]['calendar'] . '" />';
            } else {
                $sdtPr .= '<w:calendar w:val="gregorian" />';
            }
            $sdtPr .= '</w:date>';
        }
        // rich text
        if (isset($args[0]) && $args[0] == 'richText') {
            if (isset($args[1]['placeholderText']) && !empty(isset($args[1]['placeholderText']))) {
                $sdtPr .= '<w:showingPlcHdr/>';
            }
            $sdtPr .= '<w:richText />';
        }

        // checkbox
        if (isset($args[0]) && $args[0] == 'checkbox') {
            $sdtPr .= '<w14:checkbox xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml">';
            if (isset($args[1]['checked'])) {
                if ($args[1]['checked'] === true) {
                    $sdtPr .= '<w14:checked w14:val="1"/>';
                } else {
                    $sdtPr .= '<w14:checked w14:val="0"/>';
                }
            } else {
                $sdtPr .= '<w14:checked w14:val="0"/>';
            }
            $sdtPr .= '</w14:checkbox>';
        }

        $sdtPr .= '</w:sdtPr>';


        //2. Construct the sdtContent element (structured document tag content)        
        $sdtContent = '<w:sdtContent>' . $args[2] . '</w:sdtContent>';

        //3. Now we build the whole sdt tag
        $this->_xml = '<w:sdt>' . $sdtPr . $sdtContent . '</w:sdt>';
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
