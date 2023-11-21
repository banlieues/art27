<?php  
if(! function_exists('affiche_balise'))
{
    function affiche_balise($string){

        $result = str_replace('<', '&lt', $string);
		return str_replace('>', '&gt', $result);
    }
}



function is_lu_message($lus,$id_user)
{
    $liste_lu=explode(",",$lus);
    //debug($lus);
    //debug($id_user);
    if(in_array($id_user,$liste_lu))
    {
        return TRUE;
    }
        

    return FALSE;
}


if(!function_exists('is_reference_outlook'))
{
    function is_reference_outlook($objet){
        if(mb_substr_count($objet,"#")>1):
            return TRUE;
        else : 
            return FALSE;
        endif;
    }
}

if(! function_exists('findCase'))
{
    function findCase($objet=""){

		$case = preg_replace('/\D/','',$objet);
    	return $case;
    }
}

if(! function_exists('get_string_between'))
{
	function get_string_between($string, $start, $end){
		
	    $string = ' ' . $string;
	    $ini = strpos($string, $start);
	    if ($ini == 0): return ''; endif;
	    $ini += strlen($start);
	    $len = strpos($string, $end, $ini) - $ini;
	    return substr($string, $ini, $len);
	}
}

if(! function_exists('signature_homegrade_old'))
{
    function signature_homegrade_old(){
    	$view="";
        $view.="<br><br>";
        $view.="<img src='".base_url()."assets/demandes/documents/signature_homegrade_mail.png'/>";

        return $view;
    }
}



if(! function_exists('signature_homegrade'))
{
    

     function signature_homegrade(){
	 
	 $view="";
	 $view.='
        <div style="margin-top: 5px">
            <a href="https://homegrade.brussels" >
                <img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg">
            </a>
        </div>
        <div style="margin-top: 10px; font-size: 10px">
            <span style="color: #3C3C3C;">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br/>
            <span style="color: #F02D37"><strong>Tel</strong></span> <span style="color: #3C3C3C;"> 1810</span>
        </div>
        <div style="margin-top: 5px; font-size: 10px">
            <a href="https://www.homegrade.brussels" style="color: #000000; text-decoration: none;">
                <span style="color: #F02D37"><strong>www.homegrade.brussels</strong></span>
            </a>
        </div>
        <div style="margin-top: 15px">
            <a href="https://www.homegrade.brussels" >
                <img src="https://homegrade.brussels/wp-content/uploads/source_Homegrade/HG_SignatureMail_AffluenceTelephonev5pt.jpg">
            </a>
        </div>
    ';

// 	 $view.='<div style="margin-top: 15px"><a href="https://www.homegrade.brussels" ><img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg"></a></div>
// <div style="margin-top: 10px; font-size: 10px"><span style="color: #3C3C3C;">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br/>
// <span style="color: #F02D37"><strong>Tel</strong></span><span style="color: #3C3C3C;"> 1810</span><br/>
// <span style="color: #F02D37"><strong>@</strong></span> <a href="mailto:info@homegrade.brussels"> <span style="color: #3C3C3C;">info@homegrade.brussels</span></a></div>
// <div style="margin-top: 5px; font-size: 10px"><a href="https://www.homegrade.brussels" style="color: #000000; text-decoration: none;"><span style="color: #F02D37"><strong>www.homegrade.brussels</strong></span></a></div>
// <div style="margin-top: 15px"><a href="https://www.homegrade.brussels" ><img src="http://homegrade.brussels/img/SignatureBoiteInfo_infotemp.jpg"></a></div>';

//	 $view.='<a href="https://www.homegrade.brussels/"><span style="font-family:Arial;,sans-serif;color:blue;mso-fareast-language:FR-BE;text-decoration:none"><img border="0" width="250" height="91" id="_x0000_i1026" 
//	     src="'.base_url().'assets/demandes/documents/SignatureBoiteInfo.jpg" alt="'.base_url().'assets/demandes/documents/SignatureBoiteInfo.jpg">
//	     </span></a><span style="font-size:12.0pt;font-family;Arial;,sans-serif;mso-fareast-language:FR-BE"><o:p></o:p></span><br>';
//	 
//	 $view.='<span style="font-size:7.5pt;font-family:Arial,sans-serif;color:black;mso-fareast-language:FR-BE">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span>
//	     <span style="font-size:7.5pt;font-family:Arial,sans-serif;mso-fareast-language:FR-BE"><br>
//	     <b><span style="color:#F02D37">Tel</span></b><span style="color:#3C3C3C"> </span>
//	    <span style="color:black">1810</span><br><b><span style="color:#F02D37">@</span></b> <span style="color:black">
//	    <a href="mailto:info@homegrade.brussels"><span style="color:black; font-size:7.5pt!important">info@homegrade.brussels</span></a></span><o:p></o:p></span><br>';
//	 
//	$view.='<a href="https://www.homegrade.brussels/"><b><span style="color:#F02D37;text-decoration:none; font-size:7.5pt!important">www.homegrade.brussels</span></b></a><o:p></o:p></span></p>
//<p class="MsoNormal"><a href="https://www.homegrade.brussels/"><span style="font-family:Arial,sans-serif;color:blue;mso-fareast-language:FR-BE;text-decoration:none">
//<img border="0" width="350" height="51" id="_x0000_i1025" src="'.base_url().'assets/demandes/documents/SignatureBoiteInfo_infotemp.jpg" alt="'.base_url().'assets/demandes/documents/SignatureBoiteInfo_infotemp.jpg"></span></a>
//<span style="font-size:12.0pt;font-family:Arial,sans-serif;mso-fareast-language:FR-BE"><o:p></o:p></span></p>
//<p class="MsoNormal"><o:p>&nbsp;</o:p>'; 
	
   return $view;
	 
}}
