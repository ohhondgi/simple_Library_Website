<?php
    $tns = "
        (DESCRIPTION=
            (ADDRESS_LIST = (ADDRESS=(PROTOCOL=TCP)
            (HOST=10.211.55.3)(PORT=1521)))
            (CONNECT_DATA= (SERVICE_NAME=XE))
        )
    ";
    $dsn = "oci:dbname=".$tns.";charset=utf8";
    $username = 'C##TERMPROJECT';
    $password = '11191001';
    $searchWord = $_GET['searchWord'] ?? '';
    try{
        $conn = new PDO($dsn, $username, $password);
    } catch (PDOException $e){
        echo("에러 내용: ").$e -> getMessage();
    }
    $bookName = '';
    $author = '';
    $publisher = '';
    $year = '';
    $RentState = '';
?>