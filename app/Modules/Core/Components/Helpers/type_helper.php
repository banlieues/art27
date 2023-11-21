<?php

if(! function_exists('fullname')) :
    function fullname($name1, $name2)
    {
        $array = array_filter([$name1, $name2]);

        if(empty($array)) return null;

        return implode(' ', $array);
    }
endif;

if(! function_exists('coalesce_isset')) :
    function coalesce_isset($data, $default=null, $after=null)
    {
        $return_data = isset($after) ? $data . $after : $data;
        return isset($data) ? $return_data : $default;
    }
endif;

if(! function_exists('coalesce_empty')) :
    function coalesce_empty($data, $default=null, $after=null)
    {
        $return_data = isset($after) ? $data . $after : $data;
        return !empty($data) ? $return_data : $default;
    }
endif;

if(! function_exists('add_zero_to_one_digit')) :
    function add_zero_to_one_digit($string)
    {
        if(strlen($string)==1) $string = '0' . $string;

        return $string;
    }
endif;

if(! function_exists('convert_month_to_digit')) :
    function convert_month_to_digit($month)
    {
        $month = mb_strtolower(remove_accents($month));
        if(preg_match('/^jan/', $month)) $digit = '01';
        elseif(preg_match('/^(?:fev|feb)/', $month)) $digit = '02';
        elseif(preg_match('/^mar/', $month)) $digit = '03';
        elseif(preg_match('/^(?:avr|apr)/', $month)) $digit = '04';
        elseif(preg_match('/^(?:mai|mei)/', $month)) $digit = '05';
        elseif(preg_match('/^(?:juin|june)/', $month)) $digit = '06';
        elseif(preg_match('/^(?:juil|july)/', $month)) $digit = '07';
        elseif(preg_match('/^(?:aout|aug)/', $month)) $digit = '08';
        elseif(preg_match('/^sept/', $month)) $digit = '09';
        elseif(preg_match('/^oct/', $month)) $digit = '10';
        elseif(preg_match('/^nov/', $month)) $digit = '11';
        elseif(preg_match('/^dec/', $month)) $digit = '12';

        return $digit;
    }
endif;

if(! function_exists('add_digit_to_year')) :
    function add_digit_to_year($year)
    {
        if(strlen($year)==2):
            if($year < 30) : $year = '20' . $year;
            else : $year = '19' . $year;
            endif;
        endif;
        
        return $year;
    }
endif;

if(! function_exists('is_html')) :
    function is_html($string)
    {
        return preg_match("/<[^<]+>/", $string, $m) != 0;
    }
endif;

if(! function_exists('transpose_array')) :
    function transpose_array($arr) {
        $out = array();
        foreach ($arr as $key => $subarr) {
            foreach ($subarr as $subkey => $subvalue) {
                $out[$subkey][$key] = $subvalue;
            }
        }
        return $out;
    }
endif;

if (! function_exists('extract_email')) :
    function extract_email($string)
    {
        $pattern = "/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,})(?:\.[a-z]{2,})?/i";
        preg_match_all($pattern, $string, $matches);

        if(isset($matches[0][0])) return $matches[0][0];
    }
endif;

if (! function_exists('url_exists')) :
    function url_exists($url) {
        $file_headers = @get_headers($url);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') return false;
        else return true;
    }
endif;

if(! function_exists('is_json')) :
    function is_json($object)
    {
        if(is_string($object)) :
            json_decode($object);
            return json_last_error() === JSON_ERROR_NONE;
        else :
            return false;
        endif;
    }
endif;

// if(! function_exists('remove_accents')) :
//     function remove_accents($str) {
//         return strtr(
//             utf8_decode($str), 
//             utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 
//             'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
//     }
// endif;

function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}

if(! function_exists('convert_utf8_to_url')) :
    function convert_utf8_to_url($str) {
        $str = trim($str);
        $str = remove_accents($str);

        $str = preg_replace('/\W/', '_', $str);
        $str = preg_replace('/_+/', '_', $str);
        $str = preg_replace('/^_+/', '', $str);
        $str = preg_replace('/_+$/', '', $str);

        $str = mb_strtolower($str);
        // $str = utf8_encode($str);
        // $str = preg_replace('/\s/', '_', $str);

        return $str;
    }
endif;

if(! function_exists('convert_first_row_csv_to_sql_key')) :
    function convert_first_row_csv_to_sql_key($keys) 
    {
        $i = 0;
        foreach($keys as $key):
            $keys[$i] = convert_utf8_to_url($key);
            $i++;
        endforeach;

        return $keys;
    }
endif;

if(! function_exists('convert_csv_array_to_object')) :
    function convert_csv_array_to_object($rows) 
    {
        $rows[0] = convert_first_row_csv_to_sql_key($rows[0]);
        $keys = $rows[0];

        $datas = [];
        foreach($rows as $row) :
            if($row==$rows[0]) continue;
            $data = (object) [];
            $i = 0;
            foreach($row as $value) :
                $data->{$keys[$i]} = $value;
                $i++;
            endforeach;
            $datas[] = $data;
        endforeach;
        
        return $datas;
    }
endif;