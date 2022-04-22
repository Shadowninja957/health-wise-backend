<?php

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // header("Access-Control-Allow-Credentials: true");
    
    include_once '../config/database.php';

    include_once '../objects/doctor.php';

    $database = new Database();
    $conn = $database->getConnection();

    $doctor = new Doctor($conn);

    $data = json_decode(file_get_contents("php://input"));
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }

    
    if(
        !empty($data->id)
    ){
        $doctor->id = $data->id;
        // $doctor->user_id = $data->user_id;
        $doctor->first_name = $data->first_name;
        $doctor->last_name = $data->last_name;
        $doctor->date_of_birth = $data->date_of_birth;
        $doctor->gender = $data->gender;
        $doctor->work_address = $data->work_address;
        $doctor->email_address = $data->email_address;
        $doctor->work_number = $data->work_number;
        $doctor->office_days = $data->office_days;
        $doctor->start_time = $data->start_time;
        $doctor->end_time = $data->end_time;
        // $doctor->work_hours = $data->work_hours;
        $doctor->speciality_id = $data->speciality_id;
        $doctor->location_lat = $data->location_lat;
        $doctor->location_lng = $data->location_lng;

        $doctor_info = $doctor->update();

        $current_office_hours = $doctor->getHours($data->id);
        $num = $current_office_hours->rowCount();

        if($current_office_hours > 0){
            
            // foreach($data->office_days as $office_day){
            //     switch ($office_day){
            //         case 'Sunday':

            //     }
            // }
        }

        if($doctor->update()){

            http_response_code(200);

            echo json_encode(array("message"=>"Record updated.", "doctor" => $doctor));
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