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
                        <h4>Đặt lại mật khẩu</h4>
                    </div>
                    <div class="card-body">



                        <form action="function/authcode.php" method="POST">
                            <input type="hidden" name="password_token" value="<?php if(isset($_GET['token'])){echo $_GET['token']; } ?>">    
                        <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Email</label>
                                <input type="email" name="email" value="<?php if(isset($_GET['email'])){echo $_GET['email'];} ?>" class="form-control" placeholder="Nhập mã..."
                                    id="exampleInputPassword1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới..."
                                    id="exampleInputPassword1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Nhập lại mật khẩu mới</label>
                                <input type="password" name="comfirm_password" class="form-control" placeholder="Nhập lại mật khẩu..."
                                    id="exampleInputPassword1">
                            </div>
                            <div>
                                <button type="submit" name="password-update" class="btn btn-primary passwordupdate">Cập nhật</button>

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
    function showLoader() {
        $('.loader').removeClass('loader-hidden');
        }
        $(document).ready(function() {
        $('.passwordupdate').click(function() {
            showLoader();});
        });
</script>