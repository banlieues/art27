<?php
namespace Phpdocx\Elements;
/**
 * Create SVG images
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    phpdocx LICENSE
 * @link       https://www.phpdocx.com
 */
class CreateSVG extends CreateElement
{
    const NAMESPACEWORD = 'wp';
    const NAMESPACEWORD1 = 'a';
    const NAMESPACEWORD2 = 'pic';
    const CONSTWORD = 360000;
    const BORDERSIZE = 12700;
    const PNG_SCALE_FACTOR = 29.5;

    /**
     * @access private
     * @var CreateSVG
     * @static
     */
    private static $_instance = NULL;

    /**
     *
     * @access private
     * @var int
     */
    private $_rId;

    /**
     *
     * @access private
     * @var int
     */
    private $_rIdSVG;

    /**
     *
     * @access private
     * @var string
     */
    private $_textWrap;

    /**
     *
     * @access private
     * @var string
     */
    private $_name;

    /**
     *
     * @access private
     * @var int
     */
    private $_sizeX;

    /**
     *
     * @access private
     * @var int
     */
    private $_sizeY;

    /**
     *
     * @access private
     * @var int
     */
    private $_spacingTop;

    /**
     *
     * @access private
     * @var int
     */
    private $_spacingBottom;

    /**
     *
     * @access private
     * @var int
     */
    private $_spacingLeft;

    /**
     *
     * @access private
     * @var int
     */
    private $_spacingRight;

    /**
     *
     * @access private
     * @var int
     */
    private $_jc;

    /**
     *
     * @access private
     * @var string
     */
    private $_border;

    /**
     *
     * @access private
     * @var string
     */
    private $_borderDiscontinuous;

    /**
     *
     * @access private
     * @var int
     */
    private $_scaling;

    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {
        $this->_rId = '';
        $this->_textWrap = '';
        $this->_sizeX = '';
        $this->_sizeY = '';
        $this->_spacingTop = '';
        $this->_spacingBottom = '';
        $this->_spacingLeft = '';
        $this->_spacingRight = '';
        $this->_jc = '';
        $this->_border = '';
        $this->_borderDiscontinuous = '';
        $this->_scaling = '';
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
     *
     * @return string
     * @access public
     */
    public function __toString()
    {
        return $this->_xml;
    }

    /**
     *
     * @return CreateSVG
     * @access public
     * @static
     */
    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new CreateSVG();
        }
        return self::$_instance;
    }

    /**
     * Setter name
     *
     * @access public
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Getter name
     *
     * @access public
     * @return <type>
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Setter rId
     *
     * @access public
     * @param string $rId
     */
    public function setRId($rId)
    {
        $this->_rId = $rId;
    }

    /**
     * Getter rId
     *
     * @access public
     * @return <type>
     */
    public function getRId()
    {
        return $this->_rId;
    }

    /**
     * Setter rIdSVG
     *
     * @access public
     * @param string $rId
     */
    public function setRIdSVG($rIdSVG)
    {
        $this->_rIdSVG = $rIdSVG;
    }

    /**
     * Getter rIdSVG
     *
     * @access public
     * @return <type>
     */
    public function getRIdSVG()
    {
        return $this->_rIdSVG;
    }

    /**
     * Create image
     *
     * @access public
     * @param array $args[0]
     */
    public function createSVG()
    {
        $this->_xml = '';
        $this->_rId = '';
        $this->_rIdSVG = '';
        $this->_textWrap = '';
        $this->_sizeX = '';
        $this->_sizeY = '';
        $this->_spacingTop = '';
        $this->_spacingBottom = '';
        $this->_spacingLeft = '';
        $this->_spacingRight = '';
        $this->_jc = '';
        $this->_border = '';
        $this->_borderDiscontinuous = '';
        $this->_scaling = '';
        $args = func_get_args();

        if (isset($args[0]['rId'])) {
            if (!isset($args[0]['textWrap']) || $args[0]['textWrap'] < 0 ||
                    $args[0]['textWrap'] > 5
            ) {
                $textWrap = 0;
            } else {
                $textWrap = $args[0]['textWrap'];
            }

            $tamPxX = $args[0]['sizeX'];
            $tamPxY = $args[0]['sizeY'];

            $this->_name = 'SVG' . $args[0]['rId'];
            $this->setRId($args[0]['rId']);
            $this->setRIdSVG($args[0]['rIdSVG']);
            $top = '0';
            $bottom = '0';
            $left = '0';
            $right = '0';

            if (!isset($args[0]['dpi'][0])) {
                $args[0]['dpi'][0] = 96;
            }
            if (!isset($args[0]['dpi'][1])) {
                $args[0]['dpi'][1] = 96;
            }

            list($dpiX, $dpiY) = $args[0]['dpi'];
            $tamWordX = round($tamPxX * 2.54 / $dpiX * CreateSVG::CONSTWORD);
            $tamWordY = round($tamPxY * 2.54 / $dpiY * CreateSVG::CONSTWORD);

            if (isset($args[0]['spacingTop'])) {
                $top = round(
                        $args[0]['spacingTop'] * 2.54 /
                        $dpiX * CreateSVG::CONSTWORD
                );
            }
            if (isset($args[0]['spacingBottom'])) {
                $bottom = round(
                        $args[0]['spacingBottom'] * 2.54 /
                        $dpiX * CreateSVG::CONSTWORD
                );
            }
            if (isset($args[0]['spacingLeft'])) {
                $left = round(
                        $args[0]['spacingLeft'] * 2.54 /
                        $dpiX * CreateSVG::CONSTWORD
                );
            }
            if (isset($args[0]['spacingRight'])) {
                $right = round(
                        $args[0]['spacingRight'] * 2.54 /
                        $dpiX * CreateSVG::CONSTWORD
                );
            }

            $this->generateP();
            if (isset($args[0]['imageAlign'])) {
                $this->generatePPR();
                $this->generateJC($args[0]['imageAlign']);
            }
            $this->generateR();
            $this->generateRPR();
            $this->generateNOPROOF();
            $this->generateDRAWING();
            if ($textWrap == 0) {
                $this->generateINLINE();
            } else {
                if ($textWrap == 3) {
                    $this->generateANCHOR(1);
                } else {
                    $this->generateANCHOR();
                }
                $this->generateSIMPLEPOS();
                if (isset($args[0]['relativeToHorizontal'])) {
                    $this->generatePOSITIONH($args[0]['relativeToHorizontal']);
                } else {
                    $this->generatePOSITIONH();
                }
                if (isset($args[0]['float']) && ($args[0]['float'] == 'left' || $args[0]['float'] == 'right' || $args[0]['float'] == 'center')) {
                    $this->generateALIGN($args[0]['float']);
                }
                if (isset($args[0]['horizontalOffset']) && is_numeric($args[0]['horizontalOffset'])) {
                    $this->generatePOSOFFSET($args[0]['horizontalOffset']);
                } else {
                    $this->generatePOSOFFSET(0);
                }
                if (isset($args[0]['relativeToVertical'])) {
                    $this->generatePOSITIONV($args[0]['relativeToVertical']);
                } else {
                    $this->generatePOSITIONV();
                }
                if (isset($args[0]['verticalAlign'])) {
                    $this->generateALIGN($args[0]['verticalAlign']);
                }
                if (isset($args[0]['verticalOffset']) && is_numeric($args[0]['verticalOffset'])) {
                    $this->generatePOSOFFSET($args[0]['verticalOffset']);
                } else {
                    $this->generatePOSOFFSET(0);
                }
            }

            $this->generateEXTENT($tamWordX, $tamWordY);
            $this->generateEFFECTEXTENT($left, $top, $right, $bottom);

            switch ($textWrap) {
                case 1:
                    $this->generateWRAPSQUARE();
                    break;
                case 2:
                case 3:
                    $this->generateWRAPNONE();
                    break;
                case 4:
                    $this->generateWRAPTOPANDBOTTOM();
                    break;
                case 5:
                    $this->generateWRAPTHROUGH();
                    $this->generateWRAPPOLYGON();
                    $this->generateSTART();
                    $this->generateLINETO();
                    break;
                default:
                    break;
            }
            $this->generateDOCPR();
            if (isset($args[0]['rIdHyperlink'])) {
                $this->generateHYPERLINK($args[0]['rIdHyperlink']);
            }
            $this->generateCNVGRAPHICFRAMEPR();
            $this->generateGRAPHICPRAMELOCKS(1);
            $this->generateGRAPHIC();
            $this->generateGRAPHICDATA();
            $this->generatePIC();
            $this->generateNVPICPR();
            $this->generateCNVPR();
            $this->generateCNVPICPR();
            $this->generateBLIPFILL();
            $this->generateBLIP();
            $this->generateSTRETCH();
            $this->generateFILLRECT();
            $this->generateSPPR();
            $this->generateXFRM();
            $this->generateOFF();
            $this->generateEXT($tamWordX, $tamWordY);
            $this->generatePRSTGEOM();
            $this->generateAVLST();
            if (isset($args[0]['borderStyle']) ||
                    isset($args[0]['borderWidth']) ||
                    isset($args[0]['borderColor'])) {
                //width
                if (isset($args[0]['borderWidth'])) {
                    $this->generateLN($args[0]['borderWidth'] * CreateSVG::BORDERSIZE);
                } else {
                    $this->generateLN(CreateSVG::BORDERSIZE);
                }
                //color
                if (isset($args[0]['borderColor'])) {
                    $this->generateSOLIDFILL($args[0]['borderColor']);
                } else {
                    $this->generateSOLIDFILL('000000');
                }
                //style
                if (isset($args[0]['borderStyle'])) {
                    $this->generatePRSTDASH($args[0]['borderStyle']);
                } else {
                    $this->generatePRSTDASH('solid');
                }
            }

            $this->cleanTemplate();
        } else {
            PhpdocxLogger::logger('There was an error adding the image', 'fatal');
        }
    }

    /**
     * Init image
     *
     * @access public
     * @param array $args[0]
     */
    public function initImage()
    {
        $args = func_get_args();

        if (!isset($args[0]['textWrap'])) {
            $args[0]['textWrap'] = '';
        }
        if (!isset($args[0]['sizeX'])) {
            $args[0]['sizeX'] = '';
        }
        if (!isset($args[0]['sizeY'])) {
            $args[0]['sizeY'] = '';
        }
        if (!isset($args[0]['spacingTop'])) {
            $args[0]['spacingTop'] = '';
        }
        if (!isset($args[0]['spacingBottom'])) {
            $args[0]['spacingBottom'] = '';
        }
        if (!isset($args[0]['spacingLeft'])) {
            $args[0]['spacingLeft'] = '';
        }
        if (!isset($args[0]['spacingRight'])) {
            $args[0]['spacingRight'] = '';
        }
        if (!isset($args[0]['imageAlign'])) {
            $args[0]['imageAlign'] = '';
        }
        if (!isset($args[0]['border'])) {
            $args[0]['border'] = '';
        }
        if (!isset($args[0]['borderDiscontinuous'])) {
            $args[0]['borderDiscontinuous'] = '';
        }
        if (!isset($args[0]['scaling'])) {
            $args[0]['scaling'] = '';
        }
        if (!isset($args[0]['dpi'])) {
            $args[0]['dpi'] = '';
        }

        $this->_textWrap = $args[0]['textWrap'];
        $this->_sizeX = $args[0]['sizeX'];
        $this->_sizeY = $args[0]['sizeY'];
        $this->_spacingTop = $args[0]['spacingTop'];
        $this->_spacingBottom = $args[0]['spacingBottom'];
        $this->_spacingLeft = $args[0]['spacingLeft'];
        $this->_spacingRight = $args[0]['spacingRight'];
        $this->_jc = $args[0]['imageAlign'];
        $this->_border = $args[0]['border'];
        $this->_borderDiscontinuous = $args[0]['borderDiscontinuous'];
        $this->_scaling = $args[0]['scaling'];
    }

    /**
     * Generate w:docpr
     *
     * @param string $id
     * @param string $name
     * @access protected
     */
    protected function generateDOCPR($id = "1", $name = "Picture 1")
    {
        $id = rand(999999, 999999999);
        $xml = '<' . CreateSVG::NAMESPACEWORD . ':docPr id="' . $id .
                '" name="' . $name . '" descr="' . htmlspecialchars($this->_name) .
                '">__GENERATEDOCPR__</' . CreateSVG::NAMESPACEWORD .
                ':docPr>__GENERATEINLINE__';

        $this->_xml = str_replace('__GENERATEINLINE__', $xml, $this->_xml);
    }

    /**
     * Generate w:blip
     *
     * @param string $cstate
     * @access protected
     */
    protected function generateBLIP($cstate = 'print')
    {
        $xml = '<' . CreateImage::NAMESPACEWORD1 . ':blip r:embed="rId' . $this->getRId() . '" cstate="' . $cstate . '">
                <a:extLst>
                    <a:ext uri="{28A0092B-C50C-407E-A947-70E740481C1C}">
                        <a14:useLocalDpi val="0" xmlns:a14="http://schemas.microsoft.com/office/drawing/2010/main"/>
                    </a:ext>
                    <a:ext uri="{96DAC541-7B7A-43D3-8B79-37D633B846F1}">
                        <asvg:svgBlip r:embed="rId' . $this->getRIdSVG() . '" xmlns:asvg="http://schemas.microsoft.com/office/drawing/2016/SVG/main"/>
                    </a:ext>
                </a:extLst>
                </' . CreateImage::NAMESPACEWORD1 . ':blip>__GENERATEBLIPFILL__';

        $this->_xml = str_replace('__GENERATEBLIPFILL__', $xml, $this->_xml);
    }

    /**
     * Generate w:hlinkClick
     *
     * @param string $rIdHyperlink
     * @access protected
     */
    protected function generateHYPERLINK($rIdHyperlink)
    {
        $id = rand(999999, 999999999);
        $xml = '<a:hlinkClick r:id="rId' . $rIdHyperlink .
                '" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"/>';

        $this->_xml = str_replace('__GENERATEDOCPR__', $xml, $this->_xml);
    }

}
