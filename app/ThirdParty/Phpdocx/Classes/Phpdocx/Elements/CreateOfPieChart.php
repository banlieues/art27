<?php
namespace Phpdocx\Elements;

use Phpdocx\Logger\PhpdocxLogger;
/**
 * Create Of pie Chart
 *
 * @category   Phpdocx
 * @package    elements
 * @copyright  Copyright (c) Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    phpdocx LICENSE
 * @link       https://www.phpdocx.com
 */
class CreateOfPieChart extends CreateGraphic implements InterfaceGraphic
{
    /**
     * Create embedded xml chart
     *
     * @access public
     */
    public function createEmbeddedXmlChart()
    {
        $this->_xmlChart = '';
        $this->generateCHARTSPACE();
        $this->generateDATE1904(1);
        $this->generateLANG();
        $this->generateROUNDEDCORNERS($this->_roundedCorners);
        $color = 2;
        if ($this->_color) {
            $color = $this->_color;
        }
        $this->generateSTYLE($color);
        $this->generateCHART();
        if ($this->_title != '') {
            $this->generateTITLE();
            $this->generateTITLETX();
            $this->generateRICH();
            $this->generateBODYPR();
            $this->generateLSTSTYLE();
            $this->generateTITLEP();
            $this->generateTITLEPPR();
            $this->generateDEFRPR('title');
            $this->generateTITLER();
            $this->generateTITLERPR();
            $this->generateTITLET($this->_title);
            $this->cleanTemplateFonts();
        } else {
            $this->generateAUTOTITLEDELETED();
            $title = '';
        }
        if (strpos($this->_type, '3D') !== false) {
            $this->generateVIEW3D();
            $rotX = 30;
            $rotY = 30;
            $perspective = 30;
            if ($this->_rotX != '') {
                $rotX = $this->_rotX;
            }
            if ($this->_rotY != '') {
                $rotY = $this->_rotY;
            }
            if ($this->_perspective != '') {
                $perspective = $this->_perspective;
            }
            $this->generateROTX($rotX);
            $this->generateROTY($rotY);
            $this->generateRANGAX($this->_rAngAx);
            $this->generatePERSPECTIVE($perspective);
        }
        if ($this->values == '') {
            PhpdocxLogger::logger('Data values are missing.', 'fatal');
        }
        $this->generatePLOTAREA();
        $this->generateLAYOUT();

        $this->generateOFPIECHART();
        $this->generateOFPIETYPE(!empty($this->_subtype) ? $this->_subtype : 'pie');
        $this->generateVARYCOLORS();
        if (isset($this->values['legend'])) {
            $legends = array($this->_title);
        } else {
            $legends = array($this->_title);
        }

        $numValues = count($this->values['data']);

        $letter = 'A';
        for ($i = 0; $i < count($legends); $i++) {
            $this->generateSER();
            $this->generateIDX($i);
            $this->generateORDER($i);
            $letter++;

            $this->generateTX();
            $this->generateSTRREF();
            $this->generateF('Sheet1!$' . $letter . '$1');
            $this->generateSTRCACHE();
            $this->generatePTCOUNT();
            $this->generatePT();
            $this->generateV($legends[$i]);
            $this->cleanTemplate2();

            if (!empty($this->_showValue) || !empty($this->_showCategory)) {
                $this->generateSERDLBLS();
                if (!empty($this->_showValue)) {
                    $this->generateSHOWVAL();
                }
                if (!empty($this->_showCategory)) {
                    $this->generateSHOWCATNAME();
                }
                if (!empty($this->_showPercent)) {
                    $this->generateSHOWPERCENT(1);
                }
            }

            if (is_array($this->_theme) && isset($this->_theme['serRgbColors']) && isset($this->_theme['serRgbColors'][$i])) {
                if ($this->_theme['serRgbColors'][$i] != null) {
                    $this->generateSPPR_SER();
                    $this->generateSPPR_SOLIDFILL($this->_theme['serRgbColors'][$i]);
                }
            }
            
            if (is_array($this->_theme) && isset($this->_theme['valueRgbColors']) && isset($this->_theme['valueRgbColors'][$i]) && $this->_theme['valueRgbColors'][$i] != null) {
                if ($this->_theme['valueRgbColors'][$i] != null) {
                    $this->generateCDPT($this->_theme['valueRgbColors'][$i]);
                }
            }

            $this->generateCAT();
            $this->generateSTRREF();
            $this->generateF('Sheet1!$A$2:$A$' . ($numValues + 1));
            $this->generateSTRCACHE();
            $this->generatePTCOUNT($numValues);

            $num = 0;
            foreach ($this->values['data'] as $value) {
                $this->generatePT($num);
                $this->generateV($value['name']);
                $num++;
            }
            $this->cleanTemplate2();
            $this->generateVAL();
            $this->generateNUMREF();
            $this->generateF('Sheet1!$' . $letter . '$2:$B$' . ($numValues + 1));
            $this->generateNUMCACHE();
            $this->generateFORMATCODE();
            $this->generatePTCOUNT($numValues);
            $num = 0;
            foreach ($this->values['data'] as $name => $value) {
                $this->generatePT($num);
                $this->generateV($value['values'][$i]);
                $num++;
            }
            $this->cleanTemplate3();
        }
        $this->generateDLBLS();
        $this->generateSHOWLEGENDKEY($this->_showLegendKey);
        $this->generateSHOWVAL($this->_showValue);
        $this->generateSHOWCATNAME($this->_showCategory);
        $this->generateSHOWSERNAME($this->_showSeries);
        $showPercent = 0;
        if (!empty($this->_showPercent)) {
            $showPercent = 1;
        }
        $this->generateSHOWPERCENT($showPercent);
        if (!empty($this->_gapWidth)) {
            $this->generateGAPWIDTH($this->_gapWidth);
        }
        else {
            $this->generateGAPWIDTH();
        }
        if (!empty($this->_splitType)) {
            $this->generateSPLITTYPE($this->_splitType);
            if (!empty($this->_splitPos)) {
                $this->generateSPLITPOS($this->_splitPos, $this->_splitType);
            }
            if ($this->_splitType == 'cust' && !empty($this->_custSplit) && is_array($this->_custSplit)) {
                $this->generateCUSTSPLIT();
                $this->generateSECONDPIEPT($this->_custSplit);
            }
        }

        if (!empty($this->_secondPieSize)) {
            $this->generateSECONDPIESIZE($this->_secondPieSize);
        } else {
            $this->generateSECONDPIESIZE();
        }
        $this->generateSERLINES();

        $this->generateLEGEND();
        $this->generateLEGENDPOS($this->_legendPos);
        $this->generateLEGENDOVERLAY($this->_legendOverlay);
        $this->generatePLOTVISONLY();

        if ((empty($this->_border) || $this->_border == 0 || !is_numeric($this->_border))
        ) {
            $this->generateSPPR();
            $this->generateLN();
            $this->generateNOFILL();
        } else {
            $this->generateSPPR();
            $this->generateLN($this->_border);
        }

        if ($this->_font != '') {
            $this->generateTXPR();
            $this->generateLEGENDBODYPR();
            $this->generateLSTSTYLE();
            $this->generateAP();
            $this->generateAPPR();
            $this->generateDEFRPR();
            $this->generateRFONTS($this->_font);
            $this->generateENDPARARPR();
        }

        $this->generateEXTERNALDATA();
        $this->cleanTemplateDocument();
        
        return $this->_xmlChart;
    }

    public function dataTag()
    {
        return array('val');
    }

    /**
     * retun the type of the xlsx object
     *
     * @access public
     */
    public function getXlsxType()
    {
        return CreateSimpleXlsx::getInstance();
    }

}
