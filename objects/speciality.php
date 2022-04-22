<?php
    class Speciality{
        
        private $conn;
        private $table_name = "specialities";
        
        public function __construct($conn){
            $this->conn = $conn;
        }

        function read(){ 
            $query = "SELECT * FROM ".$this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
       
    }
?>