<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';    
    include_once '../objects/feedbackReplies.php';

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }

    $database = new Database();
    $conn = $database->getConnection();
    

    $feedbackReplies = new FeedbackReplies($conn);
    $data = json_decode(file_get_contents("php://input"));
    $feedbackReplies->patient_feedback_id = $data->patient_feedback_id;

    $stmt = $feedbackReplies->read();
    $num = $stmt->rowCount();
    $records = array();


    if($num > 0){
       
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            extract($row);

            $records[] = array(
                "id" => $id,
                "patient_feedback_id" => $patient_feedback_id,                
                "detail" => $detail,
                "reply_read" => $reply_read,
                "patient_id" => $patient_id,
                "doctor_id" => $doctor_id,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
            );

        }       
    }
    
    http_response_code(200);

    echo json_encode($records);    
    

?>