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
include_once '../models/grocery.php';

$database = new Database();
$db = $database->getConnection();

$grocery = new Grocery($db);


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
    
    // $user_id = $decoded->data->id;

    
    if ($grocery->getGroceryByRecipeId($data->recipe_id)) {
        $response = array(
            "data" => array(
                "groceries_id" => $grocery->groceries_id,
                "groceries_name" => $grocery->groceries_name,
                "groceries_quantity" => $grocery->groceries_quantity,
                "recipe_id" => $grocery->recipe_id
            )
        );

        http_response_code(200);

        echo json_encode(
            array(
                "message" => "Here your grocery data.",
                "response" => $response
            )
        );
    }

    else {
        http_response_code(401);

        echo json_encode(array("message" => "Unable to update grocery."));
    }
}
else{
 
    http_response_code(401);
 
    echo json_encode(array("message" => "Access denied."));
}
