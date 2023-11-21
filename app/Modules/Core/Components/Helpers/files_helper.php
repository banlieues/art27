<?php
// files_helper

if (!function_exists('format_filesize'))
{
    function format_filesize($octets, $digits = 0)
    {
        $label = array('B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');
        for ($i = 0; $octets >= 1024 && $i < (count($label) - 1); $octets /= 1024, $i++);
        return(round($octets,  $digits)." ".$label[$i]);
    }

    

}

function slugify_name_with_extension($text)
{
    $nameFinal=NULL;
    $nameArray=explode(".",$text);
    $i=0;
    foreach($nameArray as $nameSegment)
    {
        if($i>0)
        {
            $nameFinal.=".";
        }
        $nameFinal.=slugify_name_file($nameSegment);
        $i=$i+1;
    }


    return $nameFinal;

}



function slugify_name_file($text, string $divider = '-')
{
  // replace non letter or digits by divider
  $string_replace="49002100aaazimp6";
  $text=str_replace(".",$string_replace,$text);
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  $string_replace=iconv('utf-8', 'us-ascii//TRANSLIT', $string_replace);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
 $text = mb_strtolower($text);

  if (empty($text)) {
    return $text;
  }
  $text=str_replace($string_replace,".",$text);
  return $text;
}
