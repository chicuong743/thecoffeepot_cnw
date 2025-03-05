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
                        <h4>Lấy lại mật khẩu</h4>
                    </div>
                    <div class="card-body">
                        <form action="function/authcode.php" method="POST">
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Email tài khoản của bạn là:</label>
                            <input type="email" name="email" class="form-control" placeholder="Nhập email..." 
                                required 
                                pattern="^[a-zA-Z0-9._%+-] + @[a-zA-Z0-9.-] + \.[a-zA-Z]{2,}$" 
                                id="exampleInputPassword1"
                                title="Email phải có dạng abc@domain.com">
                        </div>
                        <div>
                            <button type="submit" name="password-reset-link" class="btn btn-primary passwordresetlink">Tiếp tục</button>
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
        var passwordInput = document.getElementById('exampleInputPassword1');

        showPasswordCheckbox.addEventListener('change', function () {
            if (showPasswordCheckbox.checked) {
                passwordInput.type = "text";
                return;
            } else {
                passwordInput.type = "email";
                return;
            }
        });
        $(document).ready(function() {
        $('.passwordresetlink').click(function() {
            showLoader();});
        });
    });
 function showLoader() {
    $('.loader').removeClass('loader-hidden');
    }
    
</script>
