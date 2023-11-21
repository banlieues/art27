<?php

namespace Mail\Config;

use Mail\Config\Rules;
use Base\Config\BaseValidation;

class Validation extends BaseValidation
{
    public $ruleSets = [
        Rules::class,
    ];

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function ruleEmail() 
    {
        return [
            'sender' => [
                'label' => 'Expéditeur',
                'rules' => 'required',
            ],
            // 'recipient' => [
            //     'rules' => 'recipient',
            //     'errors' => [
            //         'recipient' => 'Au moins un destinataire est requis.',
            //     ],
            // ],
            'subject' => [
                'label' => 'Sujet',
                'rules' => 'required',
            ],  
            'message' => [
                'label' => 'Message',
                'rules' => 'required',
            ],
        ];
    }

    public function MailRecipient(?string &$error=null): bool
    {
        $request = \Config\Services::request();
        $post = $request->getPost();
        
        if(
            (!empty($post['to_selected']) && count($post['to_selected'])>0) || 
            (!empty($post['to_text']) && count($post['to_text'])>0)  || 
            (!empty($post['cc_selected']) && count($post['cc_selected'])>0)  || 
            (!empty($post['cc_text']) && count($post['cc_text'])>0)  || 
            (!empty($post['cci_selected']) && count($post['cci_selected'])>0)  || 
            (!empty($post['cci_text']) && count($post['cci_text'])>0) 
        ) : 
            return TRUE;
        else :
            $error = 'Veuillez encoder au moins un destinataire.';
            return FALSE;
        endif;
    }
}
