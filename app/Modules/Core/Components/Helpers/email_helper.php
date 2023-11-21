<?php
// Email_Helper

if (!function_exists('send_email_to'))
{
    function send_email_to($to, $cc, $bcc, $subject, $message)
    {
        $EmailConfig = new \Custom\Config\Email();

        $EmailService = \Config\Services::email();
        $EmailService->initialize((array) $EmailConfig);
        $EmailService->setFrom($EmailConfig->fromEmail, CRM_NAME . " - No reply");
        $EmailService->setTo($to);
        if (!empty($cc)) {$EmailService->setCC($cc);}
        if (!empty($bcc)) {$EmailService->setBCC($bcc);}
        $EmailService->setSubject($subject);
        $EmailService->setMessage($message);
        $EmailService->send();
        // if (!$EmailService->send();) {debugd($EmailService->printDebugger());}
    }
}

function extraire_mail($mail)
{
    if(!empty($mail) && filter_var($mail, FILTER_VALIDATE_EMAIL)):
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,8})(?:\.[a-z]{2})?/i';
        preg_match_all($pattern, $mail, $matches);
        $r=$matches[0][0];       
        return $r;
    else : 
        return '';
    endif;
}

if (!function_exists('send_email_with_attachment'))
{
    function send_email_with_attachment($to, $cc, $bcc, $subject, $message, $attachment, $filename)
    {
        $EmailConfig = new \Custom\Config\Email();

        $EmailService = \Config\Services::email();
        $EmailService->initialize((array) $EmailConfig);
        $EmailService->setFrom($EmailConfig->fromEmail, CRM_NAME . " - No reply");
        $EmailService->setTo($to);
        if (!empty($cc)) {$EmailService->setCC($cc);}
        if (!empty($bcc)) {$EmailService->setBCC($bcc);}
        $EmailService->setSubject($subject);
        $EmailService->setMessage($message);

        if (!empty($attachment))
        {
            if (empty($filename))
            {
                // $EmailService->attach('/path/to/filename.jpg');
                // $EmailService->attach('filename.jpg', 'inline');
                // $EmailService->attach('http://example.com/filename.pdf');
                $EmailService->attach($attachment);
            }

            else
            {
                // $EmailService->attach('filename.pdf', 'attachment', 'new_filename.pdf');
                $EmailService->attach($attachment, 'attachment', $filename);
            }
        }

        $EmailService->send();
        // if (!$EmailService->send();) {printDebugger();}
    }
}

if (!function_exists('send_email_with_attachment_on_fly'))
{
    function send_email_with_attachment_on_fly($to, $cc, $bcc, $subject, $message, $attachment, $filename)
    {
        $EmailConfig = new \Custom\Config\Email();

        $EmailService = \Config\Services::email();
        $EmailService->initialize((array) $EmailConfig);
        $EmailService->setFrom($EmailConfig->fromEmail, CRM_NAME . " - No reply");
        $EmailService->setTo($to);
        if (!empty($cc)) {$EmailService->setCC($cc);}
        if (!empty($bcc)) {$EmailService->setBCC($bcc);}
        $EmailService->setSubject($subject);
        $EmailService->setMessage($message);

        if (!empty($attachment) && !empty($filename))
        {
            // $EmailService->attach($buffer, 'attachment', 'filename.pdf', 'application/pdf');
            $EmailService->attach($attachment, 'attachment', $filename.'.pdf', 'application/pdf');
        }

        $EmailService->send();
        // if (!$EmailService->send();) {printDebugger();}
    }
}
