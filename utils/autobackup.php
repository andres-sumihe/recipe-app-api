<?php

include_once '../config/database.php';
date_default_timezone_set('Asia/Jakarta');

include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../config/database.php';
include_once '../models/user.php';

require '../libs/PHPMailer-master/src/Exception.php';
require '../libs/PHPMailer-master/src/PHPMailer.php';
require '../libs/PHPMailer-master/src/SMTP.php';
require '../libs/PHPMailer-master/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use \Firebase\JWT\JWT;


$database = new Database();
$db = $database->getConnection();
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
    $user->name = $decoded->data->name;
    $user->email = $decoded->data->email;
    $user->user_id = $decoded->data->id;

	
	$query = "SELECT * FROM tbl_user where user_id = {$user->user_id}";
	
	$stmt = $db->prepare($query);
	
	$stmt->execute();
	$xml = new DomDocument("1.0");
	$xml->formatOutput = true;
	$users = $xml->createElement("users");
	$xml->appendChild($users);
	
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$user_field = $xml->createElement("user");
		$users->appendChild($user_field);
	
		$user_id = $xml->createElement("user_id", $row["user_id"]);
		$user_field->appendChild($user_id);
	
		$name = $xml->createElement("name", $row["name"]);
		$user_field->appendChild($name);
	
		$email = $xml->createElement("email", $row["email"]);
		$user_field->appendChild($email);
	
		$username = $xml->createElement("username", $row["username"]);
		$user_field->appendChild($username);
	}
	$filename = "backup-" .  date("Y-m-d-H-i-s") . ".xml";
	echo "<xml>" . $xml->saveXML() . "xml";
	
	$xml->save($filename) or die("Error");
	
	
	$mail = new PHPMailer();
	
	$mail->isSMTP();
	
	$mail->SMTPDebug = SMTP::DEBUG_SERVER;
	
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	
	$mail->SMTPAuth = true;
	
	$mail->Username = 'proyekmenpro@gmail.com';
	
	$mail->Password = 'menpro123';
	
	$mail->setFrom('asumihe14@gmail.com.com', 'Recipe App');
	
	$mail->addReplyTo('laurentzgo@gmail.com', 'Recipe App');

	$mail->addAddress($user->email, 'Andres Sumihe');
	
	$mail->Subject = 'AutoBackup From Server';
	
	$mail->Body = "<h1>Here Your Backup!</h1>";
	
	$mail->AltBody = 'Backup';
	
	$mail->addAttachment('./'.$filename);
	
	if (!$mail->send()) {
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Message sent!';
		http_response_code(200);
		unlink($filename);
		// if (save_mail($mail)) {
		// 	echo "Message saved!";
		// }
	}
	function save_mail($mail)
	{
		$path = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';
	
		$imapStream = imap_open($path, $mail->Username, $mail->Password);
	
		$result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
		imap_close($imapStream);
	
		return $result;
	}
}
