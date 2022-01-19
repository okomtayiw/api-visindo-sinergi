<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to encode json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
 
// database connection will be here
// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../config/header.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$user = new User($db);
$headers = getallheaders();
$jwt = $headers["Authorization"];



if($jwt){
 
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        if($_SERVER['REQUEST_METHOD'] === "GET"){
                $idUser = $_GET['ID'];
                $users = $user->getUser($idUser);
        
                if($users > 0){
        
                $users_arr = array();
        
                foreach ($users as $row){
        
                   $users_arr[] = array(
                     "id" => $row['id'],
                     "first_name" => $row["first_name"],
                     "last_name" => $row['last_name'],
                     "email" => $row["email"],
                     "number" => $row["number_customer"],
                     "address" => $row["address_customers"],
                     "name_package" =>  $row["name_package"],
                     "description" => $row["description"],
                     "date_installation" =>  $row["date_installation"],
                     "nohp" => $row["no_handphone"]
                   );
                }
        
                 http_response_code(200); // Ok
                 echo json_encode(array(
                   "status" => 1,
                   "data" => $users_arr
                 ));
        
                }else{
                    http_response_code(404); // no data found
                    echo json_encode(array(
                    "status" => 0,
                    "message" => "No Projects found"
                    ));
        
                }
            } 
    }
    catch (exception $e) {
        
        // set response code
        http_response_code(401);
    
        // tell the user access denied  & show error message
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
   
} else {

      // set response code
      http_response_code(401);
 
      // tell the user access denied
      echo json_encode(array("message" => "Access denied."));

}

    

?>