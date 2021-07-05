<?php
// error reporting
error_reporting(E_ALL);
 
date_default_timezone_set('Asia/Jakarta');
 
$key = "YXBpLnJlY2lwZS5jb20";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); 
$issuer = "http://localhost/CodeOfaNinja/RestApiAuthLevel1/";
?>
