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
    $doctor->user_id = $data->user_id;

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }
    

    $stmt = $doctor->read();
    $num = $stmt->rowCount();


    if($num > 0){

        $records = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            extract($row);

            $hours = array();

            $age = date_diff(date_create($date_of_birth), date_create());

            $stmtHours = $doctor->getHours($id);
            while($rowHours = $stmtHours->fetch(PDO::FETCH_ASSOC)){
                $hours[] = $rowHours;
            }

            $records[] = array(
                "id" => $id,
                "name" => $first_name.' '.$last_name,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "date_of_birth" => $date_of_birth,
                "gender" => $gender,                
                "age" => $age->format("%y"),
                "work_address" => $work_address,
                "email_address" => $email_address,
                "work_number" => $work_number,
                "speciality_id" => $speciality_id,
                "location_lat" => $location_lat,
                "location_lng" => $location_lng,
                "role_id" => 2,
                "hours" => $hours,
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