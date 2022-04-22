<?php 
    class FeedbackReplies {
        private $conn;
        private $table_name = "patient_feedback_replies";
        
        public $patient_feedback_id;
        public $detail;
        public $patient_id;
        public $doctor_id;
        public $id;
        
        public function __construct($conn){
            $this->conn = $conn;
            
        }

        
        function read(){            

            $query = "select ".$this->table_name.".* ". 
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name.".patient_feedback_id = ".$this->patient_feedback_id;
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        
        function create(){

            $query = "insert into 
                        ".$this->table_name."
                        SET patient_feedback_id =:patient_feedback_id , detail =:detail, 
                        patient_id=:patient_id,
                        doctor_id=:doctor_id, created_at=CURRENT_TIMESTAMP, updated_at=CURRENT_TIMESTAMP";
            
            $stmt = $this->conn->prepare($query);
            
            // $this->first_name=htmlspecialchars(strip_tags($this->first_name));

            $stmt->bindParam(":patient_feedback_id", $this->patient_feedback_id);
            $stmt->bindParam(":detail", $this->detail);
            $stmt->bindParam(":patient_id", $this->patient_id);
            $stmt->bindParam(":doctor_id", $this->doctor_id);

            if($stmt->execute()){
                return true;
            }

            return false;
            //return $stmt->errorInfo();
        }

        function update(){
            $query = "update ".$this->table_name." SET reply_read = 1,
            updated_at=CURRENT_TIMESTAMP WHERE id=:id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);

            if($stmt->execute()){
                return true;
            }

            return false;
        }
    }
?>