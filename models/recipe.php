<?php
    class Recipe{
    
        // database connection and table name
        private $conn;
        private $table_name = "tbl_recipe";
    
        // object properties
        public $recipe_id;
        public $user_id;
        public $category_id;
        public $recipe_name;
        public $picture_recipe_url;
        public $description;
        // public $category_name;
    
        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        function getAllRecipe(){
  
            // select all query
            $query = "SELECT * FROM " . $this->table_name;
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            // execute query
            $stmt->execute();
          
            return $stmt;

    }

    function create_recipe(){
  
        // query to insert record
        // add query here for create recipe [POST]
        $query = "INSERT INTO " . $this->table_name . " SET recipe_id=:recipe_id, recipe_name=:recipe_name, picture_recipe_url=:picture_recipe_url, user_id=:user_id, category_id=:category_id";
        $stmt = $this->conn->prepare($query);
      
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->recipe_name=htmlspecialchars(strip_tags($this->recipe_name));
        $this->picture=htmlspecialchars(strip_tags($this->picture));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->groceries=htmlspecialchars(strip_tags($this->groceries));
        $this->coocking_steps=htmlspecialchars(strip_tags($this->coocking_steps));
        $this->created=htmlspecialchars(strip_tags($this->created));
        
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":recipe_name", $this->recipe_name);
        $stmt->bindParam(":picture", $this->picture);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":groceries", $this->groceries);
        $stmt->bindParam(":coocking_steps", $this->coocking_steps);
        $stmt->bindParam(":created", $this->created);

        
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }
}
?>