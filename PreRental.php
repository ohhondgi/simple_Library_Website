<?php
include "login/TCP.php";
session_start();
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
            <?php include "Subphp/AdminSidebar.php" ?>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                <?php include "Subphp/TogNavigation.php" ?>
                <!-- book list -->
                <div class="container-fluid">
                    <br>
                    <!-- 검색어 입력 공간 -->
                    <form class="row">
                        <div class="col-10">
                            <input type="text" class="form-control" id="searchWord" 
                            name="searchWord" placeholder="검색어 입력" value="<?= $searchWord ?>">
                        </div>
                        <!-- 검색 버튼 -->
                        <div class="col-auto text-end">
                            <button type="submit" class="btn btn-primary mb-3">검색</button>
                        </div>
                    </form>
                    <!-- 반납된 booklist를 나타내는 테이블 -->
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>book id</th>
                                <th>빌렸던 시각</th>
                                <th>반납한 시각</th>
                                <th>빌린 회원 id</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // 반납된 도서를 가져오기 위한 db 접근
                            $i=0;
                            $BookQuery = 
                            "SELECT *
                            FROM PREVIOUSRENTAL
                            WHERE LOWER(E_ISBN) 
                            LIKE '%' || :searchWord || '%' ORDER BY E_ISBN";
                            $bookInfo = $conn -> prepare($BookQuery);
                            $bookInfo -> execute(array($searchWord));
                            while ($row = $bookInfo -> fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <!-- 책 id  -->
                            <td><?= $row['E_ISBN']?></td>
                            <!-- 대출했던 날짜 -->
                            <td><?= $row['PR_DATERENTED']?></td>
                            <!-- 반납했던 날짜 -->
                            <td><?= $row['DATERETURNED']?></td>
                            <!-- 대출한 회원 id -->
                            <td><?= $row['C_CNO']?></td>
                        </tr>
                        <!-- row를 탐색하면서 전체 booklist를 출력 -->
                        <?php $i++;} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
