<?php

namespace PHPAuth;

use PHPMailer;

class EmailDelegate implements EmailDelegate_i
{
	public function sendMail($config, $destination, $subject, $body, $altBody)
    {
		$smtpConfig = parse_ini_file(__SITE_PATH . '/../config-smtp.ini');
		
		if (!$smtpConfig['smtp_host'])
		{
			return false;
		}
		
        $mail = new PHPMailer;
		$mail->CharSet = $config->email_charset;
		if ($config->email_smtp)
		{
			$mail->isSMTP();
			$mail->Host = $smtpConfig['smtp_host'];
			$mail->SMTPAuth = $config->email_smtp_auth;
			if ($config->email_smtp_auth)
			{
				$mail->Username = $smtpConfig['smtp_username'];
				$mail->Password = $smtpConfig['smtp_password'];
			}
			$mail->Port = $smtpConfig['smtp_port'];

			if ($config->email_smtp_security)
			{
				$mail->SMTPSecure = $config->email_smtp_security;
			}
		}
		
		$mail->From = $smtpConfig['smtp_username'];
		$mail->FromName = $config->site_name;
		$mail->addAddress($destination);
		$mail->isHTML(true);
		
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $altBody;
		
		return $mail->send();
    }
}

?>