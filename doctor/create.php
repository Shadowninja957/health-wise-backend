<?php

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // header("Access-Control-Allow-Credentials: true");
    
    include_once '../config/database.php';

    include_once '../objects/doctor.php';

    $database = new Database();
    $conn = $database->getConnection();

    $doctor = new Doctor($conn);

    $data = json_decode(file_get_contents("php://input"));
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }

    
    if(
        !empty($data->user_id)
    ){
        $doctor->user_id = $data->user_id;
        $doctor->first_name = $data->first_name;
        $doctor->last_name = $data->last_name;
        // $doctor->contact_number = $data->contact_number;
        // $doctor->address = $data->address;

        if($doctor->create()){

            http_response_code(201);

            echo json_encode(array("message"=>"Doctor added."));
        }
        else{

            http_response_code(503);

            echo json_encode(array("message"=>"Unable to add Doctor."));
        }
    }
    else{

        http_response_code(400);

        echo json_encode(array("message"=>"Unable to add record. Data is incomplete."));
    }

    

?>