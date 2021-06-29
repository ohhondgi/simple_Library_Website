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
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>유저 id</th>
                                <th>이름</th>
                                <th>책 id</th>
                                <th>책 이름</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // 반납된 도서를 가져오기 위한 db 접근
                            $i=0;
                            $BookQuery = 
                            "SELECT c.cno, c.name, p.e_isbn, e.title
                            FROM ebook e, PREVIOUSRENTAL p join customer c
                            on p.c_cno = c.cno
                            WHERE p.e_isbn = e.isbn and LOWER(cno) 
                            LIKE '%' || :searchWord || '%' ORDER BY cno";
                            $bookInfo = $conn -> prepare($BookQuery);
                            $bookInfo -> execute(array($searchWord));
                            while ($row = $bookInfo -> fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <!-- 유저 id  -->
                            <td><?= $row['CNO']?></td>
                            <!-- 유저 이름 -->
                            <td><?= $row['NAME']?></td>
                            <!-- 책 id -->
                            <td><?= $row['E_ISBN']?></td>
                            <!-- 책 이름 -->
                            <td><?= $row['TITLE']?></td>
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

