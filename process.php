<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
if (!isset($_SESSION['user_id'])){
    echo "<script>alert('로그인해 주시기 바랍니다.');";
    echo "window.location.replace('index.php');</script>";
    exit;
}
include "login/TCP.php";
include "Subphp/head.php";
$bookId = $_GET['isbn'] ?? '';
$todaytime = date("Y/m/d");
$state = $_GET['state'] ?? '';
$Extend = $_GET['extend'] ?? '';
switch($_GET['mode']){
    case 'Reserve':
        // 예약을 위해 회원의 예약 상태를 위해 reserve 테이블 접근
            $reserveQuery = 
            "SELECT * FROM RESERVE WHERE LOWER(C_CNO)
            LIKE '%' || :searchWord || '%' ORDER BY C_CNO";
            $reserveInfo = $conn -> prepare($reserveQuery);
            $reserveInfo -> execute(array($searchWord));
            $count=0;
            // reserve 테이블에서 회원 id와 일치하는 책에 대한 카운팅
                while($row = $reserveInfo -> fetch(PDO::FETCH_ASSOC)){
            if ($_SESSION['user_id']== $row['C_CNO']){
                $count++;
            }
        }
        // 예약 가능한 도서의 최대 개수를 넘은 경우
        if ($count > 2){
            echo "<script>alert('예약가능한 도서는 최대 3권 입니다.');";
        } else{
            // 쿼리문을 이용하여 reserve 테이블에 insert
            $ReserveQuery = "INSERT INTO RESERVE (E_ISBN,C_CNO,DATETIME)
            VALUES (:bookId, :cno, :todayTime)";
            $ReserveInsert = $conn -> prepare($ReserveQuery);
            $ReserveInsert -> bindParam(':bookId',$bookId);
            $ReserveInsert -> bindParam(':cno',$_SESSION['user_id']);
            $ReserveInsert -> bindParam(':todayTime', $todaytime);
            try{
                $ReserveInsert -> execute();
            } catch (PDOException $e){  // 이미 예약한 경우 FK 충돌
                echo "<script>alert('이미 예약되어있습니다.');";
                echo "window.location.replace('index.php');</script>";
            }
            echo "<script>alert('예약이 완료되었습니다.');";
        }
        echo "window.location.replace('index.php');</script>";
        break;
    case 'Rent':
        // 회원의 대출 권수에 대한 카운팅을 위한 Ebook 테이블 접근
        $RentQuery =
        "SELECT * FROM Ebook
        WHERE LOWER(C_CNO) LIKE '%' || :searchWord || '%' ORDER BY C_CNO";
        $RentInfo = $conn -> prepare($RentQuery);
        $RentInfo -> execute(array($searchWord));
        $count = 0;
        // 회원 id와 일치하는 책들을 counting
        while($row = $RentInfo -> fetch(PDO::FETCH_ASSOC)){
            if ($_SESSION['user_id'] == $row['C_CNO'])
                $count++;
        }
        // 대출중인 책의 경우
        if ($state == "대출 중"){
             ?>
            <script>
                // 대출중이라는 알림과 함께 예약 여부를 묻고, 예약할 경우 process.php를 호출
                if(confirm('이미 대출중인 도서입니다. 예약하시겠습니까?')) {
                    window.location.replace('process.php?mode=Reserve&isbn=<?=$bookId?>&state=<?= $RentState?>&extend=<?= $Extend?>');
                }
                else{
                    window.location.replace('index.php');
                }
            </script>
            <?php
        // 대출중인 책의 권수가 3권 이상인 경우
        } else if ($count > 2){
            echo "<script>alert('대출가능한 도서는 최대 3권 입니다.');";
            echo "window.location.replace('index.php');</script>";
        } else {
            // ebook에 책에 대한 업데이트를 통해 책을 대출
            $RentQuery =
                "UPDATE EBOOK SET C_CNO = :cno, EXTTIMES = 0,
                DATERENTED = :extend, DATEDUE = :due WHERE ISBN = :isbn";
            $RentInsert = $conn -> prepare($RentQuery);
            $RentInsert -> bindParam(':cno', $_SESSION['user_id']);
            $RentInsert -> bindParam(':extend',$todaytime);
            $duetime = date("Y/m/d",strtotime($todaytime.'+10 days'));
            $RentInsert -> bindParam(':due',$duetime);
            $RentInsert -> bindParam(':isbn', $bookId);
            try{
                $RentInsert -> execute();
            } catch (PDOException $e){  
                echo "<script>alert('잘못된 접근입니다.');";
                echo "window.location.replace('index.php');</script>";
            }
            echo "<script>alert('대출이 완료되었습니다.');";
            echo "window.location.replace('index.php');</script>";
        }
        break;
    case 'ReserveCancel':
        // 쿼리문을 사용하여 유저 id와 책 id에 해당하는 데이터를 삭제
        $ReserveCancelQuery =
        "DELETE FROM RESERVE
        WHERE C_CNO = :cno and E_ISBN = :isbn";
        $ReserveCancel = $conn -> prepare($ReserveCancelQuery);
        $ReserveCancel -> bindParam(':cno', $_SESSION['user_id']);
        $ReserveCancel -> bindParam(':isbn',$bookId);
        $ReserveCancel -> execute();
        echo "<script>alert('예약 취소가 완료되었습니다.');";
        echo "window.location.replace('index.php');</script>";
        break;
    case 'Extend':
        // 연장 횟수가 2회 이상인 경우
        if ($Extend > 1){
            echo "<script>alert('연장 횟수는 최대 2회 입니다.');";
            echo "window.location.replace('index.php');</script>";
        }
        // 쿼리문을 사용하여 datedue의 기간을 10일 연장
        else{
            $checkReQ = 
            "SELECT C_CNO
            FROM RESERVE
            WHERE C_CNO = :cno";
            $checkRe = $conn -> prepare($checkReQ);
            $checkRe -> bindParam(':cno', $_SESSION['user_id']);
            $checkRe -> execute();
            if ($checkRe -> fetch(PDO::FETCH_ASSOC)){
                echo "<script>alert('예약된 사람이 있어 연장이 불가능합니다.');";
                echo "window.location.replace('index.php');</script>";
                break;
            }
            $ExtendQuery =
                "UPDATE EBOOK SET EXTTIMES = :extend, DATEDUE = DATEDUE + (INTERVAL '10' DAY)
                WHERE C_CNO = :cno and ISBN = :isbn" ;
            $Extend++;
            $ExtendInsert = $conn -> prepare($ExtendQuery);
            $ExtendInsert -> bindParam(':extend', $Extend);
            $ExtendInsert -> bindParam(':cno', $_SESSION['user_id']);
            $ExtendInsert -> bindParam(':isbn', $bookId);
            $ExtendInsert -> execute();
            echo "<script>alert('대출 연장이 완료되었습니다.');";
            echo "window.location.replace('index.php');</script>";
        }
        break;
    case 'Return':
        // 해당 책 id와 유저 id와 일치하는 정보들을 가져오기 위해 ebook에 접근
        $returnInsertPRQuery =
            "SELECT ISBN, DATERENTED, DATEDUE, C_CNO
            FROM EBOOK
            WHERE C_CNO = :cno and ISBN = :isbn";
        $returnInsertPR = $conn -> prepare($returnInsertPRQuery);
        $returnInsertPR -> bindParam(':cno', $_SESSION['user_id']);
        $returnInsertPR -> bindParam(':isbn', $bookId);
        $returnInsertPR -> execute();

        // PreviousRental table에 넣기 위한 query
        $InsertPRQuery = 
            "INSERT INTO PREVIOUSRENTAL (E_ISBN, PR_DATERENTED, DATERETURNED,C_CNO)
            VALUES (:isbn, :rented, :returned, :cno)";
        $InsertPR = $conn -> prepare($InsertPRQuery);
        if ($row = $returnInsertPR -> fetch(PDO::FETCH_ASSOC)){
            $InsertPR -> bindParam(':isbn', $row['ISBN']);
            $InsertPR -> bindParam(':rented', $row['DATERENTED']);
            $InsertPR -> bindParam(':returned', $row['DATEDUE']);
            $InsertPR -> bindParam(':cno', $row['C_CNO']);
            $InsertPR -> execute();
        }
        // 반납하였기 때문에 쿼리문을 사용하여 ebook을 업데이트
        $returnQuery = 
        "UPDATE EBOOK SET C_CNO = null, EXTTIMES = null,
        DATERENTED = null, DATEDUE = null
        WHERE C_CNO = :cno and ISBN = :isbn";
        $return = $conn -> prepare($returnQuery);
        $return -> bindParam(':cno', $_SESSION['user_id']);
        $return -> bindParam(':isbn', $bookId);
        $return -> execute();
        
        // 예약한 사람을 찾기 위해 reserve 테이블 접근
        $searchQ = 
        "SELECT count(e_isbn) RC
        FROM reserve
        WHERE e_isbn = :isbn
        group by e_isbn";
        $stmt = $conn -> prepare($searchQ);
        $stmt -> execute(array($bookId));
        $countRP='';
        // 예약한 사람이 있을 경우 count
        if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
            $countRP = $row['RC'];
        }
        if ($countRP){
            echo "<script>window.location.replace('process.php?mode=mail&isbn=$bookId&count=$countRP');</script>";
        } else{
            echo "<script>alert('반납되었습니다.');";
            echo "window.location.replace('index.php');</script>";
        }
        break;
    case 'mail':
        //  반납한 책이 누군가에게 예약 되어있는 경우에만 여기로 진입하게됨
        $ISBN = $_GET['isbn'];

        //  누가 예약했는지의 정보를 얻기 위해 reserve 테이블 접근
        $searchQ = 'SELECT C_CNO
                        FROM RESERVE
                        WHERE E_ISBN = :isbn
                        ORDER BY DATETIME ASC';
        $stmt = $conn->prepare($searchQ);
        $stmt->bindParam(':isbn', $bookId);
        $stmt->execute();
        $reservedPerson = '';
        // 예약 날짜가 가장 오래된 사람을 fetch
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {    
            $reservedPerson = $row['C_CNO'];
        }

        require 'vendor/autoload.php';
        // mail을 보내기 위한 mailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = '5ghdrl@gmail.com';                     // SMTP username
            $mail->Password = 'admini2347!';                          // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('5ghdrl@gmail.com', 'PHP Mailer(SHP)');
            $mail->addAddress('ohhonggi19@gmail.com', 'HongGi Oh');     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Notice: Your reserved book has been returned!';
            $mail->Body = '예약하신 도서가 반납되었습니다. 다음날까지 대출해주시기 바랍니다.';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();

            //  메일링 후에 해당 예약 삭제처리
            $searchQuery = 'DELETE FROM RESERVE 
            WHERE E_ISBN = :isbn AND C_CNO = :cno';
            $stmt = $conn->prepare($searchQuery);
            $stmt->bindParam(':isbn', $bookId);
            $stmt->bindParam(':cno', $reservedPerson);
            $stmt->execute();

            echo "<script>alert('도서가 반납되었으며 예약대기자에게 메일 전송이 완료되었습니다.');";
            echo "window.location.replace('index.php');</script>";

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            echo "<script>alert('메일 전송이 실패하였습니다.');";
            echo "window.location.replace('index.php');</script>";
        }

        break;
}
// echo ("<meta http-equiv='refresh' ; url=TCP.php");
?>
