<?php
class User
{

    private $conn;
    private $table_name = "tbl_user";

    public $user_id;
    public $username;
    public $password;
    public $email;
    public $phone_number;
    public $gender;
    public $picture_url;
    public $date_of_birth;
    public $name;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {

        $query = "INSERT INTO " . $this->table_name . "
            SET
                username = :username,
                email = :email,
                password = :password,
                gender = :gender,
                name = :name";
        $stmt = $this->conn->prepare($query);

        /* 
        Column

        `user_id`, 
        `username`, 
        `password`, 
        `email`, 
        `phone_number`, 
        `gender`, 
        `picture_url`, 
        `date_of_birth`, 
        `name`
        */

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->gender = htmlspecialchars(strip_tags($this->gender));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function emailExists()
    {

        $query = "SELECT user_id, username, name, password
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(1, $this->email);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['user_id'];
            $this->username = $row['username'];
            $this->name = $row['name'];
            $this->password = $row['password'];

            return true;
        }

        return false;
    }

    public function getUserById($user_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = {$user_id}";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            if ($num > 0) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->username = $row['username'];
                $this->picture_url = $row['picture_url'];
        
                return true;
            }
    }

    public function update()
    {

        $password_set = !empty($this->password) ? ", password = :password" : "";

        $query = "UPDATE " . $this->table_name . "
            SET
                name = :name,
                email = :email
                {$password_set}
            WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);

        if (!empty($this->password)) {
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    public function update_profile_photo()
    {

        $query = "UPDATE " . $this->table_name . "
            SET
                picture_url = :picture_url
            WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $this->picture_url = htmlspecialchars(strip_tags($this->picture_url));

        $stmt->bindParam(':picture_url', $this->picture_url);
        

        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
