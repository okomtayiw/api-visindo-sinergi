<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/apivisindo/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here
// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/package.php';

 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$package = new Package($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$package->namepackage = $data->namepackage;
$package->abonemen =  $data->abonemen;
$package->description =  $data->description;


 
// use the create() method here

// create the user
if(
    !empty($package->namepackage) && !empty($package->abonemen) && !empty($package->description) &&
    $package->create()
){
 
    // set response code
    http_response_code(200);
 
    // display message: user was created
    echo json_encode(array("message" => "Package was created."));
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create package."));
}

?>