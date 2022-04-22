<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';    
    include_once '../objects/doctor.php';

    $database = new Database();
    $conn = $database->getConnection();
    

    $doctor = new Doctor($conn);
    

    $stmt = $doctor->readDoctors();
    $num = $stmt->rowCount();


    if($num > 0){

        $records = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            extract($row);

            $age = date_diff(date_create($date_of_birth), date_create());
            $hours = array();
            $stmtHours = $doctor->getHours($id);
            while($rowHours = $stmtHours->fetch(PDO::FETCH_ASSOC)){
                $hours[] = $rowHours;
            }

            $records[] = array(
                "id" => $id,
                "name" => $first_name.' '.$last_name,                
                "age" => $age->format("%y"),
                "work_address" => $work_address,
                "email_address" => $email_address,
                "work_number" => $work_number,
                "speciality" => $detail,
                "location_lat" => $location_lat,
                "location_lng" => $location_lng,
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