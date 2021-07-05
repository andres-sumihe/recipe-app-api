<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

include_once '../config/database.php';
include_once '../models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);


$data = json_decode(file_get_contents("php://input"));
$jwt = isset($data->jwt) ? $data->jwt : "";
if ($jwt) {

    try {

        $decoded = JWT::decode($jwt, $key, array('HS256'));
    } catch (Exception $e) {

        http_response_code(401);

        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
    
    $user->user_id = $decoded->data->id;

    
    if ($user->getUserById($user->user_id)) {
        $response = array(
            "data" => array(
                "user_id" => $user->user_id,
                "name" => $user->name,
                "email" => $user->email,
                "username" => $user->username
            )
        );

        http_response_code(200);

        echo json_encode(
            array(
                "message" => "Here your user data.",
                "response" => $response
            )
        );
    }

    else {
        http_response_code(401);

        echo json_encode(array("message" => "Unable to update user."));
    }
}
else{
 
    http_response_code(401);
 
    echo json_encode(array("message" => "Access denied."));
}
