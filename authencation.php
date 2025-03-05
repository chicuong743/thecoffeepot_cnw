<?php

session_start();

if (isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Bạn đã đăng nhập rồi!";
    header('Location: index.php');
    exit();
}

include('includes/header.php');

$user_id = $_GET['user_id'];
$name= $_GET['name'];
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
                        <h4>Xác thực tài khoản: <?= $name ?></h4>
                    </div>
                    <div class="card-body">
                        <form action="function/authcode.php" method="POST">
                            <div class="mb-3">
                                <label for="" class="form-label">Mã xác thực</label>
                                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                                <input type="text" name="input_token" class="form-control" placeholder="Nhập mã xác thực..."
                                    id="" >
                            </div>
                            <span><span>Lưu ý: </span>Mã xác thực có phân biệt chử in hoa!</span>
                            <div class="row">
   
                                <div class="col text-start">
                                    <button type="submit" name="authencation_btn" class="btn btn-primary">Xác Thực</button>
                                </div>

                                <div class="col text-end">
                                    <button type="submit" name="restart_authencation_btn" class="btn btn-danger mt-2">Gửi lại mã</button>
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
    $('.restart_authencation_btn').click(function() {
        showLoader();});
    });
    $(document).ready(function() {
    $('.authencation_btn').click(function() {
        showLoader();});
    });
    
</script>
