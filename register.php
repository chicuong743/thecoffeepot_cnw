<?php

session_start();
include('includes/header.php');
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
                        <h4>Đăng ký tài khoản</h4>
                    </div>
                    <div class="card-body">
                        <form action="function/authcode.php" method="POST">
                           <!-- viết hoa chữ cái đuầ của mỗi chử trong tên -->
                            <div class="mb-3">
                                <label class="form-label">Tên</label>
                                <input type="text" name="name" class="form-control text-capitalize" placeholder="Nhập tên tài khoản..."
                                    required>
                            </div>
                            <!-- Số điện thoại phải bắt đầu bằng số 0 và có tổng cộng 10 số. -->
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-control" placeholder="Nhập số điện thoại..."
                                    required pattern="^0[0-9]{9}$"
                                    title="Số điện thoại phải bắt đầu bằng số 0 và có tổng cộng 10 số.">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Nhập email..." 
                                    required pattern="^[a-zA-Z0-9._%+-] + @[a-zA-Z0-9.-] + \.[a-zA-Z]{2,}$" 
                                    title="Email phải có dạng abc@domain.com">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Mật khẩu</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Nhập mật khẩu..." id="exampleInputPassword1" required
                                        pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d]{8,}$"
                                        title="Mật khẩu phải có ít nhất 8 kí tự bao gồm thường, in hoa và số.">
                                    <button type="button" id="showPasswordToggle" class="btn btn-outline-secondary">
                                        <i id="showPasswordIcon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputPassword2" class="form-label">Nhập lại mật khẩu</label>
                                <div class="input-group">
                                    <input type="password" name="cpassword" class="form-control"
                                        placeholder="Nhập lại mật khẩu..." id="exampleInputPassword2" required>
                                    <button type="button" id="showCPasswordToggle" class="btn btn-outline-secondary">
                                        <i id="showCPasswordIcon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>



                            <button type="submit" name="register_btn" class="btn btn-primary">Đăng ký</button>
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
        var passwordInput = document.getElementById('exampleInputPassword1');
        var showPasswordToggle = document.getElementById('showPasswordToggle');
        var showPasswordIcon = document.getElementById('showPasswordIcon');

        var cpasswordInput = document.getElementById('exampleInputPassword2');
        var showCPasswordToggle = document.getElementById('showCPasswordToggle');
        var showCPasswordIcon = document.getElementById('showCPasswordIcon');

        showPasswordToggle.addEventListener('click', function () {
            togglePasswordVisibility(passwordInput, showPasswordIcon);
        });

        showCPasswordToggle.addEventListener('click', function () {
            togglePasswordVisibility(cpasswordInput, showCPasswordIcon);
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
    });
</script>
<script> 
    function showLoader() {
        $('.loader').removeClass('loader-hidden');
        }
        $(document).ready(function() {
        $('.login-btn').click(function() {
            showLoader();});
        });
</script>