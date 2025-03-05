<?php

include('includes/header.php');
include('../middleware/adminMiddleware.php');


if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $messageData = checkMessage($id);
    if (mysqli_num_rows($messageData) < 0) {
        ?>
        <h4>Mã đơn hàng không tồn tại!</h4>
        <?php
        die();
    }

} else {
    ?>
    <h4>Mã đơn hàng không tồn tại!</h4>
    <?php
    die();
}
$data = mysqli_fetch_array($messageData);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <span class="text-white fs-3 fw-bold">Nội dung</span>

                    <a href="message.php" class="btn btn-warning float-end"><i class="fa fa-reply"></i> Trở
                        về</a>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-5">
                            <h5>Thông tin người gửi</h5>
                            <hr>
                            <form action="../function/userhelp.php" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                        <label class="fw-bold">Tên</label>
                                        <input type="hidden" name="name" value="<?= $data['name'] ?>">
                                        <div class="border p-1">
                                            <?= $data['name'] ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="fw-bold">Email</label>
                                        <input type="hidden" name="email" value="<?= $data['email'] ?>">
                                        <div class="border p-1">
                                            <?= $data['email'] ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="fw-bold">Số điện thoại</label>
                                        <div class="border p-1">
                                            <?= $data['phone'] ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="fw-bold">Nội dung</label>
                                        <div class="border p-6">
                                            <?= $data['message'] ?>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-7">
                            <h5>Phản hồi</h5>
                            <hr>

                            <label for="" class="fw-bold">Nội dung phản hồi </label>
                            <textarea name="repply" id="" cols="" rows="10" class=" form-control mt-2"
                                placeholder="Nội dung.."></textarea>
                            <button type="submit" name="repply_message"
                                class=" btn btn-primary form-control fw-bold mt-2 "> Gửi </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>