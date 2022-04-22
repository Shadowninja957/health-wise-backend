<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';    
    include_once '../objects/user.php';

    $database = new Database();
    $conn = $database->getConnection();
    

    $user = new User($conn);
    

    $stmt = $user->read();
    $num = $stmt->rowCount();


    if($num > 0){

        $userRecords = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            extract($row);

            $userRecords[] = array(
                "user_name" => $user_name,
            );

        }

        http_response_code(200);

        echo json_encode($userRecords);
    }
    else{
        
        http_response_code(404);

        echo json_encode(
            array("message" => "No users found.")
        );
    }

    
    

?>