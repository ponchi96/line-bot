<?php
require_once('config/Config.php');
require_once('classes/Db/ExtendPDO.php');
include_once __DIR__ . "/autoload.php";

//send person record
$line_user_id_record=[];
$client = new Line\LINEBotTiny(LINE_CHANNELACCESSTOKEN, LINE_CHANNELSECRET);
$pdo = new ExtendPDO(DB_HOST,DB_DBNAME,DB_ENCODE,DB_USER,DB_PW);
foreach ($client->parseEvents() as $event) {
	//prevaent the same person send large message
	if(!in_array($event['source']['userId'],$line_user_id_record)){
		$line_user_id_record[]=$event['source']['userId'];
		if($event['type'] == 'message'){
			$message = $event['message'];
			if($message['type']=='text'){
				$user = new Bot\UserClass($pdo,$event['source']['userId']);
				$reply = new Line\MessageClass($event['replyToken']);
				$command = new Bot\CommandClass($client,$user,$reply);
				$command->run($message['text']);
				/*
				if (strtolower($message['text']) == "a"){
					$client->replyMessage($reply->getTemplateMessage('test',[
						[1,'abc',$event['source']['userId']],
						[2,'abc','https://2.2.2']
					]));
				}
				else{
					$client->replyMessage($reply->getTextMessage($user->test().'test'));
				}
				*/
			}
		}
	}	
	
};
