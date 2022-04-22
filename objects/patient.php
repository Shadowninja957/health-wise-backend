<?php
    class Patient{
        
        private $conn;
        private $table_name = "patients";
        
        public $user_id;
        public $first_name;
        public $last_name;
        public $contact_number;
        public $address;
        
        public function __construct($conn){
            $this->conn = $conn;
            
        }

        function read(){            

            $query = "select * FROM ".$this->table_name." WHERE user_id = ".$this->user_id;
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }
        
        function create(){
           //create patient record
            $query = "insert into 
            ".$this->table_name."
            SET user_id =:user_id , first_name =:first_name, 
            last_name=:last_name, contact_number=:contact_number,
            address=:address";

            $stmt = $this->conn->prepare($query);

            // $this->first_name=htmlspecialchars(strip_tags($this->first_name));

            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":contact_number", $this->contact_number);
            $stmt->bindParam(":address", $this->address);

            if($stmt->execute()){
            return true;
            }

            return false;
        }

        function update() 
        {
            $query = "UPDATE ".$this->table_name." SET first_name=:first_name,
            last_name=:last_name, date_of_birth=:date_of_birth, gender=:gender,
            contact_number=:contact_number,updated_at=CURRENT_TIMESTAMP
            WHERE id=:id";

            $stmt = $this->conn->prepare($query);

            $this->first_name=htmlspecialchars(strip_tags($this->first_name));
            $this->last_name=htmlspecialchars(strip_tags($this->last_name));
            $this->date_of_birth= $this->date_of_birth ? htmlspecialchars(strip_tags($this->date_of_birth)) : null;
            $this->gender=htmlspecialchars(strip_tags($this->gender));
            $this->contact_number=htmlspecialchars(strip_tags($this->contact_number));

            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":date_of_birth", $this->date_of_birth);
            $stmt->bindParam(":gender", $this->gender);
            $stmt->bindParam(":contact_number", $this->contact_number);

            if($stmt->execute()){
                return true;
            }

            return false;
        }
    }
?>