<?php
require_once('models/LINEBotTiny.php');
require_once('config/Config.php');
require_once('db/Pdo.php');

$client = new LINEBotTiny(LINE_CHANNELACCESSTOKEN, LINE_CHANNELSECRET);
foreach ($client->parseEvents() as $event) {
	
	$pdo = new ExtendPDO(DB_HOST,DB_DBNAME,DB_ENCODE,DB_USER,DB_PW);
	$query = $pdo->select("SELECT 1 FROM accounts WHERE line_user_id=:line_user_id LIMIT 1",[
		':line_user_id' => $event["source"]["userId"],
	]);
	$test = count($query);
	if($event['type'] == 'message'){
		$message = $event['message'];
		if($message['type']=='text'){
			if (strtolower($message['text']) == "template"){
				$client->replyMessage(array(
					'replyToken' => $event['replyToken'],
					'messages' => array(
						array(
							'type' => 'template', // 訊息類型 (模板)
							'altText' => 'Example buttons template', // 替代文字
							'template' => array(
								'type' => 'buttons', // 類型 (按鈕)
								'thumbnailImageUrl' => 'https://api.reh.tw/line/bot/example/assets/images/example.jpg', // 圖片網址 <不一定需要>
								'title' => 'Example Menu', // 標題 <不一定需要>
								'text' => 'Please select', // 文字
								'actions' => array(
									array(
										'type' => 'postback', // 類型 (回傳)
										'label' => 'Postback example', // 標籤 1
										'data' => 'action=buy&itemid=123' // 資料
									),
									array(
										'type' => 'message', // 類型 (訊息)
										'label' => 'Message example', // 標籤 2
										'text' => 'Message example' // 用戶發送文字
									),
									array(
										'type' => 'uri', // 類型 (連結)
										'label' => 'Uri example', // 標籤 3
										'uri' => 'https://github.com/GoneTone/line-example-bot-php' // 連結網址
									)
								)
							)
						)
					)
				));
			}
			else{
				$client->replyMessageSimple($event['replyToken'],$test.$message['text']."test5");
			}
		}
	}
};
