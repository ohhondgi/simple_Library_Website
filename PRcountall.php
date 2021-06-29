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
                <div class="container-fluid">
                <br>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>책 id</th>
                                <th>유저 id</th>
                                <th>합계</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // 반납된 도서를 가져오기 위한 db 접근
                            $i=0;
                            $BookQuery = 
                            "SELECT p.e_isbn, p.c_cno, count(*) as total
                            from previousrental p
                            group by rollup (p.e_isbn, p.c_cno)
                            order by p.e_isbn";
                            $bookInfo = $conn -> prepare($BookQuery);
                            $bookInfo -> execute();
                            while ($row = $bookInfo -> fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <!-- 유저 id  -->
                            <td><?= $row['E_ISBN']?></td>
                            <!-- 유저 이름 -->
                            <td><?= $row['C_CNO']?></td>
                            <!-- 책 id -->
                            <td><?= $row['TOTAL']?></td>
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

