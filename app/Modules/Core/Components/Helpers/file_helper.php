<?php


if(! function_exists('file_to_base64')) :
    function file_to_base64($path, $type) 
    {
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }
endif;

if(! function_exists('read_json_file')) :
    function read_json_file($path) 
    {
        $client = \Config\Services::curlrequest();
        $response = $client->get('FILE://' . $path);
        $body = json_decode($response->getBody());

        return $body;
    }
endif;

if(! function_exists('glob_recursive')) :
    function glob_recursive($base, $pattern, $flags = 0) 
    {
        $flags = $flags & ~GLOB_NOCHECK;
        
        if (substr($base, -1) !== DIRECTORY_SEPARATOR) $base .= DIRECTORY_SEPARATOR;
    
        $files = glob($base . $pattern, $flags);
        if (!is_array($files)) $files = [];
    
        $dirs = glob($base . '*', GLOB_ONLYDIR|GLOB_NOSORT|GLOB_MARK);
        if (!is_array($dirs)) return $files;
        
        foreach ($dirs as $dir) :
            $dirFiles = glob_recursive($dir, $pattern, $flags);
            $files = array_merge($files, $dirFiles);
        endforeach;
    
        return $files;
    }
endif;

if(! function_exists('export_csv')) :
function export_csv($filename, $rows, $labels=null)
{
    header('Content-Description: File Transfer');
    header('Content-type: application/csv;');
    header('Content-type: application/vnd.ms-excel;');
    header('Content-Type: text/csv;');
    // header('Content-type: application/csv;charset=utf-8');
    // header('Content-type: application/vnd.ms-excel;charset=utf-8');
    // header('Content-Type: text/csv;charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // open temporary file
    $file = fopen('php://output','w');
    if(!empty($labels)) :
        $i = 0;
        foreach($labels as $label) :
            $labels[$i] = mb_convert_encoding($label, 'windows-1252', 'UTF-8');
            $i++;
        endforeach;
        fputcsv($file, $labels, ';');
    endif;
    foreach($rows as $row) :
        foreach($row as $key=>$value) :
            if(!is_string($value)) :
                $row->$key = json_encode($value, JSON_PRETTY_PRINT);
            endif;
            if(is_null($value) || $value==null) :
                $row->$key = '';
            else :
                $row->$key = mb_convert_encoding($value, 'windows-1252', 'UTF-8');
            endif;
        endforeach;
        $line = (array) $row;
        // fputcsv($file, $line, ';');
        fputcsv($file, $line, ';');
    endforeach;
    fclose($file);
    exit;
}
endif;

if(! function_exists('convert_file_csv_to_array')) :
function convert_file_csv_to_array($file)
{
    $fp = fopen($file, 'r');
    $lines = [];
    while (!feof($fp)) :
        $line = fgetcsv($fp, null, ';');
        if(empty($line)) continue;

        $newline = [];
        // foreach($line as $column) $newline[] = utf8_encode($column);
        foreach($line as $column) $newline[] = $column;
        $lines[] = $newline;
    endwhile;
    fclose($fp);

    return $lines;
}
endif;

if(! function_exists('write_datas_to_json_file')) :
    function write_datas_to_json_file($datas, $file)
    {
        $folder = pathinfo($file)['dirname'];
        if(!is_dir($folder)) mkdir($folder, 0777, true);
        $fp = fopen($file, 'w');
        fwrite($fp, json_encode($datas, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        fclose($fp);
    }
endif;
