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


// 전달된 id를 받는다
$id = $_GET['id'];
$title = $_GET['title'];
$body = $_GET['body'];
$complete = $_GET['complete'];



// 변경
$sql = "UPDATE todo SET title = '$title', body = '$body', complete = '$complete' WHERE id = '$id'";

// query 실행
$result = mysqli_query($conn, $sql);

if($result) {
    // 글자를 json 형태로 encode 변형시켜 보낸다
    echo json_encode(["result" => 1]);
} else {
    echo json_encode(["result" => 0]);
    
}

mysqli_close($conn);


?>