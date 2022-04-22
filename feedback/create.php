<?php

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // header("Access-Control-Allow-Credentials: true");
    
    include_once '../config/database.php';

    include_once '../objects/feedback.php';

    $database = new Database();
    $conn = $database->getConnection();

    $feedback = new Feedback($conn);

    $data = json_decode(file_get_contents("php://input"));
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }

    
    if(
        !empty($data->name)&&
        !empty($data->email)&&
        !empty($data->contact)&&
        !empty($data->doctor)&&
        !empty($data->subject)&&
        !empty($data->description)       
    ){
        $feedback->patient_id = $data->patient_id;
        $feedback->name = $data->name;
        $feedback->email = $data->email;
        $feedback->contact = $data->contact;
        $feedback->doctor = $data->doctor;
        $feedback->subject = $data->subject;
        $feedback->description = $data->description;
        

        if($feedback->create()){

            http_response_code(201);

            echo json_encode(array("message"=>"Feedback created."));
        }
        else{

            http_response_code(503);

            echo json_encode(array("message"=>"Unable to Create feedback."));
        }
    }
    else{

        http_response_code(400);

        echo json_encode(array("message"=>"Unable to create feedback. Data is incomplete."));
    }

    

?>