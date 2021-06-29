
<?php
$buttonId = $_POST['button'];
$option = $_POST['option'];
// 각 버튼들을 누를 경우 메세지를 위한 조건문
if ($option == 'Reserve'){
    $answer = "예약";
}
else if ($option == 'Rent'){
    $answer = "대출";
}
else if ($option == 'ReserveCancel')
    $answer = "취소";
else if ($option == 'Extend')
    $answer = "연장";
else
    $answer = "반납";
?>

<div class="modal fade" id= <?=$buttonId ?> aria-labelledby= <?=$buttonId."Label"?> aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- 책 제목을 나타내는 코드 -->
                <h5 class="modal-title" id=<?=$buttonId."Label"?>><?= "책 제목: ".$bookName?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $answer?>하시겠습니까?
            </div>
            <div class="modal-footer">
                <!-- 확인의 경우 이를 처리하는 process.php 파일로 이동-->
                <form action="process.php?mode=<?=$option?>&isbn=<?=$bookId?>&state=<?= $RentState?>&extend=<?= $Extend?>" method="post" class="row">
                    <input type="hidden" name="bookId" value="<?= $bookId ?>">
                    <button type="submit" class="btn btn-info"><?= $option?></button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">cancel</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-
    gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

