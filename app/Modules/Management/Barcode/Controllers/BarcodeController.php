<?php

namespace Barcode\Controllers;

use Base\Controllers\BaseController;

use Layout\Libraries\LayoutLibrary;

use Barcode\Models\BarcodeModel;

use Components\Libraries\ComponentOrderBy;
use Components\Libraries\BarCodeGeneratorLibrary;

use Dompdf\Dompdf;
use Dompdf\Options;



class BarcodeController extends BaseController
{
    public function __construct()
    {
        if(session()->get("loggedUserRoleId")==2)
        {
            header("Location:" . base_url("user"));
        }
            if(session()->get("loggedUserRoleId")!=1)
        {
            header("Location:" . base_url("identification/logout"));
        }


        $this->barcodemodel = new BarcodeModel();
        $this->context = "barcode";

        $this->componentOrderBy=new ComponentOrderBy();
        

        
      

        parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();


        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;
        $this->viewpath = $this->module . "\Views\/";  


//debug($this->viewpath);
//debugd($this->viewpath);

    }


    public function index()
    {

      
        $this->datas->partenaires=$this->barcodemodel->list_get_partenaire_sociaux();
      
        return view("Barcode\list-barcode",(array) $this->datas);
    }

    //fonction de génération de barcode(résultat dans le view)
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