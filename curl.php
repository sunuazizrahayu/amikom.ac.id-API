<?php
//Upload a blank cookie.txt to the same directory as this file with a CHMOD/Permission to 777
function login($url,$data){
	//$fp = fopen("cookie.txt", "w");
	//fclose($fp);
	$login = curl_init();
	//curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
	//curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
	curl_setopt($login, CURLOPT_TIMEOUT, 40000);
	curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($login, CURLOPT_URL, $url);
	curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($login, CURLOPT_POST, TRUE);
	curl_setopt($login, CURLOPT_POSTFIELDS, $data);
	ob_start();
	return curl_exec ($login);
	ob_end_clean();
	curl_close ($login);
	unset($login);
}                   
function grab_page($site){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
	curl_setopt($ch, CURLOPT_URL, $site);
	ob_start();
	return curl_exec ($ch);
	ob_end_clean();
	curl_close ($ch);
}
function post_data($site,$data){
	$datapost = curl_init();
	$headers = array("Expect:");
	curl_setopt($datapost, CURLOPT_URL, $site);
	curl_setopt($datapost, CURLOPT_TIMEOUT, 40000);
	curl_setopt($datapost, CURLOPT_HEADER, TRUE);
	curl_setopt($datapost, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($datapost, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($datapost, CURLOPT_POST, TRUE);
	curl_setopt($datapost, CURLOPT_POSTFIELDS, $data);
	curl_setopt($datapost, CURLOPT_COOKIEFILE, "cookie.txt");
	ob_start();
	return curl_exec ($datapost);
	ob_end_clean();
	curl_close ($datapost);
	unset($datapost);
}
?>