<?php
namespace Bot;
Class UserClass{
    private $_pdo;
    private $_account_id;
    private $_bot_id;
    private $_mode;

    function __construct($pdo,$line_user_id){
        $this->_pdo = $pdo;
        $this->_checkUser($line_user_id);
    }

    private function _checkUser($line_user_id){
        $query = $this->_pdo->selectAll('SELECT * FROM accounts WHERE line_user_id=:line_user_id',[
			':line_user_id'=>$line_user_id]);
		if(count($query)==0){
			$this->_account_id = $this->_pdo->insert('accounts',[
				'line_user_id'=>$line_user_id,
				'bot_id'=>1,
				'mode'=>1
            ]);
            $this->_bot_id = $this->_pdo->insert('bots',[
				'name'=>  'Averil',
				'owner'=> $this->_account_id
            ]);
            $this->_mode = 1;
        }
        else{
            $this->_account_id = $query[0]['account_id'];
            $this->_bot_id = $query[0]['bot_id'];
            $this->_mode = $query[0]['mode'];
        }
    }    

    function changeMode($mode){
        return $this->_pdo->update('accounts', [
            'mode' => (in_array($mode,['1','2','3'])?$mode:1)
        ],'account_id=:account_id',[
            ':account_id' => $this->_account_id
        ]);
    }

    function findReply($text){
        $query = $this->_pdo->selectAll("SELECT * FROM replys WHERE bot_id=:bot_id and :text like concat('%',`keyword`,'%')", [
        ':bot_id' => $this->_bot_id,
        ':text' => $text
        ]); 
        $result = [];
        $count = 0;
        foreach ($query as $row){
            $result[$count]['id'] = $row['id'];
            $result[$count]['keyword'] = $row['keyword'];
            $result[$count]['reply'] = $row['reply'];
            $count = $count + 1;
        }

        if($this->_mode == 1){
            return $count==0?$result:$result[0];
        }
        else if($this->_mode == 2){
            return $count==0?$result:$result[rand(0,$count-1)];
        }
    }
    function allReply(){
        $query = $this->_pdo->selectAll("SELECT * FROM replys WHERE bot_id=:bot_id",[
        ':bot_id' => $this->_bot_id
        ]);
        $result =[];
        $count = 0;
        foreach ($query as $row) {
            $result[$count]['id'] =$row['id'];
            $result[$count]['keyword'] =$row['keyword'];
            $result[$count]['reply']=$row['reply'];
            $count = $count + 1;
        }
        return $result;
    }

    function addReply($keyword,$reply){  
        return $this->_pdo->insert('replys', [
            'bot_id' => $this->_bot_id,
            'keyword' => $keyword,
            'reply' => $reply,
        ]);
    }

    function updateReply($id,$reply){
        return $this->_pdo->update('replys', [
            'reply' => $reply
        ],'id=:id and bot_id=:bot_id',[
            ':id' => $id,
            ':bot_id' => $this->_bot_id
        ]);
    }

    function deleteReply($id){
        return $this->_pdo->delete('replys','id=:id and bot_id=:bot_id',[
            ':id' => $id,
            ':bot_id' => $this->_bot_id
        ]);

    }
}