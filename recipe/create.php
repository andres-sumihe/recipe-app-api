<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
include_once '../config/database.php';
  
include_once '../models/recipe.php';
  
$database = new Database();
$db = $database->getConnection();
  
$recipe = new Recipe($db);
  
$data = json_decode(file_get_contents("php://input"));
  
if(
    !empty($data->recipe_name) &&
    !empty($data->groceries) &&
    !empty($data->coocking_steps) &&
    !empty($data->description) &&
    !empty($data->category_id)
){
  
    $recipe->recipe_id = $data->recipe_id;
    $recipe->user_id = $data->user_id;
    $recipe->category_id = $data->category_id;
    $recipe->recipe_name = $data->recipe_name;
    $recipe->picture = $data->picture;
    $recipe->description = $data->description;
    $recipe->groceries = $data->groceries;
    $recipe->coocking_steps = $data->coocking_steps;
    $recipe->created = date('Y-m-d H:i:s');
  
    if($recipe->create_recipe()){
        http_response_code(201);
        echo json_encode(array("message" => "Product was created."));
    }
  
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create recipe."));
    }
}
  
else{

    http_response_code(400);
    echo json_encode(array("message" => "Unable to create recipe. Data is incomplete."));
}
?>