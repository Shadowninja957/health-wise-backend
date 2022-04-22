<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';    
    include_once '../objects/speciality.php';

    $database = new Database();
    $conn = $database->getConnection();
    

    $speciality = new Speciality($conn);

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }
    
    $stmt = $speciality->read();
    $num = $stmt->rowCount();
    $records = array();

    if($num > 0){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $records[] = $row;            
        }        
    }

    http_response_code(200);

    echo json_encode($records);

?>