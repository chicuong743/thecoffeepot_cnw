<?php

session_start();

if (isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Bạn đã đăng nhập rồi!";
    header('Location: index.php');
    exit();
}

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
                        <h4>Đăng nhập</h4>
                    </div>
                    <div class="card-body">
                        <form action="function/authcode.php" method="POST">



                            <div class="mb-3">
                                <label for="" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Nhập Email..."
                                    id="" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Mật khẩu</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Nhập mật khẩu..." id="exampleInputPassword1" required
                                    pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d]{8,}$"
                                        title="Mật khẩu phải có ít nhất 8 kí tự bao gồm thường, in hoa và số.">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="showPasswordCheckbox">
                                <label class="form-check-label" for="showPasswordCheckbox">Hiển thị mật khẩu</label>
                            </div>
                            <div>
                                <span>Bạn bị quên mật khẩu? <a href="password-reset.php">Quên mật khẩu</a></span>
                            </div>
                            <div>
                                <button type="submit" name="login_btn" class="btn btn-primary">Đăng nhập</button>

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
        var showPasswordCheckbox = document.getElementById('showPasswordCheckbox');
        var showPasswordIcon = document.getElementById('showPasswordIcon');

        showPasswordCheckbox.addEventListener('change', function () {
            if (showPasswordCheckbox.checked) {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        });
    });


    function showLoader() {
    $('.loader').removeClass('loader-hidden');
    }
    $(document).ready(function() {
    $('.login-btn').click(function() {
        showLoader();});
    });
</script>
