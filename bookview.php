<?php
include "login/TCP.php";
$attributename = "";
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include "Subphp/head.php" ?>
        <title>Book View</title>
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <?php include "Subphp/sidebar.php" ?>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                <?php
                include "Subphp/TogNavigation.php";
                // 클릭된 책의 isbn에 대해서 세부사항을 가져오기 위한 DB 접근
                $bookId = $_GET['ISBN'];
                $RentState = $_GET['RentState'];
                $Extend = $_GET['extend'];
                $bookInfoForView = $conn -> prepare(
                    "SELECT *
                    FROM EBOOK
                    WHERE ISBN = ?");
                $bookInfoForView -> execute(array($bookId));
                // DB에서 가져온 책이름, 출판사, 년도를 변수에 저장
                if ($row = $bookInfoForView -> fetch(PDO::FETCH_ASSOC)){
                    $bookName = $row['TITLE'];
                    $publisher = $row['PUBLISHER'];
                    $year = $row['YEAR'];
                ?>
                <div class="container-fluid">
                    <h2 class="display-6">상세 화면</h2>
                    <table class="table table-bordered text-center">
                        <!-- 책 정보를 나타내는 테이블 -->
                        <tbody>
                            <tr>
                                <td>제목</td>
                                <td><?= $bookName ?></td>
                            </tr>
                            <tr>
                                <td>고유 번호</td>
                                <td><?= $bookId ?></td>
                            </tr>
                            <tr>
                                <td>저자</td>
                                <td><?php
                                // 여러명의 저자를 나태내기 위해 DB 접근
                                        $authorQuery = 
                                        "SELECT AUTHOR
                                        FROM AUTHORS
                                        WHERE E_ISBN = ?";
                                        $authorInfo = $conn -> prepare($authorQuery);
                                        $authorInfo -> execute(array($bookId));
                                        if ($row = $authorInfo -> fetch(PDO::FETCH_ASSOC))
                                            echo($row['AUTHOR']);
                                        while ($row = $authorInfo -> fetch(PDO::FETCH_ASSOC)){
                                            echo(", ");
                                            echo($row['AUTHOR']);
                                        }
                                ?></td>
                            </tr>
                            <tr>
                                <td>출판사</td>
                                <td><?= $publisher ?></td>
                            </tr>            
                            <tr>
                                <td>발행년도</td>
                                <td><?= $year ?></td>
                            </tr>
                            <tr>
                                <td>대출 상태</td>
                                <td><?= $RentState?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
        <div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mr-3 m-3" >
                <!-- 목록으로 돌아가는 링크 -->
                <a href="index.php" class="btn btn-success">목록</a>
                <!-- 대출을 위한 버튼 -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                data-bs-target="#RentConfirmModal">대출</button>
                <!-- 예약을 위한 버튼 -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" 
                data-bs-target="#ReserveConfirmModal">예약</button>
            </div>
        </div>
        <?php
        // 버튼을 누를 경우 대출할것인지 묻는 알람
        $_POST['button'] = "RentConfirmModal";
        $_POST['option'] = "Rent";
        include "alterFade.php";
        // 버튼을 누를 경우 예약할것인지 묻는 알람
        $_POST['button'] = "ReserveConfirmModal";
        $_POST['option'] = "Reserve";
        include "alterFade.php";
        ?>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-
    gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</html>
