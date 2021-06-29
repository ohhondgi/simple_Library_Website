<!-- http://parkjuwan.dothome.co.kr/wordpress/2017/07/11/php-session-login/ -->
<!-- https://scribblinganything.tistory.com/47 -->
<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Online Library System</title>
        <link rel="stylesheet" href="../css/bootstrap.css">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" type="text/javascript"></script>
    </head>

    <link href="../css/login.css" rel="stylesheet">

    <body class="text-center" >
        <main class = "form-signin">
            <h1 class="h3 mb-3 fw-normal">LOG in to Libaray</h1>
            <!-- id와 pw를 입력하지 않은 경우 -->
            <?php if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) { ?>
                <!-- id와 pw를 입력하여 DB의 존재하는 아이디인지 검사 -->
            <form method= "post" action="login_ok.php">
                <input type="text" name="user_id" class="form-control" placeholder="CNO" required autofocus> </p>
                <input type="password" name="user_pw" class="form-control" placeholder="Password" required></p>
                <input class="w-100 btn btn-lg btn-primary" type="submit" value="Sign in" />
            </form>
            <br>
            <img width="100%" height="100%" rel="stylesheet" 
            style="border: 1px solid white;"src="../image/logo.jpeg" alt="Logo">
            <?php } else {
                // 메인 페이지로 이동
                $user_id = $_SESSION['user_id'];
                $user_name = $_SESSION['user_name'];
                // 중간에 갑자기 login 화면으로 올 경우
                echo "<p><strong>$user_name</strong>님은 이미 로그인하고 있습니다.</p>";
                echo "<p><a href=\"../index.php\">[돌아가기]</a> ";
                echo "<a href=\"logout.php\">[로그아웃]</a></p>";
            } ?>
        </main>

    </body>        
</html>