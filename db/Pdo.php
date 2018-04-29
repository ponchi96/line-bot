<?php

Class ExtendPDO extends PDO{
    private $_stmt;
    private $_data = [];
    private $_last_insert_id;

    function __set($name,$value){
        $this->_data[$name]=$value;    
    }

    function __get($name){
        if(isset($this->_data[$name])){
            return $this->_data[$name];
        }
        return false;
    }
    function __construct($host,$dbname,$encode,$user,$password){
        parent::__construct('mysql:host='.$host.'.;dbname='.$dbname.';charset='.$encode,$user,$password);
    }
   
    private function _bind($bind){
        foreach($bind as $key => $value){
            $this->_stmt->bindValue($key,$value,is_numeric($value)?PDO::PARAM_INT:PDO::PARAM_STR);
        }
    }

    function error(){
        $error = $this->_stmt->errorInfo();
        echo 'errorCode:'.$error[0].'<br>';
        echo 'errorString:'.$error[2].'<br>';
    }

    function select($sql,array $bind=[]){
        $this->_stmt = $this->prepare($sql);
        $this->_bind($bind);
        $this->_stmt->execute();
        return $this->_stmt->fetchAll();
    }

    function insert($table,array $param=[]){
        $data = array_merge($this->_data,$param);
        $colums = array_keys($param);
        $values = [];
        $bind = [];
        foreach($data as $key=>$value){
            $values[]=":{$key}";
            $bind[":{$key}"]=$value;
        }
        $sql="INSERT INTO {$table} (".implode(',',$colums).") VALUES (".implode(',',$values).")";
        $this->_stmt = $this->prepare($sql);
        $this->_bind($bind);
        $this->_stmt->execute();
        $this->_insert_last_id = $this->lastInsertId();
        return $this->_insert_last_id;
    }

    function update($table,array $param=[],$filter,array $bind =[]){
        $bind_temp = [];
        foreach($param as $key => $value){
            $bind_temp[] = "{$key} = :{$key}";
            $bind[":{$key}"] = $value;
        }
        $sql = "UPDATE {$table} SET ".implode(',',$bind_temp)." WHERE {$filter}";
        $this->_stmt = $this->prepare($sql);
        $this->_bind($bind);
        $this->_stmt->execute();
        return $this->_stmt->rowCount();
    }

    function delete($table,$filter,array $bind =[]){
        $sql ="DELETE FROM {$table} WHERE {$filter}";
        $this->_stmt = $this->prepare($sql);
        $this->_bind($bind);
        $this->_stmt->execute();
        return $this->_stmt->rowCount();
    }
}