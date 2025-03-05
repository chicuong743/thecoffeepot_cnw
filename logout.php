<?php
session_start();

if (isset($_SESSION['auth'])) {
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);

    $_SESSION['message'] = "Bạn đã đăng xuất tài khoản" ;
}
header('Location: index.php');
?>
<script> 
function showLoader() {
    $('.loader').removeClass('loader-hidden');
    }
    $(document).ready(function() {
    $('.login-btn').click(function() {
        showLoader();});
    });
</script>