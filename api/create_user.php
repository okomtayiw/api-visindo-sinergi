<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here
// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$user = new User($db);

// get posted data
$datas = file_get_contents("php://input");
$data =  json_decode($datas);
 
// set product property values
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;
$user->numbercustomer = $data->numbercustomer;
$user->nohp = $data->nohp;
$number = $user->numberExists();
$email = $user->emailExists();


 
// use the create() method here

if($number != null || $email != null){
        // set response code
        http_response_code(401);
     
        // tell the user login failed
        echo json_encode(array("message" => "User Is ready.")); 
} else {
    // create the user
    if(
        !empty($user->firstname) &&
        !empty($user->email) &&
        !empty($user->password) &&
        !empty($user->numbercustomer) &&
        !empty($user->nohp) &&
        $user->create()
    ){
        // set response code
        http_response_code(200);
    
        // display message: user was created
        echo json_encode(array("message" => "User was created."));
    }
    
    // message if unable to create user
    else{
    
        // set response code
        http_response_code(400);
        
        // display message: unable to create user
        echo json_encode(array("message" => "Unable to create user."));
    }
}
?>