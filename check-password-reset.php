<?php

session_start();

if (isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Bạn đã đăng nhập rồi!";
    header('Location: index.php');
    exit();
}

include('includes/header.php');
$email = $_GET['email']; 
?>
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                if (isset($_SESSION['message'])) {
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey</strong>
                        <?= $_SESSION['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                    unset($_SESSION['message']);
                }
                ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Đổi mật khẩu</h4>
                    </div>
                    <div class="card-body">
                    <form action="function/authcode.php" method="POST">
                                        <div class="modal-body">
                                            <div id="message" class="message"></div>
                                                <div class="form-group">
                                                    <input type="hidden" name="email" value="<?=$email ?>">
                                                    <!--mật khẩu được tạo tự động gửi về email-->
                                                    <label for="old_password" class="fw-bold">Nhập mã được gửi về email:</label>
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
                                                    <label for="new_password" class="fw-bold">Mật khẩu mới:</label>
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
                                                    <label for="comfirm_password" class="fw-bold">Nhập lại mật khẩu mới:</label>
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
                                            <div class="modal-footer mt-3">
                                                <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal">Thoát</button>

                                                <button type="submit" name="update_password_resert" class="btn btn-primary updatepasswordresert">Lưu lại</button>
                                            </div>
                                        </div>
                                    </div>  
                                </form>




                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('showOldPasswordToggle').addEventListener('click', function () {
            togglePasswordVisibility('old_password', 'showOldPasswordIcon');
        });

        document.getElementById('showNewPasswordToggle').addEventListener('click', function () {
            togglePasswordVisibility('new_password', 'showNewPasswordIcon');
        });

        document.getElementById('showCPasswordToggle').addEventListener('click', function () {
            togglePasswordVisibility('comfirm_password', 'showCPasswordIcon');
        });

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
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
            $('.updatepasswordresert').click(function() {
                showLoader();});
            });
        });
    });

    function showLoader() {
    $('.loader').removeClass('loader-hidden');
    }

</script>
