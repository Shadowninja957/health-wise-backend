<?php
    class Doctor{
        
        private $conn;
        private $table_name = "doctors";
        
        public $id;
        public $user_id;
        public $first_name;
        public $last_name;
        public $date_of_birth;
        public $gender;
        public $work_address;
        public $email_address;
        public $contact_number;
        public $office_days;
        public $work_hours;
        public $speciality_id;
        public $location_lat;
        public $location_lng;
        public $date;
        public $start_time;
        public $end_time;
        
        
        public function __construct($conn){
            $this->conn = $conn;
            
        }

        function read(){            

            $query = "select * FROM ".$this->table_name." WHERE user_id = ".$this->user_id;
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        function readDoctors(){            

            // $query = "select * from ".$this->table_name;
            // $query = "select ".$this->table_name.".*, specialities.detail FROM specialities, ".
            // $this->table_name." WHERE ".$this->table_name.".speciality_id = specialities.id";
            $query = "select ".$this->table_name.".*, specialities.detail FROM ".$this->table_name.
            " LEFT JOIN specialities ON ".$this->table_name.".speciality_id = specialities.id";
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }
        
        function create(){
            //create doctor record
            $query = "insert into 
            ".$this->table_name."
            SET user_id =:user_id , first_name =:first_name, 
            last_name=:last_name, created_at=CURRENT_TIMESTAMP, updated_at=CURRENT_TIMESTAMP";

            $stmt = $this->conn->prepare($query);

            // $this->first_name=htmlspecialchars(strip_tags($this->first_name));

            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);

            if($stmt->execute()){
            return true;
            }

            return false;
        }

        function update() 
        {
            $query = "UPDATE ".$this->table_name." SET first_name=:first_name,
            last_name=:last_name, date_of_birth=:date_of_birth, gender=:gender,
            work_address=:work_address, email_address=:email_address,
            work_number=:work_number,
            speciality_id=:speciality_id, location_lat=:location_lat, 
            location_lng=:location_lng, updated_at=CURRENT_TIMESTAMP
            WHERE id=:id";

            $stmt = $this->conn->prepare($query);

            $this->first_name=htmlspecialchars(strip_tags($this->first_name));
            $this->last_name=htmlspecialchars(strip_tags($this->last_name));
            $this->date_of_birth= $this->date_of_birth ? htmlspecialchars(strip_tags($this->date_of_birth)) : null;
            $this->gender=htmlspecialchars(strip_tags($this->gender));
            $this->work_address=htmlspecialchars(strip_tags($this->work_address));
            $this->email_address=htmlspecialchars(strip_tags($this->email_address));
            $this->work_number=htmlspecialchars(strip_tags($this->work_number));
            $this->speciality_id= $this->speciality_id ? htmlspecialchars(strip_tags($this->speciality_id)) : null;
            $this->location_lat= $this->location_lat ? htmlspecialchars(strip_tags($this->location_lat)) : null;
            $this->location_lng= $this->location_lng ? htmlspecialchars(strip_tags($this->location_lng)) : null;

            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":date_of_birth", $this->date_of_birth);
            $stmt->bindParam(":gender", $this->gender);
            $stmt->bindParam(":work_address", $this->work_address);
            $stmt->bindParam(":email_address", $this->email_address);
            $stmt->bindParam(":work_number", $this->work_number);
            $stmt->bindParam(":speciality_id", $this->speciality_id);
            $stmt->bindParam(":location_lat", $this->location_lat);
            $stmt->bindParam(":location_lng", $this->location_lng);

            if($stmt->execute()){
                return true;
            }

            return false;
        }

        function updateOfficeHours($id, $day, $start_time, $end_time)
        {
            $query = "UPDATE doctor_hours SET start_time = ".$start_time.
            ", end_time = ".$end_time.", updated_at = CURRENT_TIMESTAMP".
            "WHERE doctor_id = ".$id." AND day = ".$day;
            $stmt = $this->conn->prepare($query);
            if($stmt->execute())return true;
            return false;
        }

        function addOfficeHours ($id, $day, $start_time, $end_time)
        {
            $query = "INSERT INTO doctor_hours(doctor_id, day, start_time, end_time, created_at, updated_at) ".
            " VALUES (".$id.",".$day.",".$start_time.",".$end_time.", CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $stmt = $this->conn->prepare($query);
            if($stmt->execute()) return true;
            return false; 
        }
        
        function deleteOfficeHours ($id, $day)
        {
            $query = "DELETE FROM doctor_hours WHERE doctor_id = ".$id.
            "AND day =".$day;
            $stmt = $this->conn->prepare($query);
            if($stmt->execute()) return true;
            return false;
        }

        function getAppointments ()
        {
            $query = "SELECT * FROM appointments WHERE date = '".$this->date."'".
            " AND doctor_id = ".$this->id;

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        function getHours ($id)
        {
            $query = "SELECT * FROM doctor_hours WHERE doctor_id = ".$id;

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }
    }
?>