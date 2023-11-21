<?php
// Calcul_Helper

if (!function_exists('calcul_ht'))
{
    function calcul_ht($pu, $quantity)
    {
        if(empty($pu)) return 0;

        return $pu*$quantity;
    }
}

if (!function_exists('calcul_tva'))
{
    function calcul_tva($pu, $quantity, $tva_percent=21)
    {
        if(empty($pu)) return 0;

        return $pu*$quantity*$tva_percent/100;
    }
}

if (!function_exists('calcul_tvac'))
{
    function calcul_tvac($pu, $quantity, $tva_percent=21)
    {
        if(empty($pu)) return 0;
        
        return ($pu*$quantity)+($pu*$quantity*$tva_percent/100);
    }
}


