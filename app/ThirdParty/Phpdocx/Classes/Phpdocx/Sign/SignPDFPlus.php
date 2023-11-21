<?php
namespace Phpdocx\Sign;

use Phpdocx\Logger\PhpdocxLogger;
/**
 * Sign a PDF file allowing multiple signatures
 *
 * @category   Phpdocx
 * @package    sign
 * @copyright  Copyright (c) Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    phpdocx LICENSE
 * @link       https://www.phpdocx.com
 */
class SignPDFPlus implements Sign
{
    /**
     * @access private
     * @var string
     */
    private $_password;

    /**
     * @access private
     * @var string
     */
    private $_pdf;

    /**
     * @access private
     * @var array
     */
    private $_privatekey;

    /**
     * @access private
     * @var string
     */
    private $_x509Certificate;

    /**
     * Setter $_pdf
     */
    public function setPDF($file)
    {
        require_once dirname(__FILE__) . '/../Libs/SAP_lib.php';

        if (is_file($file)) {
            $this->_pdf = \Phpdocx\Libs\PDFDoc::from_string(file_get_contents($file));
        } else {
            PhpdocxLogger::logger('The file does not exist', 'fatal');
        }
    }

    /**
     * Setter $_privatekey
     */
    public function setPrivateKey($file, $password = null)
    {
        if (is_file($file)) {
            $this->_privatekey['file'] = file_get_contents($file);
            $this->_privatekey['password'] = $password;
            $this->_privatekey['pkcs12'] = false;
        } else {
            PhpdocxLogger::logger('The file does not exist', 'fatal');
        }
    }

    /**
     * Setter $_privatekey
     */
    public function setPrivateKeyPkcs12($file, $password = null)
    {
        if (is_file($file)) {
            $this->_privatekey['file'] = file_get_contents($file);
            $this->_privatekey['password'] = $password;
            $this->_privatekey['pkcs12'] = true;
        } else {
            PhpdocxLogger::logger('The file does not exist', 'fatal');
        }
    }

    /**
     * Setter $_x509Certificate
     */
    public function setX509Certificate($file)
    {
        if (is_file($file)) {
            $this->_x509Certificate = file_get_contents($file);
        } else {
            PhpdocxLogger::logger('The file does not exist', 'fatal');
        }
    }

    /**
     * Sign PDF
     * 
     * @access public
     * @param string $target PDF file output
     * @param array $optionsSignature Optional, signature options:
     *     'x' (float) abscissa of the upper-left corner. 0 if not set
     *     'y' (float) ordinate of the upper-left corner. 0 if not set
     *     'w' (float) width of the signature area. Image width if not set
     *     'h' (float) height of the signature area. Image height if not set
     *     'page' (int) page number
     * @param string $optionsImage Optional, image to add in PDF as sign:
     *     'src' (string) image file path
     */
    public function sign($target, $optionsSignature = null, $optionsImage = null)
    {
        if (is_array($optionsSignature) && is_array($optionsImage)) {
            if (!is_file($optionsImage['src'])) {
                PhpdocxLogger::logger('The image does not exist', 'fatal');
            }
            $imageSize = getimagesize($optionsImage['src']);
            if (!isset($optionsSignature['page'])) {
                $optionsSignature['page'] = 1;
            }
            if (!isset($optionsSignature['x'])) {
                $optionsSignature['x'] = 0;
            }
            if (!isset($optionsSignature['y'])) {
                $optionsSignature['y'] = 0;
            }
            if (!isset($optionsSignature['w'])) {
                $optionsSignature['w'] = $optionsSignature['x'] + $imageSize[0];
            }
            if (!isset($optionsSignature['h'])) {
                $optionsSignature['h'] = $optionsSignature['y'] + $imageSize[1];
            }
            $this->_pdf->set_signature_appearance((int)$optionsSignature['page'] - 1, array($optionsSignature['x'], $optionsSignature['y'], $optionsSignature['x'] + $optionsSignature['w'], $optionsSignature['y'] + $optionsSignature['h']), $optionsImage['src']);
        }
        $this->_pdf->set_signature_certificate(array('cert' => $this->_x509Certificate, 'pkey' => $this->_privatekey['file'], 'pcks12' => $this->_privatekey['pkcs12']), $this->_privatekey['password']);
        $docsigned = $this->_pdf->to_pdf_file_s();

        file_put_contents($target, $docsigned);
    }
}
