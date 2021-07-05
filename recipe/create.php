<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

include_once '../models/recipe.php';
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';

$database = new Database();
$db = $database->getConnection();

$recipe = new Recipe($db);

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
        !empty($data->recipe_name) &&
        !empty($data->picture_recipe_url) &&
        !empty($data->description) &&
        !empty($data->category_id)
    ) {

        $recipe->recipe_name = $data->recipe_name;
        $recipe->picture_recipe_url = $data->picture_recipe_url;
        $recipe->description = $data->description;
        $recipe->user_id = $decoded->data->id;
        $recipe->category_id = $data->category_id;


        if ($recipe->create_recipe()) {
            http_response_code(201);
            echo json_encode(array("message" => "Product was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create recipe."));
        }
    } else {

        http_response_code(400);
        echo json_encode(array("message" => "Unable to create recipe. Data is incomplete."));
    }
}
