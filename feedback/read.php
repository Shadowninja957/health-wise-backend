<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';    
    include_once '../objects/feedback.php';

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }

    $database = new Database();
    $conn = $database->getConnection();
    

    $feedback = new Feedback($conn);
    $data = json_decode(file_get_contents("php://input"));
    $feedback->patient_id = isset($data->patient_id) ? $data->patient_id : null;
    $feedback->doctor_id = isset($data->doctor_id) ? $data->doctor_id: null;

    if($data->doctor_id) $stmt = $feedback->readDoctor();
    else $stmt = $feedback->read();
    $num = $stmt->rowCount();


    if($num > 0){

        $records = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            extract($row);
            $replies = array();
            $stmtReplies = $feedback->replies($id);
            while($rowReplies = $stmtReplies->fetch(PDO::FETCH_ASSOC)){
                $replies[] = $rowReplies;
            }

            $records[] = array(
                "id" => $id,
                "name" => $name,                
                "email" => $email,
                "contact" => $contact,
                "doctor" => $first_name." ".$last_name,
                "subject" => $subject,
                "description" => $description,
                "replies" => $replies,
            );

        }

        http_response_code(200);

        echo json_encode($records);
    }
    else{
        
        http_response_code(404);

        echo json_encode(
            array("message" => "No feedback found.")
        );
    }

    
    

?>