<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// required to encode json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);
$headers = getallheaders();
$jwt = $headers["Authorization"];
 
// get posted data
$datas = file_get_contents("php://input");
$data =  json_decode($datas);

 

 $user->firstname = $data->firstname;
 $user->lastname = $data->lastname;
 $user->email = $data->email;
 $user->id = $data->id;
 $user->nohp = $data->nohp;
 $user->numbercustomer = $data->number;
 
 
 
// decode jwt here
// if jwt is not empty
if($jwt){
 
    // if decode succeed, show user details
    try {
 
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
                // set user property values
      
        
        // update the user record
        if($user->update()){
            // we need to re-generate jwt because user details might be different
            $token = array(
                "iat" => $issued_at,
                "exp" => $expiration_time,
                "iss" => $issuer,
                "data" => array(
                    "id" => $user->id,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "number_customer" => $user->numbercustomer
                )
            );
            $jwt = JWT::encode($token, $key);
            
            // set response code
            http_response_code(200);
            
            // response in json format
            echo json_encode(
                    array(
                        "message" => "User was updated.",
                        "jwt" => $jwt
                    )
                );
        }else{
            // set response code
            http_response_code(401);
        
            // show error message
            echo json_encode(array("message" => "Unable to update user."));
        }
    }catch (Exception $e){
 
        // set response code
        http_response_code(401);
     
        // show error message
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
} else{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
}

?>


