<?php
include "login/TCP.php";
session_start();
// 예약 횟수 count를 위한 데이터 연동
$user_id = $_SESSION['user_id'];
$count=0;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "Subphp/head.php" ?>
        <title>E-book Library</title>
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <?php include "Subphp/sidebar.php" ?>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                <?php include "Subphp/TogNavigation.php" ?>
                <br>
                <div class="container-fluid">
                    <table class="table table-bordered text-center">
                        <!-- 마이 페이지에서 유저 정보 테이블 -->
                        <tbody>
                            <tr>
                                <th>이름</th>
                                <th><?= $uname?></th>
                            </tr>
                            <tr>
                                <th>예약 권수</th>
                                <th>
                                <!-- 예약 권수를 나타내기 위한 DB 연동 -->
                                <?php
                                $reserveQuery = 
                                "SELECT * FROM RESERVE WHERE LOWER(C_CNO)
                                LIKE '%' || :searchWord || '%' ORDER BY C_CNO";
                                $reserveInfo = $conn -> prepare($reserveQuery);
                                $reserveInfo -> execute(array($searchWord));
                                while($row = $reserveInfo -> fetch(PDO::FETCH_ASSOC)){
                                    if ($user_id == $row['C_CNO']){
                                        $count++;
                                    }
                                }
                                 echo $count
                                ?>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                    <br> <br>
                    <table class="table table-bordered text-center">
                        <!-- 책 세부사항 -->
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th>책 세부사항</th>
                                <th>예약 취소</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=0;
                            if ($count >0){
                                // 예약 book 정보를 위한 데이터 연동
                                $findbookInfoQ =
                                "SELECT TITLE,PUBLISHER,YEAR,EXTTIMES,ISBN FROM RESERVE r, EBOOK e 
                                WHERE r.E_ISBN = e.ISBN and r.C_CNO = $user_id
                                and LOWER(TITLE) LIKE '%' || :searchWord || '%' ORDER BY TITLE";
                                $findbookInfo = $conn -> prepare($findbookInfoQ);
                                $findbookInfo -> execute(array($searchWord));
                                // extend가 null 인 경우 대출하지 않은 책
                                while ($row = $findbookInfo -> fetch(PDO::FETCH_ASSOC)){
                                    $Extend = $row['EXTTIMES'];
                                    if ($Extend == null){
                                        $RentState = '대출 가능';
                                    }else{
                                        $RentState = '대출 중';
                                    }
                            ?>
                                <tr>
                                    <!-- 예약한 책을 나타내는 테이블 -->
                                    <td><?=$i?></td>
                                    <td><a href="bookview.php?ISBN=<?=$row['ISBN']?>&RentState=<?= $RentState?>">
                                    <?= $row['TITLE']."/".$row['PUBLISHER']."/".$row['YEAR']?></td>
                                    <td>
                                        <!-- 예약 취소 버튼 -->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ReserveCancelConfirmModal">예약 취소</button>
                                        <?php
                                        $bookId = $row['ISBN'];
                                        $bookName = $row['TITLE'];
                                        $_POST['button'] = "ReserveCancelConfirmModal";
                                        $_POST['option'] = "ReserveCancel";
                                        include "alterFade.php";
                                        ?>
                                    </td>
                                    <?php $i++;}} ?>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
                        
                    