<?php
    include "TCP.php";
    // user의 id와 pw를 비교하기 위해 DB 연동
    $UserQuery = "SELECT CNO, NAME, PASSED, EMAIL
    FROM CUSTOMER
    WHERE LOWER(CNO)
    LIKE '%' || :searchWord || '%' ORDER BY CNO";
    $userInfo = $conn -> prepare($UserQuery);
    $userInfo -> execute(array($searchWord));

    // id와 pw가 전달되지 않은 상태에서 접근한 경우
    if ( !isset($_POST['user_id']) || !isset($_POST['user_pw']) ) {
        header("Content-Type: text/html; charset=UTF-8");
        echo "<script>alert('아이디 또는 비밀번호가 빠졌거나 잘못된 접근입니다.');";
        echo "window.location.replace('login.php');</script>";
        exit;
    }
    //post데이터를 변수에 저장
    $user_id = $_POST['user_id'];
    $user_pw = $_POST['user_pw'];
    // 연동한 DB의 CNO와 passward를 비교
    while($userRow = $userInfo -> fetch(PDO::FETCH_ASSOC)){
        if( $userRow['CNO'] == $user_id && $userRow['PASSED'] == $user_pw ) {
            /* If success */
            session_start();
            // 세션에 cno와 이름을 저장
            $_SESSION['user_id'] = $user_id;
            $_SESSION['today'] = '2021/04/19';
            $username = $_SESSION['user_name'] = $userRow['NAME'];
            if ($_SESSION['user_id'] == 0){
                echo "<script>alert('관리자 $username 님 환영합니다.');</script>";
                ?> <meta http-equiv="refresh" content="0;url=../PreRental.php" /> <?php
            }else{
                echo "<script>alert('$username 님 환영합니다.');</script>";
                ?><meta http-equiv="refresh" content="0;url=../index.php" /> <?php
            }
            break;
        }
    }
    // CNO와 passwd가 일치하지 않는 경우
    if ($userRow == false){
        header("Content-Type: text/html; charset=UTF-8");
        echo "<script>alert('아이디 또는 비밀번호가 잘못되었습니다.');";
        echo "window.location.replace('login.php');</script>";
        exit;
    }
?>
