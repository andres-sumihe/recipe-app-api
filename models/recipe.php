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
        $query = "INSERT INTO " . $this->table_name . " 
                SET 
                recipe_name=:recipe_name, 
                picture_recipe_url=:picture_recipe_url, 
                description=:description, 
                user_id=:user_id, 
                category_id=:category_id";
        $stmt = $this->conn->prepare($query);
      
        $this->recipe_name=htmlspecialchars(strip_tags($this->recipe_name));
        $this->picture_recipe_url=htmlspecialchars(strip_tags($this->picture_recipe_url));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        
        $stmt->bindParam(":recipe_name", $this->recipe_name);
        $stmt->bindParam(":picture_recipe_url", $this->picture_recipe_url);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":category_id", $this->category_id);
        

        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }
}
?>