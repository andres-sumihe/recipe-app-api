<?php
    // headers
    header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';
    include_once '../models/user.php';

    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    $user->email = $data->email;
    $email_exists = $user->emailExists();

    // generate json web token
    include_once '../config/core.php';
    include_once '../libs/php-jwt-master/src/BeforeValidException.php';
    include_once '../libs/php-jwt-master/src/ExpiredException.php';
    include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once '../libs/php-jwt-master/src/JWT.php';

    use \Firebase\JWT\JWT;

    if ($email_exists && password_verify($data->password, $user->password)) {

        $token = array(
            "iat" => $issued_at,
            // "exp" => $expiration_time,
            "iss" => $issuer,
            "data" => array(
                "id" => $user->id,
                "username" => $user->username,
                "name" => $user->name,
                "email" => $user->email
            )
        );

        http_response_code(200);

        $jwt = JWT::encode($token, $key);
        echo json_encode(
            array(
                "message" => "Successful login.",
                "jwt" => $jwt
            )
        );
    }
    // login failed
    else {

        http_response_code(401);

        echo json_encode(array("message" => "Login failed. pass: " . $user->password  . $user->email ));
    }

?>
