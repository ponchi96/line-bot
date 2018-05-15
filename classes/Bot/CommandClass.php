<?php
namespace Bot;
Class CommandClass{
    private $_user;
    private $_reply;
    private $_client;
    private $_commands=['.all','.add','.delete','.update','.mode'];
    private $_commands_check=['.all','.add&keyword&reply','.delete&number','.update&number&reply','.mode&number'];
    function __construct($client,$user,$reply){
        $this->_user = $user;
        $this->_reply = $reply;
        $this->_client = $client;
    }

    function run($text){
        $text_explode = explode('&',$text);
        if(in_array($text_explode[0],$this->_commands)){
            $command_index = array_search($text_explode[0],$this->_commands);
            $reply_text = '';
            if(count($text_explode) != count(explode('&',$this->_commands_check[$command_index]))){
                $reply_text = 'You should enter:'.$this->_commands_check[$command_index];                
            }
            else if($command_index==0){
                $allreply = $this->_user->allReply();
                if(count($allreply)==0){
                    $reply_text = 'No data';
                }

                for($i=0;$i<count($allreply);$i++){
                    $reply_text .= $allreply[$i]['id']."-".$allreply[$i]['keyword']."-".$allreply[$i]['reply'].'
';
                }
            }
            else if($command_index==1){
                $reply_text = $this->_user->addReply($text_explode[1],$text_explode[2])>0?'add success':'add error';
            }
            else if($command_index==2){
                $reply_text = $this->_user->deleteReply($text_explode[1])>0?'delete success':'delete error';
            }
            else if($command_index==3){
                $reply_text = $this->_user->updateReply($text_explode[1], $text_explode[2])>0?'update success':'update error';
            }
            else if($command_index==4){
                $reply_text = $this->_user->changeMode($text_explode[1]) > 0 ? 'mode change success' : 'mode is not change';

            }
        }
        else{
            $allreply = $this->_user->findReply($text);
            if (count($allreply) == 0) {
                $reply_text = 'I do not know how to say';
            }
            else{
                $reply_text .= $allreply['reply']; 
            }
        }
        $this->_client->replyMessage($this->_reply->getTextMessage($reply_text));

    }
}