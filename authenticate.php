<?php 

if (!isset($_SESSION['auth'])) {
    redirect("login.php","Đăng nhập để tiếp tục");
}

?>