<?php

namespace PHPAuth;

use Twilio\Rest\Client;

class SMSDelegate implements SMSDelegate_i
{
    public function sendSMS($phone, $body)
	{
		$config = parse_ini_file(__SITE_PATH . '/../config-sms.ini');
		
		if (!$config['sms_id'])
		{
			return false;
		}
		
		$client = new Client($config['sms_id'], $config['sms_token']);
		
		$client->messages->create
		(
			$phone,
			array
			(
				// A Twilio phone number you purchased at twilio.com/console
				'from' => $config['sms_phone_number'],
				'body' => $body
			)
		);
		
        return true;
	}
}

?>