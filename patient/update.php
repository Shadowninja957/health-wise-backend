<?php

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // header("Access-Control-Allow-Credentials: true");
    
    include_once '../config/database.php';

    include_once '../objects/patient.php';

    $database = new Database();
    $conn = $database->getConnection();

    $patient = new Patient($conn);

    $data = json_decode(file_get_contents("php://input"));
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }

    
    if(
        !empty($data->id)
    ){
        $patient->id = $data->id;
        $patient->first_name = $data->first_name;
        $patient->last_name = $data->last_name;
        $patient->date_of_birth = isset($data->date_of_birth) ? $data->date_of_birth : null;
        $patient->gender = isset($data->gender) ? $data->gender : null;
        $patient->contact_number = isset($data->contact_number) ? $data->contact_number: null;

        if($patient->update()){

            http_response_code(200);

            echo json_encode(array("message"=>"Record updated.", "patient" => $patient));
        }
        else{

            http_response_code(503);

            echo json_encode(array("message"=>"Unable to update."));
        }
    }
    else{

        http_response_code(400);

        echo json_encode(array("message"=>"Unable to update record. Data is incomplete."));
    }

    

?>