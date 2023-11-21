<?php

namespace Components\Libraries;

use CodeIgniter\Files\File;
use Phpdocx\Create\CreateDocx;
use Phpdocx\Create\CreateDocxFromTemplate;
use Phpdocx\Utilities\MultiMerge;
use Phpdocx\Transform\TransformDocAdvHTML;
use Phpdocx\Transform\TransformDocAdvHTMLDefaultPlugin;

class PhpdocxLibrary
{
    public function DocxToPdf($file)
    {
        // $dompdf = new Dompdf\Dompdf(array('enable_remote' => true));
        $pathinfo = (object) pathinfo($file->getRealPath());
        $destination = $pathinfo->dirname . '/' . $pathinfo->filename . '.pdf';

        $docx = new CreateDocx();
        // if bug with "rename..." check app/ThirdParty/phpdocx-premium-12-2-namespace/config/phpdocxconfig.ini
        $docx->transformDocument($file->getRealPath(), $destination, 'libreoffice', array('homeFolder' => $pathinfo->dirname));

        $pdf = new File($destination);

        return $pdf;
    }
    
    public function CreatePdfFromHtml($filename, $html)
    {
        $filepath = PATH_DOCU_DEMANDE . $filename;
        $handle = fopen($filepath . '.html', "w");
        fwrite($handle, $html);
        fclose($handle);

        $docx = new CreateDocx();
        $docx->embedHTML($filepath . '.html', array('isFile' => true));
        $docx->createDocx($filepath . '.docx');

        \Phpdocx\Utilities\PhpdocxUtilities::$_phpdocxConfig['transform']['path'] = APPPATH . 'ThirdParty/phpdocx-premium-12-2-namespace/opt/libreoffice/program/soffice';
        $docx->transformDocument($filepath . '.docx', $filepath . '.pdf', 'libreoffice', array('homeFolder' => PATH_DOCU_DEMANDE));

        unlink($filepath . '.html');
        unlink($filepath . '.docx');

        return new File($filepath . '.pdf');
    }

    public function AttachHtmlToDocx($destination, $html)
    {
        $destination = PATH_DOCU_DEMANDE . $destination;
        // debug($html);
        $docx = new CreateDocx();
        $docx->embedHTML($html, array('downloadImages' => true, 'useHTMLExtended' => true));
        $docx->createDocx($destination);

        $file = new File($destination . '.docx');

        return $file;
    }

    public function CreateDocxFromTemplate($template, $filename, $variables)
    {
        $destination = PATH_DOCU_DEMANDE . $filename;

        $docx = new CreateDocxFromTemplate($template);
        $docx->setTemplateSymbol('#');
        $docx->replaceVariableByText($variables);
        $docx->createDocx($destination);

        $file = new File($destination . '.docx');

        return $file;
    }

    public function MergeDocx(array $files, string $filename)
    {
        $destination = PATH_DOCU_DEMANDE . $filename;

        $paths = [];
        $path_0 = '';
        $i = 0;
        foreach($files as $file) :
            if($i==0) :
                $path_0 = $file->getRealPath();
            else :
                $paths[] = $file->getRealPath();
            endif;
            $i++;
        endforeach;

        $merge = new MultiMerge();
        $merge->mergeDocx($path_0, $paths, $destination, array('mergeType' => 1));

        $file = new File($destination . '.docx');

        return $file;
    }

    public function download_file(string $file, string $report_name)
    {
        if(!file_exists($file)) return false;

        $pathinfo = (object) pathinfo($file);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $report_name . '.' . $pathinfo->extension . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    // public function template_process($template_url, $params, $destination_url)
    // {
    //     $docx = new CreateDocxFromTemplate($template_url);
    //     $docx->setTemplateSymbol('${', '}');
    //     $variables = $docx->getTemplateVariables();
    //     foreach($params as $param):
    //         foreach($param as $key=>$value):
    //             foreach($variables['document'] as $variable):
    //                 if($variable==$key) :
    //                     $docx->replaceVariableByText(array($variable => $value));
    //                 endif;
    //             endforeach;
    //         endforeach;
    //     endforeach;
    //     $docx->createDocx($destination_url);
    // }

    public function docx_to_html(string $file)
    {
        $transformHTMLPlugin = new TransformDocAdvHTMLDefaultPlugin();
        $transformHTMLPlugin->setGenerateSectionTags(false);

        $transform = new TransformDocAdvHTML($file);
        $html = $transform->transform($transformHTMLPlugin);

        return $html;
    }
    
}