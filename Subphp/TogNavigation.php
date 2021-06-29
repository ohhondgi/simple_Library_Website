<!-- navigation의 오른쪽 상단 로그인 or 로그아웃 및 환영 문장을 나타내는 코드 -->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <button class="btn btn-primary" id="sidebarToggle">Menu</button>
        <div>
            <!-- 로그인하지 않은 경우 -->
            <?php if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])){?>
                <!-- 로그인 버튼 -->
            <button class="btn btn-primary" id="sidebarToggle" onclick="location.href='login/login.php'">로그인</button>
            <?php } else {   
                // 로그인 한 경우 환영 문장과 로그아웃 버튼
                    $uname = $_SESSION['user_name'];
                echo $uname."님 환영합니다.";?>
            <button class="btn btn-primary" id="sidebarToggle" onclick="location.href='login/logout.php'">로그아웃</button> <?php }?>
        </div>
    </div>
</nav>
