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

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }
    

    $stmt = $doctor->getHours($data->id);
    $num = $stmt->rowCount();

    if($num > 0){

        $records = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            extract($row);

            $records[] = array(
                "id" => $id,
                "day" => $day,
                "start_time" => $start_time,
                "end_time" => $end_time,
            );

        }

        http_response_code(200);

        echo json_encode($records);
    }
    else{
        
        http_response_code(404);

        echo json_encode(
            array("message" => "No work hours found.")
        );
    }

    
    

?>