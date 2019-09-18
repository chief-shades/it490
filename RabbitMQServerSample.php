<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function login($user,$pass){
	//TODO validate user credentials
	return true;
}
function doEcho($req){
	$req['message'] = "Echo " . $req['message'];
	return $req;
}
function request_processor($req){
	echo "Received Request".PHP_EOL;
	echo "Request[" . $req . "]\n<br>"; 
	echo "<pre>" . var_export($req, true) . "</pre>";
	if(!isset($req['type'])){
		return __FILE__ . ".Error: unsupported message type";
	}
	//Handle message type
	$type = $req['type'];
	switch($type){
		case "login":
			return login($req['username'], $req['password']);
		case "validate_session":
			return validate($req['session_id']);
		case "echo":
			return doEcho($req);
	}
	return array("return_code" => '0',
		"message" => "Server received request and processed it");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "sampleServer");

echo "Rabbit MQ Server Start" . PHP_EOL;
$server->process_requests('request_processor');
echo "Rabbit MQ Server Stop" . PHP_EOL;
exit();
?>
