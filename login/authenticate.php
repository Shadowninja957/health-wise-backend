<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';    
    include_once '../objects/user.php';

    $database = new Database();
    $conn = $database->getConnection();
    

    $user = new User($conn);

    $data = json_decode(file_get_contents("php://input")); 
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // The request is using the POST method
        header("HTTP/1.1 200 OK");
        return;
    
    }

    
    if(
        !empty($data->user_name)&&
        !empty($data->password)       
    ){
        $user->user_name = $data->user_name;
        $user->password = $data->password;
        $stmt = $user->authenticate();
        $num = $stmt->rowCount();

        if($num > 0){
               
            http_response_code(200);

            $data = [];

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
                extract($row);
    
                $data[] = array(
                    "user_id" => $id,
                    "role_id" => $role_id,
                );
    
            }
    
            echo json_encode(
                array("message" => "User Authenticated.", "data" => $data)
            );
        }
        else{
            
            http_response_code(404);
    
            echo json_encode(
                array("message" => "User not found.")
            );
        }
        
    }
    else{

        http_response_code(400);

        echo json_encode(array("message"=>"Unable to authenticate. Data is incomplete."));
    }

?>