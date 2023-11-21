<?php

namespace Components\Config;

use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleImage() 
    {
        return [
            'image' => [
                'label' => "Image à importer", 
                'rules' => '
                    uploaded[image]|
                    max_size[image, 100]|
                    mime_in[image, image/png, image/jpg, image/gif]|
                    ext_in[image, png, jpg, gif]|
                    max_dims[image,1024,768]
                ',
            ],
        ];
    }

    public function ruleDocument() 
    {
        return [
            'document' => [
                'label' => "Image à importer", 
                'rules' => '
                    uploaded[document]|
                    max_size[document, 100]|
                    mime_in[document, image/png, image/jpg, image/gif]|
                    ext_in[document, png, jpg, gif]|
                    max_dims[document, 1024, 768]
                ',
                'errors' => [
                    'uploaded' => "Aucune image n'a été attachée pour l'upload.",
                    'max_size' => "Le fichier a dépassé la taille maximale de 100 kb.",
                    'mime_in' => "Le MIME type du fichier doit être image/png, image/jpg ou image/gif.",
                    'ext_in' => "L'extension du fichier doit être png, jpg, gif.",
                    'max_dims' => "L'image ne peut dépasser les dimensions 1024*768.",
                ],
            ],
        ];
    }

    public function ruleFile() 
    {
        return [
            'file' => [
                'label' => "Fichier à importer", 
                'rules' => '
                    uploaded[file]|
                    ext_in[file, gif|jpg|png|doc|docx|odt|pdf|xls|xlsx|ods|ppt|pptx|odp]|
                ',
                'errors' => [
                    'uploaded' => "Aucun fichier n'a été attaché pour l'upload.",
                    'ext_in' => "L'extension du fichier doit être gif, jpg, png, doc, docx, odt, pdf, xls, xlsx, ods, ppt, pptx ou odp.",
                ],
            ],
        ];
    }
}