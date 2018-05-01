<?php
namespace Line;
Class MessageClass{
    private $_reply_token;

    function __construct($reply_token){
        $this->_reply_token = $reply_token;
    }

    private function _ationData($type,$label,$text){
        return [
            'type' => ($type==1?'message':'uri'), // 類型 (1.訊息 2.url)
            'label' => $label, // 標籤 
            ($type==1?'text':'uri') => $text // 用戶發送 (1.訊息 2.url)
        ];
    }
    function getTemplateMessage($title_text,array $data){
        for($i=0;$i<count($data);$i++){
            $new_data[] = $this->_ationData($data[$i][0],$data[$i][1],$data[$i][2]);
        }
        return array(
            'replyToken' => $this->_reply_token,
            'messages' => array(
                array(
                    'type' => 'template', // 訊息類型 (模板)
                    'altText' => '[此訊息只能由手機觀看]', // 替代文字
                    'template' => array(
                        'type' => 'buttons', // 類型 (按鈕)
                        //'thumbnailImageUrl' => '', // 圖片網址 <不一定需要>
                        //'title' => 'Example Menu', // 標題 <不一定需要>
                        'text' => $title_text, // 文字
                        'actions' => $new_data
                    )
                )
            )
        );
    }
    function getTextMessage($text){
        return array(
            'replyToken' => $this->_reply_token,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $text
                )
            ));
    }

}