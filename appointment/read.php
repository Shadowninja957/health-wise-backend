<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';    
    include_once '../objects/appointment.php';

    $database = new Database();
    $conn = $database->getConnection();
    

    $appointment = new Appointment($conn);

    
    $data = json_decode(file_get_contents("php://input"));
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }
    
    if(isset($data->patient_id)) $appointment->patient_id = $data->patient_id;
    if(isset($data->doctor_id)) $appointment->doctor_id = $data->doctor_id;
    
    
    $stmt = $appointment->specialities();
    $specialities = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $specialities[] = array("id" => $id, "detail" => $detail);
    }
        
    $stmt = $appointment->read();
    $num = $stmt->rowCount();
    $records = array();

    if($num > 0){

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            extract($row);

            if(isset($data->patient_id)){
                //get patients appointments
                $age = date_diff(date_create($date_of_birth), date_create());

                $speciality = null;
                foreach($specialities as $value){
                    if($value["id"] == $speciality_id) $speciality = $value["detail"]; 
                }

                $records[] = array(
                    "id" => $id,
                    "name" => $first_name.' '.$last_name,                
                    "age" => $age->format("%y"),
                    "work_address" => $work_address,
                    "email_address" => $email_address,
                    "work_number" => $work_number,
                    "details" => $details,
                    "date" => $date,
                    "time" => $time,
                    "speciality" => $speciality,
                    "cancel" => $cancel,
                    "location_lat" => $location_lat,
                    "location_lng" => $location_lng,
                    "doctor_id" => $doctor_id,
                    "patient_id" => $patient_id,
                );
            }
            else{
                //get doctors appointments
                $records[] = array(
                    "id" => $id,
                    "name" => $first_name.' '.$last_name,
                    "contact_number" => $contact_number,
                    "details" => $details,
                    "date" => $date,
                    "time" => $time,
                    "cancel" => $cancel
                );
            }

           

        }       
    }
    
    http_response_code(200);

    echo json_encode($records);    
    

?>