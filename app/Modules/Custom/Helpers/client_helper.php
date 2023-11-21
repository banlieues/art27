<?php

if(! function_exists('generic_crm_email')) :
    function generic_crm_email()
    {
        $config = new \Custom\Config\Email();

        $generic_crm_email = (object) [
            'name' => 'Homegrade',
            'lastname' => '',
            // 'email' => 'frameworker@banlieues.be',
            'email' => $config->SMTPUser,
        ];

        return $generic_crm_email;
    }
endif;

 
if (!function_exists('signature')) :
    function signature()
    {
        $view = '
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

        // $view = '
        //     <div style="margin-top: 15px">
        //         <a href="https://www.homegrade.brussels" >
        //             <img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg">
        //         </a>
        //     </div>
        //     <div style="margin-top: 10px; font-size: 10px">
        //         <span style="color: #3C3C3C;">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span>
        //         <br/>
        //         <span style="color: #F02D37"><strong>Tel</strong></span>
        //         <span style="color: #3C3C3C;"> 1810</span>
        //         <br/>
        //         <span style="color: #F02D37"><strong>@</strong></span>
        //         <a href="mailto:info@homegrade.brussels">
        //         <span style="color: #3C3C3C;">info@homegrade.brussels</span>
        //         </a>
        //     </div>
        //     <div style="margin-top: 5px; font-size: 10px">
        //         <a href="https://www.homegrade.brussels" style="color: #000000; text-decoration: none;">
        //             <span style="color: #F02D37"><strong>www.homegrade.brussels</strong></span>
        //         </a>
        //     </div>
        //     <div style="margin-top: 15px">
        //         <a href="https://www.homegrade.brussels" >
        //             <img src="http://homegrade.brussels/img/SignatureBoiteInfo_infotemp.jpg">
        //         </a>
        //     </div>
        // ';
      
        return $view; 
    }
endif;
 
if (!function_exists('signatures_old')) :
    function signatures_old()
    {
        $signatures_old = [];

        $signatures_old[] = '<div style="margin-top: 5px">
        <a href="https://homegrade.brussels">
            <img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg">
        </a>
    </div>
    <div style="margin-top: 10px; font-size: 10px">
        <span style="color: #3C3C3C;">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br>
        <span style="color: #F02D37"><strong>Tel</strong></span> <span style="color: #3C3C3C;"> 1810</span>
    </div>
    <div style="margin-top: 5px; font-size: 10px">
        <a href="https://www.homegrade.brussels" style="color: #000000; text-decoration: none;">
            <span style="color: #F02D37"><strong>www.homegrade.brussels</strong></span>
        </a>
    </div>
    <div style="margin-top: 15px">
        <a href="https://www.homegrade.brussels">
            <img src="https://homegrade.brussels/wp-content/uploads/source_Homegrade/HG_SignatureMail_AffluenceTelephonev5pt.jpg">
        </a>
    </div>';

        $signatures_old[] = '<div style="margin-top: 15px">
                <a href="https://www.homegrade.brussels" >
                    <img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg">
                </a>
            </div>
            <div style="margin-top: 10px; font-size: 10px">
                <span style="color: #3C3C3C;">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span>
                <br/>
                <span style="color: #F02D37"><strong>Tel</strong></span>
                <span style="color: #3C3C3C;"> 1810</span>
                <br/>
                <span style="color: #F02D37"><strong>@</strong></span>
                <a href="mailto:info@homegrade.brussels">
                <span style="color: #3C3C3C;">info@homegrade.brussels</span>
                </a>
            </div>
            <div style="margin-top: 5px; font-size: 10px">
                <a href="https://www.homegrade.brussels" style="color: #000000; text-decoration: none;">
                    <span style="color: #F02D37"><strong>www.homegrade.brussels</strong></span>
                </a>
            </div>
            <div style="margin-top: 15px">
                <a href="https://www.homegrade.brussels" >
                    <img src="http://homegrade.brussels/img/SignatureBoiteInfo_infotemp.jpg">
                </a>
            </div>';
      
        return $signatures_old; 
    }
endif;
 
if (!function_exists('signatures_all')) :
    function signatures_all() {
        $signatures = signatures_old();
        $signatures[] = signature();
        $signatures[] = '<div style="margin-top:5px"><a href="https://homegrade.brussels"><img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg"> </a></div>';
        $signatures[] = '<div style="margin-top:5px; font-size:10px"><a href="https://www.homegrade.brussels" style="color:#000000; text-decoration:none"><span style="color:#F02D37"><strong>www.homegrade.brussels</strong></span> </a></div>';
        $signatures[] = '<div style="margin-top:10px; font-size:10px"><span style="color:#3C3C3C">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br><span style="color:#F02D37"><strong>Tel</strong></span> <span style="color:#3C3C3C">1810</span> </div>';
        $signatures[] = '<div class="" style="margin-top:15px"><a href="https://www.homegrade.brussels/" class=""><img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg" class=""></a></div>';
        $signatures[] = '<div style="margin-top:15px"><a href="https://www.homegrade.brussels"><img src="https://homegrade.brussels/wp-content/uploads/source_Homegrade/HG_SignatureMail_AffluenceTelephonev5pt.jpg"> </a></div>';
        $signatures[] = '<div class="" style="margin-top:10px; font-size:10px"><span class="" style="color:#3C3C3C">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br class=""><span class="" style="color:#F02D37"><strong class="">Tel</strong></span><span class="" style="color:#3C3C3C"> 1810</span><br class=""><span class="" style="color:#F02D37"><strong class="">@</strong></span> <a href="mailto:info@homegrade.brussels" class=""><span class="" style="color:#3C3C3C">info@homegrade.brussels</span></a></div>';
        $signatures[] = '<div class="" style="margin-top:5px; font-size:10px"><a href="https://www.homegrade.brussels/" class="" style="text-decoration:none"><span class="" style="color:#F02D37"><strong class="">www.homegrade.brussels</strong></span></a></div>';
        $signatures[] = '<div class="" style="margin-top:15px"><a href="https://www.homegrade.brussels/" class=""><img src="http://homegrade.brussels/img/SignatureBoiteInfo_infotemp.jpg" class=""></a></div>';
        $signatures[] = '<div class="" style="margin-top:10px; font-size:10px"><span class="" style="color:rgb(60,60,60)">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br class=""><span class="" style="color:rgb(240,45,55)"><strong class="">Tel</strong></span><span class="" style="color:rgb(60,60,60)"><span class="x_Apple-converted-space">&nbsp;</span>1810</span><br class=""><span class="" style="color:rgb(240,45,55)"><strong class="">@</strong></span><span class="x_Apple-converted-space">&nbsp;</span><a href="mailto:info@homegrade.brussels" class=""><span class="" style="color:rgb(60,60,60)">info@homegrade.brussels</span></a></div>';
        $signatures[] = '<div class="" style="margin-top:5px; font-size:10px"><a href="https://www.homegrade.brussels/" class="" style="text-decoration:none"><span class="" style="color:rgb(240,45,55)"><strong class="">www.homegrade.brussels</strong></span></a></div>';
        $signatures[] = '<div class="" style="margin-top:5px"><a href="https://homegrade.brussels/" class=""><img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg" class=""> </a></div>';
        $signatures[] = '<div class="" style="margin-top:10px; font-size:10px"><span class="" style="color:#3C3C3C">Place Quetelet / Queteletplein 7 - 1210 Bruxelles / Brussel</span><br class=""><span class="" style="color:#F02D37"><strong class="">Tel</strong></span> <span class="" style="color:#3C3C3C">1810</span> </div>';
        $signatures[] = '<div class="" style="margin-top:5px; font-size:10px"><a href="https://www.homegrade.brussels/" class="" style="text-decoration:none"><span class="" style="color:#F02D37"><strong class="">www.homegrade.brussels</strong></span> </a></div>';
        $signatures[] = '<div class="" style="margin-top:15px"><a href="https://www.homegrade.brussels/" class=""><img src="https://homegrade.brussels/wp-content/uploads/source_Homegrade/HG_SignatureMail_AffluenceTelephonev5pt.jpg" class=""> </a></div>';
        $signatures[] = '<div style="margin-top:5px"><a href="https://homegrade.brussels"><img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg" data-unique-identifier=""> </a></div>';
        $signatures[] = '<div style="margin-top:15px"><a href="https://www.homegrade.brussels"><img src="https://homegrade.brussels/wp-content/uploads/source_Homegrade/HG_SignatureMail_AffluenceTelephonev5pt.jpg" data-unique-identifier=""> </a></div>';
        $signatures[] = '<div class="" style="margin-top:5px"><a href="https://homegrade.brussels/" class=""><img src="http://homegrade.brussels/img/SignatureBoiteInfo.jpg" class="" data-unique-identifier=""> </a></div>';
        $signatures[] = '<div class="" style="margin-top:15px"><a href="https://www.homegrade.brussels/" class=""><img src="https://homegrade.brussels/wp-content/uploads/source_Homegrade/HG_SignatureMail_AffluenceTelephonev5pt.jpg" class="" data-unique-identifier=""> </a></div>';

        return $signatures;
    }
endif;
