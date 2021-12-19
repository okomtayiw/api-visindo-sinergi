<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/apivisindo/");
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
include_once '../objects/package.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$package = new Package($db);
$headers = getallheaders();
$jwt = $headers["Authorization"];


if($jwt){
 
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        if($_SERVER['REQUEST_METHOD'] === "GET"){

                $packages = $package->gatPackage();
        
                if($packages > 0){
        
                $package_arr = array();
        
                foreach ($packages as $row){
        
                   $package_arr[] = array(
                     "id_package" => $row['id_package'],
                     "name_package" => $row["name_package"],
                     "abonemen" => $row['abonemen'],
                     "descrption" => $row["description"]
                   );
                }
        
                 http_response_code(200); // Ok
                 echo json_encode(array(
                   "status" => 1,
                   "data" => $package_arr
                 ));
        
                }else{
                    http_response_code(404); // no data found
                    echo json_encode(array(
                    "status" => 0,
                    "message" => "No Package found"
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