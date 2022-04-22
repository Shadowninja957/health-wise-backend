<?php
class Database{
	
	//var to hold connection to db server
	public $conn;
	
	//var for configuration
	public $host = "localhost";
	public $username = "root";
	public $password = "";
	
	public $database = "health_wise";
	
	public $report_errors = TRUE;
    
    	
	//Get mysqli connection
	public function getConnection(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
		
	}
	
	
}
?>