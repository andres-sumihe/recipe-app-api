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
include_once '../models/cooking_step.php';

$database = new Database();
$db = $database->getConnection();

$cooking_steps = new CookingStep($db);



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

    $stmt = $cooking_steps->getCookingStepsByRecipeId($data->recipe_id);
    $num = $stmt->rowCount();

    if ($num > 0) {

        $cooking_steps_arr = array();
        $cooking_steps_arr["data"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $response = array(
                "cooking_steps_id" => $cooking_steps_id,
                "title" => $title,
                "description" => $description,
                "recipe_id" => $recipe_id
            );

            array_push($cooking_steps_arr["data"], $response);
        }

        http_response_code(200);

        echo json_encode($cooking_steps_arr);
    } else {

        http_response_code(404);
        echo json_encode(
            array("message" => "No data found.")
        );
    }

} else {

    http_response_code(401);

    echo json_encode(array("message" => "Access denied."));
}
