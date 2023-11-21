<?php

namespace Components\Libraries;
use Laminas\Barcode\Barcode;
use Laminas\Barcode\Object\Code128;
use Laminas\Barcode\Renderer\Image;

Class BarCodeGeneratorLibrary
{

    public function generate($text)
    {
       
        $barcode = new Code128([
            'text' => $text,
            'barHeight' => 60,
            'factor' => 2,
        ]);
        
        $renderer = new Image([
            'resource' => imagecreate($barcode->getWidth(), $barcode->getHeight()),
            'barcode' => $barcode,
        ]);
        
       ob_start();
            $renderer->render();
            $image = base64_encode(ob_get_contents());
        ob_end_clean();
        // echo '<img src="data:image/png;base64,' . $image . '">';
       return $image;

       //return view("Barcode\generatedbarcode",(array) $this->datas);
        

    }



}