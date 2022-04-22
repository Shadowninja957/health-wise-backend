<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';    
    include_once '../objects/doctor.php';

    $database = new Database();
    $conn = $database->getConnection();
    

    $doctor = new Doctor($conn);
    $data = json_decode(file_get_contents("php://input"));
    $doctor->id = $data->id;
    $doctor->date = $data->date;

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }
    

    $stmt = $doctor->getAppointments();
    $num = $stmt->rowCount();
    $records = array();
    try {
        if($num > 0){

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                
                $records[] = $row;
            }
    
            http_response_code(200);
            
        }

        echo json_encode($records);

    } catch (Exception $e) {
        http_response_code(500);

        echo json_encode(
            array("message" => $e)
        );
    }
    

?>