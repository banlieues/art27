<?php
namespace Phpdocx\Sign;
/**
 * Sign a PDF file
 *
 * @category   Phpdocx
 * @package    sign
 * @copyright  Copyright (c) Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    phpdocx LICENSE
 * @link       https://www.phpdocx.com
 */
interface Sign
{
    /**
     * Setter $_privatekey
     */
    public function setPrivateKey($file, $password = null);

    /**
     * Setter $_x509Certificate
     */
    public function setX509Certificate($file);
}
