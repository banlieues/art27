<?php
namespace Phpdocx\Elements;

/**
 * Create bibliography
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    phpdocx LICENSE
 * @link       https://www.phpdocx.com
 */
class CreateBibliography extends CreateElement
{
    /**
     *
     * @var string
     * @access protected
     */
    protected $_xml;

    /**
     *
     * @var CreateBibliography
     * @access protected
     * @static
     */
    private static $_instance = NULL;

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
     *
     * @return CreateBibliography
     * @access public
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateBibliography();
        }
        return self::$_instance;
    }

    /**
     * Create bibliography
     *
     * @param array $options
     * @param WordFragment $legendData
     * @access public
     */
    public function createBibliography($options, $legendData)
    {
        $sdtStart = '<w:sdt><w:sdtPr><w:id w:val="' . rand(111111111, 999999999) . '" /><w:docPartObj><w:docPartGallery w:val="Bibliographies"/><w:docPartUnique/></w:docPartObj></w:sdtPr><w:sdtContent>';
        $sdtContent = '<w:sdt><w:sdtPr><w:id w:val="' . rand(111111111, 999999999) . '"/><w:bibliography/></w:sdtPr><w:sdtContent><w:p><w:r><w:fldChar w:fldCharType="begin"/></w:r><w:r><w:instrText xml:space="preserve">BIBLIOGRAPHY </w:instrText></w:r><w:r><w:fldChar w:fldCharType="separate"/></w:r><w:r><w:fldChar w:fldCharType="end"/></w:r></w:p></w:sdtContent></w:sdt>';
        $sdtEnd = '</w:sdtContent></w:sdt>';
        
        $this->_xml = $sdtStart . $legendData . $sdtContent . $sdtEnd;
    }

}
