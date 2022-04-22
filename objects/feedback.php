<?php
    class Feedback{
        
        private $conn;
        private $table_name = "patient_feedbacks";
        
        public $patient_id;
        public $doctor_id;
        
        public function __construct($conn){
            $this->conn = $conn;
            
        }

        function read(){            

            $query = "select ".$this->table_name.".*, doctors.first_name, doctors.last_name ". 
            " FROM ".$this->table_name.", doctors ".
            " WHERE ".$this->table_name.".doctor_id = doctors.id ".
            " AND patient_id = ".$this->patient_id; 
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        function readDoctor(){
            $query = "select ".$this->table_name.".*, patients.first_name, patients.last_name ". 
            " FROM ".$this->table_name.", patients ".
            " WHERE ".$this->table_name.".patient_id = patients.id ".
            " AND doctor_id = ".$this->doctor_id; 
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        function replies($patient_feedback_id)
        {
            $query = "select * from patient_feedback_replies 
            where patient_feedback_id = ".$patient_feedback_id;

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }
        
        function create(){

            $query = "insert into 
                        ".$this->table_name."
                        SET patient_id =:patient_id , name =:name, 
                        email=:email, contact=:contact,
                        doctor_id=:doctor, subject=:subject, description=:description";
            
            $stmt = $this->conn->prepare($query);
            
            // $this->first_name=htmlspecialchars(strip_tags($this->first_name));

            $stmt->bindParam(":patient_id", $this->patient_id);
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":contact", $this->contact);
            $stmt->bindParam(":doctor", $this->doctor);
            $stmt->bindParam(":subject", $this->subject);
            $stmt->bindParam(":description", $this->description);

            if($stmt->execute()){
                return true;
            }

            // return false;
            return $stmt->errorInfo();
        }
    }
?>