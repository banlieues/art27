<?php

namespace Custom\Config;

class Email
{
    public $fromName = "CRM Notification";
    public $fromEmail;
    public $mailType = 'html';
    public $protocol = 'smtp';
    public $SMTPHost;
    public $SMTPUser;
    public $SMTPPass;
    public $SMTPPort = 587;
    public $SMTPCrypto = 'tls';

    public function __construct()
    {
        $this->SMTPHost = getEnv('email.default.SMTPHost');
        $this->SMTPUser = getEnv('email.default.SMTPUser');
        $this->SMTPPass = getEnv('email.default.SMTPPass');
        $this->fromEmail = getEnv('email.default.SenderEmail');
    }
}
