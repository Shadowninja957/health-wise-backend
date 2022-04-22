<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';    
    include_once '../objects/patient.php';

    $database = new Database();
    $conn = $database->getConnection();
    

    $patient = new Patient($conn);
    $data = json_decode(file_get_contents("php://input"));
    $patient->user_id = $data->user_id;

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }
    

    $stmt = $patient->read();
    $num = $stmt->rowCount();


    if($num > 0){

        $records = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            extract($row);

            $records[] = array(
                "id" => $id,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "gender" => $gender,
                "date_of_birth" => $date_of_birth,                
                "contact_number" => $contact_number,
                "address" => $address,
                "role_id" => 1,
            );

        }

        http_response_code(200);

        echo json_encode($records);
    }
    else{
        
        http_response_code(404);

        echo json_encode(
            array("message" => "No users found.")
        );
    }

    
    

?>