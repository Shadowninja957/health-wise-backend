<?php

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // header("Access-Control-Allow-Credentials: true");
    
    include_once '../config/database.php';

    include_once '../objects/feedbackReplies.php';

    $database = new Database();
    $conn = $database->getConnection();

    $feedbackReplies = new FeedbackReplies($conn);

    $data = json_decode(file_get_contents("php://input"));
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }

    
    if(
        !empty($data->patient_feedback_id)&&
        !empty($data->detail)      
    ){
        $feedbackReplies->patient_feedback_id = $data->patient_feedback_id;
        $feedbackReplies->detail = $data->detail;
        $feedbackReplies->patient_id = $data->patient_id;
        $feedbackReplies->doctor_id = $data->doctor_id;

        if($feedbackReplies->create()){

            http_response_code(201);

            echo json_encode(array("message"=>"Feedback reply created."));
        }
        else{

            http_response_code(503);

            echo json_encode(array("message"=>"Unable to Create feedback reply."));
        }
    }
    else{

        http_response_code(400);

        echo json_encode(array("message"=>"Unable to create feedback reply. Data is incomplete."));
    }

    

?>