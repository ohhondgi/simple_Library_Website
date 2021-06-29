<!-- 왼쪽 사이드바 구현을 위한 코드 -->
<div class="border-end bg-white" id="sidebar-wrapper">
    <div class="list-group list-group-flush">
        <img class="list-group-item list-group-item-action list-group-item-light p-1" height="55px" src="image/logo.jpeg">
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="index.php">booksearch</a>
        <div class= "list-group-item list-group-item-action list-group-item-light p-3">
            <!-- dropdown을 이용하여 마이페이지를 구현 -->
            <div class="dropdown">
            <a href="" style="color:gray" class="dropdown-toggle" data-toggle="dropdown">
                My Page</a>
                <!-- 로그인되지 않은 경우에는 마이페이지 세부사항을 보여주지 않음. -->
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])){?>
                <ul class="dropdown-menu">
                    <!-- 마이페이지를 누를 경우 대출현황과 예약 현황 페이지를 나타내는 링크 나타내기-->
                    <li><a class = "dropdown-item" href="../Rent.php">대출 현황</a></li>
                    <li><a class = "dropdown-item" href="../Reserve.php">예약 현황</a></li>
                </ul>
                <?php }?>
            </div>
        </div>
    </div>
</div>
