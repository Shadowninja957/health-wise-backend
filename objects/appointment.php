<?php
    class Appointment{
        
        private $conn;
        private $table_name = "appointments";
        
        public $patient_id;
        public $doctor_id;
        public $date;
        public $time;
        public $details;
        public $id;
        
        public function __construct($conn){
            $this->conn = $conn;
            
        }

        function read(){            

            // $query = "select * from ".$this->table_name;
            if($this->patient_id){
                // $query = "select ".$this->table_name.".*, doctors.first_name, doctors.last_name, ". 
                // " doctors.work_address, doctors.date_of_birth, doctors.work_address, ".
                // " doctors.email_address, doctors.work_number, ".
                // " specialities.detail FROM ".$this->table_name.", doctors, specialities ".
                // " WHERE ".$this->table_name.".doctor_id = doctors.id ".
                // " AND doctors.speciality_id = specialities.id ".
                // " AND patient_id = ".$this->patient_id. 
                // " ORDER BY date , time DESC";

                $query = "select ".$this->table_name.".*, doctors.first_name, doctors.last_name, ". 
                " doctors.work_address, doctors.date_of_birth, doctors.work_address, ".
                " doctors.email_address, doctors.work_number, doctors.speciality_id, ".
                " doctors.location_lat, doctors.location_lng ".
                " FROM ".$this->table_name." INNER JOIN doctors ".
                " ON ".$this->table_name.".doctor_id = doctors.id ".
                " WHERE patient_id = ".$this->patient_id.               
                " ORDER BY date , time DESC";
            }
            else{
                $query = "SELECT ".$this->table_name.".*, patients.first_name, patients.last_name, ".
                " patients.contact_number ".
                " FROM ".$this->table_name.", patients ".
                " WHERE ".$this->table_name.".patient_id = patients.id ".
                " AND ".$this->table_name.".doctor_id = ".$this->doctor_id.
                " ORDER BY date, time DESC";
            }
            
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }
        
        function create(){

            $query = "insert into 
                        ".$this->table_name."
                        SET patient_id =:patient_id , doctor_id =:doctor_id, 
                        date=:date, time=:time,
                        details=:details,
                        created_at=CURRENT_TIMESTAMP,
                        updated_at=CURRENT_TIMESTAMP";
            
            $stmt = $this->conn->prepare($query);
            
            // $this->first_name=htmlspecialchars(strip_tags($this->first_name));

            $stmt->bindParam(":patient_id", $this->patient_id);
            $stmt->bindParam(":doctor_id", $this->doctor_id);
            $stmt->bindParam(":date", $this->date);
            $stmt->bindParam(":time", $this->time);
            $stmt->bindParam(":details", $this->details);

            if($stmt->execute()){
                return true;
            }

            return false;
            //return $stmt->errorInfo();
        }

        function update()
        {
            $query = "UPDATE ".$this->table_name."
                        SET date=:date, time=:time, updated_at=CURRENT_TIMESTAMP
                        WHERE id=:id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":date", $this->date);            
            $stmt->bindParam(":time", $this->time);            
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()){
                return true;
            }

            return false;
        }

        function cancel()
        {
            $query = "UPDATE ".$this->table_name."
            SET cancel=1, updated_at=CURRENT_TIMESTAMP WHERE id=:id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);

            if($stmt->execute()){
                return true;
            }

            return false;

        }

        function specialities()
        {
            $query = "SELECT * FROM specialities";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
    }
?>