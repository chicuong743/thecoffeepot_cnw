<?php
// session_start();
include('function/userfunctions.php');
include('includes/header.php');

include('authenticate.php');



// include('function/handlecart.php') ?>


<div class="py-5">
    <div class="container">
        <div class="card">
            <div class="card-body">


                <div class="row">
                    <div id="message"></div>
                    <div class="col-md-5">
                        <h5>Thông tin tài khoản</h5>
                        <hr>
                        <div class="row">

                            <?php
                            $user_id = $_SESSION['auth_user']['user_id'];
                            $user_data = getByUID('users', $user_id);
                            if (mysqli_num_rows($user_data) > 0) {
                                foreach ($user_data as $item) {
                                    ?>
                                    <form action="function/authcode.php" method="POST">

                                        <div class="col-md-12">
                                            <label class="fw-bold">Tên </label>

                                            <input type="text" required name="name" value="<?= $item['name'] ?> "
                                                class="form-control">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="fw-bold">Email </label>
                                            <div class="border p-1">
                                                <?= $item['email'] ?>
                                            </div>
                                            <input type="hidden" value="<?= $item['email'] ?> " name="email">
                                            <!-- <input type="text" required name="email" value="<?= $item['email'] ?> "
                                                class="form-control"> -->
                                        </div>
                                        <div class="col-md-12">
                                            <label class="fw-bold">Số điện thoại </label>
                                            <input type="text" required name="phone" pattern="0[0-9]{9}"
                                                value="<?= trim($item['phone']) ?>" class="form-control">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="fw-bold">Ngày tạo tài khoản</label>
                                            <div class="border p-1">
                                                <?= date('H:i - d/m/Y', strtotime($item['created_at'])) ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label class="fw-bold">Trạng thái : </label>
                                            <?php if ($item['status'] == 0) { ?>
                                                <span class="badge bg-success text-white">Đang hoạt động</span>
                                            <?php } else if ($item['status'] == 1) { ?>
                                                    <span class="badge bg-success"> Khóa tài khoản</span>
                                            <?php } ?>

                                        </div>
                                        <div class="mt-3 row">

                                            <div class="col-md-5">
                                                <button type="button" class="btn btn-outline-danger w-100 fw-bold"
                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                    Khóa tài khoản
                                                </button>



                                            </div>

                                            <div class="col-md-4">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#openModal"
                                                    class="btn btn-outline-success w-100 fw-bold">Đổi mật khẩu</button>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" name="update_account"
                                                    class="btn btn-outline-info w-100 fw-bold">Cập nhật</button>
                                            </div>
                                    </form>
                                </div>

                                <!-- Modal Khóa tài khoản -->
                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Bạn có chắc
                                                    muốn khóa không?
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Nếu khóa tài khoản. Bạn muốn mở thì hãy liên hệ với quản trị viên.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Thoát</button>
                                                <form action="function/authcode.php" method="POST">
                                                    <input type="hidden" name="id_user" value=" <?= $item['id_user'] ?>">
                                                    <button type="submit" name="disabled_account" value="1"
                                                        class="btn btn-outline-danger w-100 fw-bold">Khóa tài
                                                        khoản</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Update mật khẩu -->
                                <div class="modal fade" id="openModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Thay đổi mật khẩu
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="function/authcode.php" method="POST">
                                                <div class="modal-body">
                                                <div id="message" class="message"></div>
                                                <div class="form-group">
                                                    <input type="hidden" name="email" value="<?= $item['email'] ?>">
                                                    <label for="old_password" class="fw-bold">Mật khẩu cũ</label>
                                                    <div class="input-group">
                                                        <input type="password" required name="old_password" id="old_password" class="form-control">
                                                        <div class="input-group-append">
                                                            <button type="button" id="showOldPasswordToggle" class="btn btn-outline-secondary">
                                                                <i id="showOldPasswordIcon" class="fa fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="new_password" class="fw-bold">Mật khẩu mới</label>
                                                    <div class="input-group">
                                                        <input type="password" required name="new_password" id="new_password" class="form-control"
                                                            pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d]{8,}$" 
                                                            title="Mật khẩu ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số ">
                                                        <div class="input-group-append">
                                                            <button type="button" id="showNewPasswordToggle" class="btn btn-outline-secondary">
                                                                <i id="showNewPasswordIcon" class="fa fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="comfirm_password" class="fw-bold">Nhập lại mật khẩu mới</label>
                                                    <div class="input-group">
                                                        <input type="password" required name="comfirm_password" id="comfirm_password" class="form-control">
                                                        <div class="input-group-append">
                                                            <button type="button" id="showCPasswordToggle" class="btn btn-outline-secondary">
                                                                <i id="showCPasswordIcon" class="fa fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                    <div id="message_pass"></div>

                                                </div>
                                                <div class="modal-footer">
                                                    <a href="password-reset.php" class="btn btn-primary">Quên mật khẩu cũ</a>

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Thoát</button>

                                                    <button type="submit" name="update_password" class="btn btn-primary updatepassword">Lưu lại</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <?php }
                 }?>
        </div>
    </div>
</div>

</div>



<?php include('includes/footer.php'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Mật khẩu cũ
    var oldPasswordInput = document.getElementById('old_password');
    var showOldPasswordToggle = document.getElementById('showOldPasswordToggle');
    var showOldPasswordIcon = document.getElementById('showOldPasswordIcon');

    // Mật khẩu mới
    var newPasswordInput = document.getElementById('new_password');
    var showNewPasswordToggle = document.getElementById('showNewPasswordToggle');
    var showNewPasswordIcon = document.getElementById('showNewPasswordIcon');

    // Nhập lại mật khẩu
    var cPasswordInput = document.getElementById('comfirm_password');
    var showCPasswordToggle = document.getElementById('showCPasswordToggle');
    var showCPasswordIcon = document.getElementById('showCPasswordIcon');

    // Sự kiện hiển thị mật khẩu cũ
    showOldPasswordToggle.addEventListener('click', function () {
        togglePasswordVisibility(oldPasswordInput, showOldPasswordIcon);
    });

    // Sự kiện hiển thị mật khẩu mới
    showNewPasswordToggle.addEventListener('click', function () {
        togglePasswordVisibility(newPasswordInput, showNewPasswordIcon);
    });

    // Sự kiện hiển thị nhập lại mật khẩu
    showCPasswordToggle.addEventListener('click', function () {
        togglePasswordVisibility(cPasswordInput, showCPasswordIcon);
    });

    function togglePasswordVisibility(inputField, iconElement) {
        if (inputField.type === "text") {
            inputField.type = "password";
            iconElement.classList.remove('fa-eye-slash');
            iconElement.classList.add('fa-eye');
        } else {
            inputField.type = "text";
            iconElement.classList.remove('fa-eye');
            iconElement.classList.add('fa-eye-slash');
        }
    }
    var showPasswordIcon = document.getElementById('new_password');

        showPasswordCheckbox.addEventListener('change', function () {
            if (showPasswordCheckbox.checked) {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
            $(document).ready(function() {
            $('.updatepassword').click(function() {
                showLoader();});
            });
        });
});
function showLoader() {
    $('.loader').removeClass('loader-hidden');
}
</script>
