<?php
require_once('config/Config.php');
require_once('classes/Db/ExtendPDO.php');
include_once __DIR__ . "/autoload.php";

$client = new Line\LINEBotTiny(LINE_CHANNELACCESSTOKEN, LINE_CHANNELSECRET);
$pdo = new ExtendPDO(DB_HOST,DB_DBNAME,DB_ENCODE,DB_USER,DB_PW);
foreach ($client->parseEvents() as $event) {	
	if($event['type'] == 'message'){
		$user = new Bot\UserClass($pdo,$event['source']['userId']);
		$test= $user->test();
		$message = $event['message'];
		if($message['type']=='text'){
			$reply = new Line\MessageClass($event['replyToken']);
			if (strtolower($message['text']) == "a"){
				$client->replyMessage($reply->getTemplateMessage('test',[
					[1,'abc',$event['source']['userId']],
					[2,'abc','https://2.2.2']
				]));
			}
			else{
				$client->replyMessage($reply->getTextMessage($test.'test'));
			}
		}
	}
};
