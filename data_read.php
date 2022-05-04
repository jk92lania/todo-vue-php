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

// 클라이언트 문자 셋 설정
mysqli_set_charset($conn, "utf-8");

$now = $_GET["page_now"];
$count = $_GET["data_count"];
$start = $count * ($now - 1);

// 읽어오는 sql 구문
$sql = "SELECT * FROM todo ORDER BY id DESC LIMIT $start, $count";

// query 실행
$res = mysqli_query($conn, $sql);
$result = array();
// 받은 결과는 배열의 형태
// 배열의 인덱스를 하나식 접근하여서 그 결과를 바탕으로
// 최종 json_encode로 echo

while($row = mysqli_fetch_array($res)) {
    // 배열에다가 저장, 하나씩 데이터를 뽑아서 push
    array_push($result, array('id' => $row[0], 'title' => $row[1], 'complete' => $row[2], 'body' => $row[3] ));
}
echo json_encode(array("result" => $result), JSON_UNESCAPED_UNICODE );

mysqli_close($conn);


?>