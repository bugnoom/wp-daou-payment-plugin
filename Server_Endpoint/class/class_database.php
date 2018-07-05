<?php 
class database{
	public $key; //set my keys;
	public $conn;
	public $error;
	private $driver = _CFG_driver;
	private $host = _CFG_host;
	private $user = _CFG_user;
	private $pass = _CFG_pass;
	private $db_name  = _CFG_db_name;
	public $connectInfo = "";
	
	public function set_key($value){
		return $this->key = $value;
	}
	
	public function get_key(){
		return $this->key;
	}
	
	public function set_error($value){
		return $this->error = $value;		
	}
	public function get_error(){
		return $this->error;
	}
		
	public function __construct(){
		$this->conn = $this->connect_db();		
	}
	
	public function raw_select_sql($sql,$where=array()){
        $query = $this->conn->prepare($sql);
        if(count($where) > 0){
            foreach($where as $k=>&$v){
            $query->bindParam($k, $v);
            }
            $query->execute();
        }else{
            $query->execute();
        }
        
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
       
        if(count($data) > 0 ){
            return $data;
        }else{
            $this->set_error("No data found!");
            $data["error"] = $this->get_error();
            return $data;
        }
    }
    
    public function raw_insert_data($sql,$bind_value=array()){
        $query = $this->conn->prepare($sql);
        
        if(!$query->execute($bind_value)){
            $this->set_error("Can't Insert data to Database : ".$query->errorInfo());
            $data["error"] = $this->get_error();
            return $data;
        }else{
            $data['success'] = "Success Save data";
			$data['last_id'] = $this->conn->lastInsertId();
            return $data;
        }
    }
	
		public function connect_db(){	
		try{
			$this->connectInfo =_CFG_connectInfo;
			$opt = array(
								PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION
							);
			return new PDO($this->connectInfo,$this->user,$this->pass,$opt);
			
		}
		catch(PDOException $e){
			$this->set_error($e->getMessage());
			echo $e->getMessage();
			
		}		
	}		
	
	public function close_connect(){
			return $this->conn = null;
	}
	
	
	public function authen($authen_key){
		if($authen_key == "" || $authen_key != $this->get_key()){
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error Authen fail', true, 500);
			die("Authentication key is incorrect");
		}else{
			return true;
		}
	}

}
?>