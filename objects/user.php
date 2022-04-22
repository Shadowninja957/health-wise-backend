<?php
    class User{
        
        private $conn;
        private $table_name = "users";
        
        public $user_name;
        public $password;
        public $role_id;
        
        public function __construct($conn){
            $this->conn = $conn;
            
        }

        function read(){            

            $query = "select * from ".$this->table_name;
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        function authenticate () 
        {
            $query = "select * from ".$this->table_name." 
            WHERE user_name =:user_name and password =sha1(:password)";

            $stmt = $this->conn->prepare($query);
            $this->user_name=htmlspecialchars(strip_tags($this->user_name));
            $this->password=htmlspecialchars(strip_tags($this->password));

            $stmt->bindParam(":user_name", $this->user_name);
            $stmt->bindParam(":password", $this->password);

            $stmt->execute();
            return $stmt;
        }

        function create(){

            $query = "insert into 
                        ".$this->table_name."
                        SET user_name =:user_name , password =sha1(:password), role_id=:role_id ";
            
            $stmt = $this->conn->prepare($query);
            
            $this->user_name=htmlspecialchars(strip_tags($this->user_name));
            $this->password=htmlspecialchars(strip_tags($this->password));
            $this->role_id=htmlspecialchars(strip_tags($this->role_id));

            $stmt->bindParam(":user_name", $this->user_name);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":role_id", $this->role_id);

            if($stmt->execute()){
                return true;
            }

            return false;
            //return $stmt->errorInfo();
        }
    }
?>