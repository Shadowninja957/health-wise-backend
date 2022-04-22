<?php
    class MedicalCondition{
        
        private $conn;
        private $table_name = "medical_conditions";
        
        // public $search_phrase;
        
        public function __construct($conn){
            $this->conn = $conn;
            
        }

        function read(){            

            // $query = "select * FROM ".$this->table_name." 
            // WHERE MATCH(symptoms) AGAINST ('".$this->search_phrase."')";

            // $query = "select * FROM ".$this->table_name." 
            // WHERE symptoms like '%".$this->search_phrase."%'";

            $query = "SELECT * FROM ".$this->table_name;
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        function treatments($medical_condition_id){
            $query = "select * FROM medical_condition_treatments
            WHERE medical_condition_id = ".$medical_condition_id;

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }
        
        
    }
?>