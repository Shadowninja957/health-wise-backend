<?php

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // header("Access-Control-Allow-Credentials: true");
    
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

    
    if(
        !empty($data->patient_id)&&
        !empty($data->doctor_id)&&
        !empty($data->date)&&
        !empty($data->time)       
    ){
        $appointment->patient_id = $data->patient_id;
        $appointment->doctor_id = $data->doctor_id;
        $appointment->date = $data->date;
        $appointment->time = $data->time;
        $appointment->details = $data->details;

        

        if($appointment->create()){

            http_response_code(201);

            echo json_encode(array("message"=>"Appointment created."));
        }
        else{

            http_response_code(503);

            echo json_encode(array("message"=>"Unable to Create appointment."));
        }
    }
    else{

        http_response_code(400);

        echo json_encode(array("message"=>"Unable to create appointment. Data is incomplete."));
    }

    

?>