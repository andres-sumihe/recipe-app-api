<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

include_once '../models/grocery.php';
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';

$database = new Database();
$db = $database->getConnection();

$grocery = new Grocery($db);

use \Firebase\JWT\JWT;

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

    if (
        !empty($data->groceries_name) &&
        !empty($data->groceries_quantity) &&
        !empty($data->recipe_id)
    ) {

        $grocery->groceries_name = $data->groceries_name;
        $grocery->groceries_quantity = $data->groceries_quantity;
        $grocery->recipe_id = $data->recipe_id;


        if ($grocery->create($grocery->recipe_id)) {
            http_response_code(201);
            echo json_encode(array("message" => "Grocery was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create grocery $grocery->recipe_id."));
        }
    } else {

        http_response_code(400);
        echo json_encode(array("message" => "Unable to create grocery $grocery->recipe_id. Data is incomplete."));
    }
}
