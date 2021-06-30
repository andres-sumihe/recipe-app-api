<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../models/recipe.php';

$database = new Database();
$db = $database->getConnection();
  
$recipe = new Recipe($db);

$stmt = $recipe->getAllRecipe();
$num = $stmt->rowCount();
  
if($num>0){
  
    $recipes_arr=array();
    $recipes_arr["records"]=array();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
    
        $recipe_item=array(
            "recipe_id" => $recipe_id,
            "user_id" => $user_id,
            "category_id" => $category_id,
            "recipe_name" => $recipe_name,
            "picture_recipe_url" => $picture_recipe_url,
            "description" => $description,
        );
  
        array_push($recipes_arr["records"], $recipe_item);
    }
  
        http_response_code(200);
    
        echo json_encode($recipes_arr);
    }else{
    
        http_response_code(404);
        echo json_encode(
            array("message" => "No products found.")
        );
    }

// {
//     "records": [
//         {
//             "recipe_id": "1",
//             "user": {
//                 "user_id" : "1",
//                 .....
//             },
//             "category": {
//                 "category_id" : "1",
//                 "category_name" : "Gorengan"
//             },
//             "recipe_name": "Tahu Isi Maknyos",
//             "picture_recipe_url": "test",
//             "description": "Tahu yang berisi"
//         }
//     ]
// } 
?>