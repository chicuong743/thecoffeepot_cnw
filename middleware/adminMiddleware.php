<?php

include('../function/myfunctions.php');

if (isset($_SESSION['auth'])) {
    if ($_SESSION['type'] == 0 ) {
        redirect("../index.php","Bạn không có quyền truy cập trang Admin");
    }
} else {
    redirect("../login.php","Đăng nhập để tiếp tục");
   
}

?>