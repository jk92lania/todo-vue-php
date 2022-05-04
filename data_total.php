<?php
// 누구나 접속 가능, 보안 x
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$host = 'localhost';
$user = 'paragon';
$pw = 'dot81319359!';
$db = 'paragon';
$conn = mysqli_connect($host, $user, $pw, $db);

// 전체 갯수 파악
$sql = "SELECT * FROM todo";
$result = mysqli_query($conn, $sql);
$total = mysqli_num_rows($result);

echo json_encode(["total" => $total]);

mysqli_close($conn);


?>