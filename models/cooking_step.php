<?php
class CookingStep
{

    private $conn;
    private $table_name = "tbl_cooking_steps";

    public $cooking_steps_id;
    public $title;
    public $description;
    public $recipe_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create($recipe_id)
    {

        $query = "INSERT INTO " . $this->table_name . "
            SET
                title = :title,
                description = :description,    
                recipe_id = :recipe_id";
        $stmt = $this->conn->prepare($query);

        /* 
        Column

        `cooking_steps_id`, 
        `title`, 
        `description`, 
        `recipe_id`, 
        */

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $recipe_id = htmlspecialchars(strip_tags($recipe_id));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':recipe_id', $recipe_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

   
    public function getGroceryById($cooking_steps_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE cooking_steps_id = {$cooking_steps_id}";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            if ($num > 0) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                $this->cooking_steps_id = $row['cooking_steps_id'];
                $this->title = $row['title'];
                $this->description = $row['description'];
        
                return true;
            }
    }
    public function getCookingStepsByRecipeId($recipe_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE recipe_id = {$recipe_id}";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            // execute query
            $stmt->execute();

            // $num = $stmt->rowCount();
            // if ($num > 0) {

            //     $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            //     $this->cooking_steps_id = $row['cooking_steps_id'];
            //     $this->title = $row['title'];
            //     $this->description = $row['description'];
            //     $this->recipe_id = $row['recipe_id'];
        
            // }
            return $stmt;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
            SET
            title = :title,
            description = :description,
            recipe_id = :recipe_id
            WHERE cooking_steps_id = :cooking_steps_id";

        $stmt = $this->conn->prepare($query);

        $this->cooking_steps_id = htmlspecialchars(strip_tags($this->cooking_steps_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->recipe_id = htmlspecialchars(strip_tags($this->recipe_id));

        $stmt->bindParam(':cooking_steps_id', $this->cooking_steps_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':recipe_id', $this->recipe_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
