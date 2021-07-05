<?php
// headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/database.php';
include_once '../models/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));
 
$user->name = $data->name;
$user->username = $data->username;
$user->gender = $data->gender;
$user->email = $data->email;
$user->password = $data->password;
 
if(
    !empty($user->name) &&
    !empty($user->email) &&
    !empty($user->username) &&
    !empty($user->gender) &&
    !empty($user->password) &&
    $user->create()
){
 
    http_response_code(200);
    echo json_encode(array("message" => "user was created"));
}
 
else{
 
    http_response_code(400);
 
    echo json_encode(array("message" => "Unable to create user."));
}
?>