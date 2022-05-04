<?php
    $host = 'localhost';
    $user = 'paragon';
    $pw = 'dot81319359!';
    $db = 'paragon';

    $conn = mysqli_connect($host, $user, $pw, $db);
    
    // db 접속 성공 여부에 대한 결과 출력
    if(!$conn) {
        die('ERROR');
    } else {
    }

?>