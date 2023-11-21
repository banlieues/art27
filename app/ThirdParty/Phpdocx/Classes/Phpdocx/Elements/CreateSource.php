<?php
namespace Phpdocx\Elements;
/**
 * Create source
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    phpdocx LICENSE
 * @link       https://www.phpdocx.com
 */
class CreateSource
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
            self::$_instance = new CreateSource();
        }
        return self::$_instance;
    }

    /**
     * Create Structured Document Tag
     *
     * @access public
     */
    public function createSource()
    {
        $args = func_get_args();
        $tag = '<b:Tag>' . $args[0] . '</b:Tag>';
        $type = '<b:SourceType>' . $args[1] . '</b:SourceType>';
        $customGUID = $this->generateGUID();
        $guid = '<b:Guid>' . $customGUID['guid'] . '</b:Guid>';
        $author = '';
        if (is_array($args[2])) {
            $author .= '<b:Author>';
            foreach ($args[2] as $authorKey => $authorContent) {
                $author .= '<b:' .  $authorKey .'><b:NameList>';
                // iterate author types
                foreach ($authorContent as $authorContentsKey => $authorContentsValue) {
                    $author .= '<b:Person>';
                    // iterate authors
                    foreach ($authorContentsValue as $authorContentsPreffix => $authorContentsName) {
                        $author .= '<b:' . $authorContentsPreffix . '>' . htmlspecialchars($authorContentsName) . '</b:' . $authorContentsPreffix . '>';
                    }
                    $author .= '</b:Person>';
                }
                $author .= '</b:NameList></b:' .  $authorKey .'>';
            }
            $author .= '</b:Author>';
        }
        $info = '';
        if (is_array($args[3])) {
            foreach ($args[3] as $infoKey => $infoValue) {
                $info .= '<b:' . $infoKey . '>' . htmlspecialchars($infoValue) . '</b:' . $infoKey . '>';
            }
        }
        
        $this->_xml = '<b:Source>' . $tag . $type . $guid . $author . $info . '</b:Source>';
    }

    /**
     * Generates a UID
     * @return array GUID and RAW GUID
     */
    protected function generateGUID()
    {
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // '-'
        $uuid = chr(123) // '{'
            . substr($charid, 0, 3) . '14A78' . $hyphen
            . '8E89' . $hyphen
            . '426F' . $hyphen
            . '90D8' . $hyphen
            . '5CD89AEFD' . substr($charid, 20, 3)
            . chr(125); // '}'

        // force an UUID, as DOCX allows using the same UUID for more than font
        //$uuid = '{23BA4462-8E89-426F-90D8-59D98759585D}';

        return array('guid' => $uuid, 'rawguid' => str_replace(array('{', '}', '-'), '', $uuid));
    }
}