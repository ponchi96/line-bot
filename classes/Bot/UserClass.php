<?php
namespace Bot;
Class UserClass{
    private $_pdo;
    private $_account_id;
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
        }
        else{
            $this->_account_id = $query[0]['account_id'];
        }
    }    

    function test(){  
        return $this->_account_id;  
    }

}