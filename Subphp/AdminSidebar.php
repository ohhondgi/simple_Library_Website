<div class="border-end bg-white" id="sidebar-wrapper">
    <div class="list-group list-group-flush">
        <img class="list-group-item list-group-item-action list-group-item-light p-1" height="55px" src="image/logo.jpeg">
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="PreRental.php">PreviousRental bookList</a>
        <div class= "list-group-item list-group-item-action list-group-item-light p-3">
            <!-- dropdown을 이용하여 통계를 구현 -->
            <div class="dropdown">
            <a href="" style="color:gray" class="dropdown-toggle" data-toggle="dropdown">
                Statistics</a>
                <!-- 로그인되지 않은 경우에는 마이페이지 세부사항을 보여주지 않음. -->
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])){?>
                <ul class="dropdown-menu">
                    <!-- 마이페이지를 누를 경우 대출현황과 예약 현황 페이지를 나타내는 링크 나타내기-->
                    <li><a class = "dropdown-item" href="CustomerInfo.php">대출한 책들의 회원 정보 list 구하기</a></li>
                    <li><a class = "dropdown-item" href="PRcountall.php">책을 빌린 사람 수의 총합 구하기</a></li>
                    <li><a class = "dropdown-item" href="PRcount.php">빌린 책들의 총 횟수 구하기</a></li>
                </ul>
                <?php }?>
            </div>
        </div>
    </div>
</div>
