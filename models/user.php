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
}
