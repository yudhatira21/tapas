<?php
include 'curl.php';

function register() {


	$date = date('Y-md');
	$tgl = explode('-', $date);
	$fake_name = curl('https://fakenametool.net/generator/random/id_ID/indonesia', null, null, false);
	preg_match_all('/<td>(.*?)<\/td>/s', $fake_name, $result);
	$name = $result[1][0];
	$secmail = curl('https://www.1secmail.com/api/v1/?action=getDomainList', null, null, false);
	$domain = json_decode($secmail);
	$rand = array_rand($domain);
	$email = str_replace(' ', '', strtolower($name)).number(3).'@'.$domain[$rand];
	$user = explode('@', $email);
	$androidid = random(15);

	$headers = array(
		'Host: api.tapas.io',
		'x-offset-time: 420',
		'accept: application/panda+json',
		'x-device-type: ANDROID',
		'x-device-uuid: '.$androidid,
		'x-lang-code: in',
		'content-type: application/json; charset=utf-8',
		'accept-encoding: gzip',
	);


	echo "\nTry to register on tapas\n";
	$signup = curl('https://api.tapas.io/auth/signup', '{"email":"'.$email.'","password":"misaka123","offset_time":420}', $headers, false);
	$json_signup = json_decode($signup, true);


	if ($json_signup['auth_token'] != "") {
		$header = array(
			'Host: api.tapas.io',
			'accept: application/panda+json',
			'content-type: application/json;charset=UTF-8',
			'x-device-type: ANDROID',
			'x-device-uuid: '.$androidid,
			'x-lang-code: in',
			'x-user-id: '.$json_signup['user_id'],
			'x-auth-token: '.$json_signup['auth_token'],
		);

		$reff = curl('https://api.tapas.io/v3/user/redeem/RYOG520U/friend-code', ' ', $header, false);

		if (stripos($reff, 'An invite code has already been used on this device.')) {
			echo "Airplane mode\n";
		} elseif (stripos($reff, '"coin_amount":500')) {
			echo "Success\n";
		} else {
			echo "Failed\n";
		}

	} else {
		echo "Error\n";
	}

}

while(true) {
	register();
}




