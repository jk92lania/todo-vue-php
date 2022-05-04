<?php
 $host = 'localhost';
 $user = 'paragon';
 $pw = 'dot81319359!';
 $db = 'paragon';
 $conn = mysqli_connect($host, $user, $pw, $db);

 if(!$conn) {
    die('ERROR');
} else {
    
 // table 생성
 $sql = "CREATE TABLE todo (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TITLE VARCHAR(30) NOT NULL,
    COMPLETE BOOLEAN NOT NULL DEFAULT 0,
    BODY TEXT NOT NULL
)";

// 실행
 if(mysqli_query($conn, $sql)) {
     echo 'table success';
     mysqli_query($conn, 'desc todo');

 } else {
     echo 'table fail';
 }
}


  // db 작업 끝나면 db 연결 종료
  mysqli_close($conn);
?>