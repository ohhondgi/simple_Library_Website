<?php
include "login/TCP.php";
session_start();
// 자동반납
// 대출 기록에 삽입
// $insert= $conn->prepare('INSERT INTO PREVIOUSRENTAL
//                             SELECT ISBN, DATERENTED, DATEDUE, CNO
//                             FROM EBOOK
//                             WHERE CNO IS NOT NULL
//                             AND DATEDUE < SYSDATE');
// $insert->execute();


// $selfrtq = 
// "SELECT *
// FROM EBOOK
// where C_CNO is not null
// and DATEDUE < :today";
// $selfrt = $conn -> prepare($selfrtq);
// $selfrt -> bindParam(':today', $_SESSION['today']);
// $selfrt -> execute();
// while ($row = $selfrt->fetch(PDO::FETCH_ASSOC)) { //  $row['ISBN']
//     $ISBN = $row['ISBN'];
//     $searchQuery = 'SELECT COUNT(E_ISBN) RC
//                         FROM RESERVE
//                         WHERE E_ISBN = :isbn
//                         GROUP BY ISBN';
//     $stmt = $conn->prepare($searchQuery);
//     $stmt->execute(array($ISBN));
//     $countReservingPersons = '';
//     if ($row = $conn-> fetch(PDO::FETCH_ASSOC)) {    //  각 변수에 쿼리로부터 얻은 정보 할당
//         $countReservingPersons = $row['RESERVEDCOUNT'];
//     }

//     if($countReservingPersons){ //  예약되어있으면 process.php에 mode=mail 로 정보를 넘김
//         echo "<script>window.location.replace('process.php?mode=mail&ISBN=$ISBN&count=$countReservingPersons');</script>";
//     }

    
    // $searchQuery = 'SELECT COUNT(ISBN) RESERVEDCOUNT
    // FROM RESERVE
    // WHERE ISBN = :isbn
    // GROUP BY ISBN';
    // if ($row = $selfrt -> fetch(PDO::FETCH_ASSOC)){
    //     $te = $row['ISBN'];
    //     echo "<script> window.location.replace('process.php?mode=Return&isbn=$te');</script>";
    // }
// }
$title = $_GET['Title'] ?? '';
$Tstate = $_GET['TR'] ?? '';
$publish = $_GET['publisher'] ?? '';
$Pstate = $_GET['PR'] ?? '';
$year = $_GET['year'] ?? '';
$Ystate = $_GET['YR'] ?? '';
$author = $_GET['author'] ?? '';
$Astate = $_GET['AR'] ?? '';
?>
<!DOCTYPE html>
<html lang="ko">
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
                <!-- book list -->
                <div class="container-fluid">
                    <br>
                    <!-- 검색어 입력 공간 -->
                    <form class="row">
                        <div class="col-10">
                            <label for="searchWord" class="visually-hidden">Search Word</label>
                            <!-- <input type="text" class="form-control" id="searchWord"
                            name="searchWord" placeholder="검색어 입력" value="<?= $searchWord ?>"> -->
                            <table class="table table-bordered text-center"> 
                                <!-- 상세검색 테이블-->
                                <thead>
                                    <tr>
                                        <p><td colspan = "3" span>상세 검색</td></p>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!-- 도서명의 대한 정보를 받는 input 태그 -->
                                        <td>도서명</td>
                                        <td>
                                            <input type="text" class="form-control" id="Title"
                                            name="Title" placeholder="검색어 입력" value="<?= $title ?>">
                                        </td>
                                        <!-- 도서명에 대한 and or not -->
                                        <td>
                                            <p><input type="radio" name="TR" id = "TAnd" value="AND" checked>And
                                            <input type="radio" name="TR" id = "Tor" value="OR">or
                                            <input type="radio" name="TR" id = "Tnot" value="NOT">not
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                    <!-- 출판사의 대한 정보를 받는 input 태그 -->
                                        <td>출판사</td>
                                        <td>
                                            <input type="text" class="form-control" id="publisher"
                                            name="publisher" placeholder="검색어 입력" value="<?= $publish ?>">
                                        </td>
                                        <!-- 출판사에 대한 and or not -->
                                        <td>
                                            <p><input type="radio" name="PR" value="AND" checked>And
                                            <input type="radio" name="PR" value="OR">or
                                            <input type="radio" name="PR"  value="NOT">not
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                    <!-- 저자의 대한 정보를 받는 input 태그 -->
                                        <td>저자</td>
                                        <td>
                                            <input type="text" class="form-control" id="author"
                                            name="author" placeholder="검색어 입력" value="<?= $author ?>">
                                        </td>
                                        <!-- 저자에 대한 and or not -->
                                        <td>
                                            <p><input type="radio" name="AR" value="AND" checked>And
                                            <input type="radio" name="AR" value="OR">or
                                            <input type="radio" name="AR" value="NOT">not
                                            </p>
                                        </td>
                                    </tr>
                                    <!-- 발행년도에 대한 정보를 받는 input 태그 -->
                                    <tr>
                                        <td>발행년도</td>
                                        <td>
                                            <input type="text" class="form-control" id="year"
                                            name="year" placeholder="검색어 입력" value="<?= $year ?>">
                                        </td>
                                        <!-- 발행년도에 대한 and or not -->
                                        <td>
                                            <p><input type="radio" name="YR" value="AND" checked>And
                                            <input type="radio" name="YR" value="OR">or
                                            <input type="radio" name="YR" value="NOT">not
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- 검색 버튼 -->
                        <div class="col-auto text-end ">
                            <button type="submit" id = "Allsearch" class="btn btn-primary mb-3">검색</button>
                        </div>
                    </form >
                    <!-- booklist를 나타내는 테이블 -->
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>책 번호</th>
                                <th>도서명</th>
                                <th>저자</th>
                                <th>출판사</th>
                                <th>출판년도</th>
                                <th>대출 상태</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        //booklist를 가져오기 위한 db 접근
                            $i=0;
                            if ($Tstate == "AND" && $Pstate == "AND" && $Ystate == "AND"){
                            }
                                $BookQuery =
                                "SELECT e.TITLE, e.PUBLISHER, e.YEAR, e.ISBN, e.EXTTIMES, a.AUTHOR
                                FROM EBOOK e, AUTHORS a
                                WHERE e.ISBN = a.E_ISBN
                                and (LOWER(TITLE) 
                                LIKE '%' || :title || '%'
                                and LOWER(PUBLISHER) 
                                LIKE '%' || :publish || '%'
                                and LOWER(YEAR)
                                LIKE '%' || :year || '%'
                                and LOWER(AUTHOR)
                                LIKE '%' || :author || '%')
                                ORDER BY TITLE";
                                // $BookQuery = "SELECT e.TITLE,"
                            $bookInfo = $conn -> prepare($BookQuery);
                            $bookInfo -> bindParam(':title', $title);
                            $bookInfo -> bindParam(':publish', $publish);
                            $bookInfo -> bindParam(':year', $year);
                            $bookInfo -> bindParam(':author', $author);
                            $bookInfo -> execute();
                            $all = $bookInfo -> fetchAll();
                            $bookInfo -> execute();
                            while ($row = $bookInfo -> fetch(PDO::FETCH_ASSOC)){
                                $Extend = $row['EXTTIMES'];
                                $CbookName = $row['TITLE'];
                                $Cisbn = $row['ISBN'];
                                $Cauthor = $row['AUTHOR'];
                                $Cpublish = $row['PUBLISHER'];
                                $Cyear = $row['YEAR'];
                                if ($Extend == null){
                                    $RentState = '대출 가능';
                                }else{
                                    $RentState = '대출 중';
                                }
                        ?>
                        <tr>
                            <!-- 책 번호 -->
                            <td><?= $Cisbn?></td>
                            <!-- 책의 간단한 속성 -->
                            <td><a href="bookview.php?ISBN=<?=$Cisbn?>&RentState=<?= $RentState?>&extend=<?= $Extend?>">
                            <?=$CbookName?></td>                            
                            <td><?php
                            if ( $i<$bookInfo->rowCount() && ($Cisbn == $all[$i+1]['ISBN'])){
                                echo $Cauthor.",".$all[$i+1]['AUTHOR'];
                                $bookInfo -> fetch(PDO::FETCH_ASSOC);
                                $i++;
                            }else echo $Cauthor;
                            ?></td>
                            <td><?= $Cpublish?></td>
                            <td><?= $Cyear?></td>
                            <!-- 대출 상태 -->
                            <td><?= $RentState?></td>
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