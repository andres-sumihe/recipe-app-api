<?php
class Grocery
{

    private $conn;
    private $table_name = "tbl_groceries";

    public $groceries_id;
    public $groceries_name;
    public $groceries_quantity;
    public $recipe_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {

        $query = "INSERT INTO " . $this->table_name . "
            SET
                groceries_name = :groceries_name,
                groceries_quantity = :groceries_quantity,
                recipe_id = :recipe_id";
        $stmt = $this->conn->prepare($query);

        /* 
        Column

        `groceries_id`, 
        `groceries_name`, 
        `groceries_quantity`, 
        `recipe_id`, 
        */

        $this->groceries_name = htmlspecialchars(strip_tags($this->groceries_name));
        $this->groceries_quantity = htmlspecialchars(strip_tags($this->groceries_quantity));
        $this->recipe_id = htmlspecialchars(strip_tags($this->recipe_id));

        $stmt->bindParam(':groceries_name', $this->groceries_name);
        $stmt->bindParam(':groceries_quantity', $this->groceries_quantity);
        $stmt->bindParam(':recipe_id', $this->recipe_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

   
    public function getGroceryById($groceries_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE groceries_id = {$groceries_id}";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            if ($num > 0) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                $this->groceries_id = $row['groceries_id'];
                $this->groceries_name = $row['groceries_name'];
                $this->groceries_quantity = $row['groceries_quantity'];
        
                return true;
            }
    }
    public function getGroceryByRecipeId($recipe_id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE recipe_id = {$recipe_id}";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            // execute query
            $stmt->execute();
            
            return $stmt;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
            SET
            groceries_name = :groceries_name,
            groceries_quantity = :groceries_quantity,
            recipe_id = :recipe_id
            WHERE groceries_id = :groceries_id";

        $stmt = $this->conn->prepare($query);

        $this->groceries_id = htmlspecialchars(strip_tags($this->groceries_id));
        $this->groceries_name = htmlspecialchars(strip_tags($this->groceries_name));
        $this->groceries_quantity = htmlspecialchars(strip_tags($this->groceries_quantity));
        $this->recipe_id = htmlspecialchars(strip_tags($this->recipe_id));

        $stmt->bindParam(':groceries_id', $this->groceries_id);
        $stmt->bindParam(':groceries_name', $this->groceries_name);
        $stmt->bindParam(':groceries_name', $this->groceries_name);
        $stmt->bindParam(':recipe_id', $this->recipe_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
