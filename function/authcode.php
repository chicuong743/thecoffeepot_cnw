<?php

session_start();

// include('myfunctions.php');

include('../admin/config/dbcon.php');

function redirect($url, $message)
{
    $_SESSION['message'] = $message;
    header('Location:' . $url);
    exit(0);
}
use PHPMailer\PHPMailer\PHPMailer;
function send_authentication_mail($name,$email,$token){
    $body = "
        <h2> Xin Chào $name!</h2>
        <hr>
        <h5>Mã xác thực tài khoản của bạn là:    <h4 style='color :blue; ' >$token</h4>    </h5>
        <span>Vui lòng nhập đúng mã xác thực để kích hoặt tài khoản!</span>
        ";
        $subject = 'Mã xác thực tài khoản';

        require_once "../PHPMailer/PHPMailer.php";
        require_once "../PHPMailer/SMTP.php";
        require_once "../PHPMailer/Exception.php";
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com"; // smtp address of your email
        $mail->SMTPAuth = true;
        $mail->Username =  'cuongchi0704@gmail.com'; // Tài khoản email gửi
        $mail->Password = 'noxo kfkc ihba tdoj'; // Mật khẩu email gửi
        $mail->Port = 587; 
        $mail->SMTPSecure = "tls"; 
        $mail->smtpConnect([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        $mail->isHTML(true);
        $mail->setFrom('cuongchi0704@gmail.com', 'The Coffee Pot');
        $mail->addAddress($email); // enter email address whom you want to send
        $mail->Subject = ("$subject");
        $mail->Body = $body;
        $mail->send();
}
function send_password_reset($username, $useremail, $token)
{
    $body = "
        <h2>Chào bạn $username</h2>
        <hr>
        <h6>Để đặt lại mật khẩu cho tài khoản  $username, bạn cần nhập chính xác mã sau:</h6><br>
        <span><Mã đặt lại mật khẩu của bạn là:  </span><p> $token</p>
        " ;
    $subject = 'Thay đổi mật khẩu';

    require_once "../PHPMailer/PHPMailer.php";
    require_once "../PHPMailer/SMTP.php";
    require_once "../PHPMailer/Exception.php";
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com"; // smtp address of your email
    $mail->SMTPAuth = true;
    $mail->Username =  'cuongchi0704@gmail.com'; // Tài khoản email gửi
    $mail->Password = 'noxo kfkc ihba tdoj'; // Mật khẩu email gửi
    $mail->Port = 587; // port
    $mail->SMTPSecure = "tls"; // tls or ssl
    $mail->smtpConnect([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);

    $mail->isHTML(true);
    $mail->setFrom('cuongchi0704@gmail.com', 'The Coffee Pot');
    $mail->addAddress($useremail); // enter email address whom you want to send
    $mail->Subject = ("$subject");
    $mail->Body = $body;
    $mail->send();




}



//Đăng Ký
if (isset($_POST['register_btn'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, md5($_POST['password']));
    $cpassword = mysqli_real_escape_string($con, md5($_POST['cpassword']));

    //check email
    $check_email_query = "Select email from users where email = '$email' ";
    $check_email_query_run = mysqli_query($con, $check_email_query);
    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['message'] = 'Email đã được đăng ký';
        header('Location: ../register.php');
    } else {
        if ($password == $cpassword) {

            //
            $type = 0; //tài khoản khách hàng.
            $token = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
            $status = 1 ; //tình trạng chờ kích hoạt.
            $insert_query = "INSERT INTO users (name, email, phone, password, type, token, status) VALUES ('$name', '$email', '$phone', '$password', '$type', '$token', '$status')";
            $insert_query_run = mysqli_query($con, $insert_query);

            if ($insert_query_run) {
                //xác thực tài khoản 
                $user_id = mysqli_insert_id($con);
                $_SESSION['message'] = "Nhập mã xác thực được gửi về email của bạn";
                header('Location: ../authencation.php?'.
                                '&user_id='.urlencode($user_id).
                                '&name=' . urlencode($name) );
                send_authentication_mail($name,$email,$token);
            } else {
                // redirect("../register.php","Đăng ký thất bại");
                $_SESSION['message'] = "Đăng ký thất bại";
                header('Location: ../register.php');
            }

        } else {
            // redirect("../register.php","Mật khẩu không khớp");
            $_SESSION['message'] = "Mật khẩu không khớp";
            header('Location: ../register.php');
        }
    }
}
//Xác Thức Tài Khoản.
if (isset($_POST['authencation_btn'])) {
    $user_id = $_POST['user_id'];
    $input_token = $_POST['input_token'];
    //gọi để lấy token
    $query = "SELECT token  FROM users WHERE id_user = '$user_id'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stored_token = $row['token'];  
        if ($input_token === $stored_token) {
            // Nếu token khớp, kích hoạt tài khoản hoặc tiếp tục xác thực
            $update_query = "UPDATE users SET status = 0 WHERE id_user = '$user_id'";
            mysqli_query($con, $update_query);
            //gọi lại để lấy status mới nhất 
            $query_status = "SELECT status  FROM users WHERE id_user = '$user_id'";
            $result_status = mysqli_query($con, $query_status);
            if (mysqli_num_rows($result_status) > 0) {
                $row_status = mysqli_fetch_assoc($result_status);
                $status= $row['status'];
            }else {
                // Nếu không tìm thấy người dùng
                $_SESSION['message'] = "Không tìm thấy người dùng!";
                // Quay lại trang hiện tại
                $redirect_url = $_SERVER['HTTP_REFERER']; 
                header("Location: $redirect_url");
            }
            if ($status == 0) {
                $_SESSION['message'] = "Tiếp tục đăng nhập";
                header('Location: ../login.php');
            } else {
                $_SESSION['message'] = "Có lỗi xảy ra khi xác thực tài khoản.";
                // Quay lại trang hiện tại
                $redirect_url = $_SERVER['HTTP_REFERER']; 
                header("Location: $redirect_url");
            }
        } else {
            // Nếu mã xác thực không đúng
            $_SESSION['message'] = "Mã xác thực không đúng, vui lòng thử lại!";
            // Quay lại trang hiện tại
            $redirect_url = $_SERVER['HTTP_REFERER']; 
            header("Location: $redirect_url");
        }
    } else {
        // Nếu không tìm thấy người dùng
        $_SESSION['message'] = "Không tìm thấy người dùng!";
        // Quay lại trang hiện tại
        $redirect_url = $_SERVER['HTTP_REFERER']; 
        header("Location: $redirect_url");
    }
}
//Gửi lại mã xác thực mới 
if (isset($_POST['restart_authencation_btn'])){
    $user_id = $_POST['user_id'];
    $query = "SELECT *  FROM users WHERE id_user = '$user_id'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name =$row['name'];
        $email =$row['email'];
        $token_new = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $update_query = "UPDATE users SET token = '$token_new' WHERE id_user = '$user_id'";
        mysqli_query($con, $update_query);
        send_authentication_mail($name,$email,$token_new); 
        $_SESSION['message'] = "Nhập mã xác thực mới được gửi về email của bạn!";
        // Quay lại trang hiện tại
        $redirect_url = $_SERVER['HTTP_REFERER']; 
        header("Location: $redirect_url");

    }else {
        // Nếu không tìm thấy người dùng
        $_SESSION['message'] = "Không tìm thấy người dùng!";
        header('Location: ../authentication.php?user_id=' . $user_id . '$name='. $name );
        exit();
    }
}
//Đăng nhập
if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, md5($_POST['password']));
    // Kiểm tra xem đã có biến phiên lưu số lần đăng nhập không thành công hay chưa
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0; // Nếu chưa có, khởi tạo là 0
        $_SESSION['last_login_attempt_time'] = time(); // Lưu thời gian cuối cùng cố gắng đăng nhập
    }
    $reset_time = 60; // Thời gian tạm khóa (1 phút)
    // Kiểm tra số lần đăng nhập không thành công và thời gian cuối cùng
    $current_time = time();
    if ($current_time - $_SESSION['last_login_attempt_time'] >= $reset_time) {
        // Nếu đã qua đủ thời gian tạm khóa, reset login_attempts về 0
        $_SESSION['login_attempts'] = 0;
    }
    // Kiểm tra số lần đăng nhập không thành công
    if ($_SESSION['login_attempts'] >= 5) {
        // Nếu số lần vượt quá 5, thông báo và không cho đăng nhập nữa
        $_SESSION['message'] = "Số lần đăng nhập không thành công quá nhiều. Hãy thử lại sau.";
        header('Location: ../login.php');
        exit;
    }

    $login_query = "Select * from users where email = '$email' and password = '$password' ";
    $login_query_run = mysqli_query($con, $login_query);

    if (mysqli_num_rows($login_query_run) > 0) {

        $userdata = mysqli_fetch_array($login_query_run);
        $userid = $userdata['id_user'];
        $userphone= $userdata['phone'];
        $username = $userdata['name'];
        $useremail = $userdata['email'];
        $type = $userdata['type']; // lấy type trong sql
        $status = $userdata['status'];
        if ($status == 1) {
            $_SESSION['message'] = "Tài khoản bạn đã bị khóa hoặc chưa kích hoạt! <a href='authencation.php?user_id=". urlencode($userid).'&name='.urlencode($username)."'>Kích hoạt tài khoản</a>";
            header('Location: ../login.php');
            exit;
        }
        $_SESSION['auth'] = true;
        $_SESSION['auth_user'] = [
            'user_id' => $userid,
            'name' => $username,
            'email' => $useremail,
            'phone' => $userphone,
            'type' => $type
        ];
        //phân quyền
        $_SESSION['type'] = $type;

        if ($type == 1) {
            // redirect("../admin/index.php","Chào bạn đến với Admin");
            $_SESSION['message'] = "Chào bạn đến với Admin";
            header('Location: ../admin/index.php');
        } else if ($type == 2) {
            $_SESSION['message'] = "Chào nhân viên đến với Admin";
            header('Location:../admin/index.php');
        } else {
            // redirect("../index.php","Đăng nhập thành công");
            $_SESSION['message'] = "Đăng nhập thành công";
            header('Location: ../index.php');
        }

        // Reset số lần đăng nhập không thành công và thời gian cuối cùng
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_login_attempt_time'] = $current_time;
    } else {
        $_SESSION['last_login_attempt_time'] = $current_time;
        $_SESSION['login_attempts']++; // Tăng số lần đăng nhập không thành công

        $_SESSION['message'] = "Đăng nhập không thành công";
        header('Location: ../login.php');
    }
}

//gửi mã để đặt lại mật khẩu khi quên mật khẩu củ 
if (isset($_POST['password-reset-link'])) {
 
    $email =  mysqli_real_escape_string($con,$_POST['email']);
    // Tạo mã token ngẫu nhiên 8 ký tự
    $lowercase = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 1);
    $uppercase = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1);
    $digit = substr(str_shuffle('0123456789'), 0, 1);
    $remaining = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
    $token = str_shuffle($lowercase . $uppercase . $digit . $remaining);
    $password_token = md5($token);
    // Kiểm tra xem email có tồn tại không
    $check_email_query = "SELECT * FROM users WHERE email = '$email'";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        // Lấy thông tin người dùng
        $userdata = mysqli_fetch_array($check_email_query_run);
        $username = $userdata['name'];
        $update_password = "UPDATE users SET password = '$password_token' WHERE email = '$email' LIMIT 1";
        $update_password_run = mysqli_query($con, $update_password);
        if ($update_password_run) {
            // Gửi mật khẩu mới qua email
            send_password_reset($username, $email, $token);
            $_SESSION["message"] = "Nhập mã được gửi đến email của bạn và thay đổi mật khẩu.";
            header('Location: ../check-password-reset.php?'. '&email=' . urlencode($email) );
            exit();
        } else {
            // Lỗi cập nhật mật khẩu
            $_SESSION["message"] = "Đã có lỗi xảy ra. Vui lòng thử lại sau.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        // Email không tồn tại
        $_SESSION["message"] = "Email không tồn tại.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

if (isset($_POST['update_password_resert'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $password =mysqli_real_escape_string($con, md5($_POST['old_password']));
    $new_password = mysqli_real_escape_string($con, ($_POST['new_password']));
    $comfirm_password = mysqli_real_escape_string($con, ($_POST['comfirm_password']));
    $hashed_new_password = md5($new_password);
    
    $check_email_query = "SELECT * FROM users WHERE email = '$email'";
    $check_email_query_run = mysqli_query($con, $check_email_query);
    if (mysqli_num_rows($check_email_query_run) > 0) {
        // Lấy thông tin người dùng
        $userdata = mysqli_fetch_array($check_email_query_run);
        if($new_password == $comfirm_password ){
            $check_password = "Select * from users where password = '$password' ";
            $check_password_run = mysqli_query($con, $check_password);
            if(mysqli_num_rows($check_password_run) > 0){
                $update_password = "UPDATE users SET password = '$hashed_new_password' WHERE email = '$email' LIMIT 1";
                $update_password_run = mysqli_query($con, $update_password);
                if ($update_password_run) {
                    $_SESSION["message"] = "Bạn đã đổi mật khẩu thành công.";
                    header('Location: ../login.php');
                    exit();
                } else {
                    // Lỗi cập nhật mật khẩu
                    $_SESSION["message"] = "Cập nhật mật khẩu mới thất bại.";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }else{
                $_SESSION["message"] = "Mã xác thực không chính xác. ";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }else{
            $_SESSION["message"] = "Mật khẩu mới không khớp với nhau ";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }  
    }else{
        $_SESSION["message"] = "Email chưa có tài khoản";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

if (isset($_POST["update_password"])) {

    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $old_password = mysqli_real_escape_string($con, md5($_POST['old_password']));
    $new_password = mysqli_real_escape_string($con, md5($_POST['new_password']));
    $comfirm_password = mysqli_real_escape_string($con, md5($_POST['comfirm_password']));



    $check_pass = "SELECT password FROM users WHERE password = '$old_password'";
    $check_pass_run = mysqli_query($con, $check_pass);

    if (mysqli_num_rows($check_pass_run) == 0) {
        // $_SESSION['message_pass'] = "Mật khẩu cũ không đúng.";
        redirect('../account.php', "Mật khẩu cũ không đúng.");
    } else {
        if ($new_password == $comfirm_password) {
            $update_pass = "UPDATE users SET password = '$comfirm_password' WHERE email = '$email' LIMIT 1 ";
            $update_pass_run = mysqli_query($con, $update_pass);
            if ($update_pass_run) {
                if (isset($_SESSION['auth'])) {
                    unset($_SESSION['auth']);
                    redirect('../index.php', "Đổi mật khẩu thành công. Hãy đăng nhập lại");
                }


            } else {
                redirect('../account.php', "Đổi mật khẩu không thành công.");
            }

        } else {
            redirect('../account.php', "Nhập lại mật khẩu mới không đúng.");
        }

    }

}


if (isset($_POST['disabled_account'])) {

    $id_user = mysqli_real_escape_string($con, $_POST['id_user']);
    $value_disabled_account = mysqli_real_escape_string($con, $_POST['disabled_account']);

    $disabled_account_query = "UPDATE users SET status = '$value_disabled_account' WHERE id_user = '$id_user'";
    $disabled_account_query_run = mysqli_query($con, $disabled_account_query);

    if ($disabled_account_query_run) {

        if (isset($_SESSION['auth'])) {
            unset($_SESSION['auth']);
            redirect('../index.php', "Bạn đã khóa tài khoản");
        }

    } else {
        redirect('../account.php', "Không thể khóa tài khỏan");
    }

}
if (isset($_POST['update_account'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    // $old_email = mysqli_real_escape_string($con, $_POST['old_email']);

    $update_query = "UPDATE users SET name = '$name', phone = '$phone' WHERE email = '$email' ";
    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        // Cập nhật thành công
        redirect('../account.php', "Cập nhật thông tin thành công.");
        // $_SESSION['message'] = "Cập nhật thông tin thành công.";
    } else {
        // Cập nhật không thành công
        redirect('../account.php', "Cập nhật thông tin không thành công.");
        // $_SESSION['message'] = "Cập nhật thông tin không thành công.";
    }

}

if (isset($_POST['admin_disabled_account'])) {

    $id_user = mysqli_real_escape_string($con, $_POST["id_user"]);
    $name = mysqli_real_escape_string($con, $_POST["name"]);

    $disabled_account_query = "UPDATE users SET status = '1' WHERE id_user = '$id_user'";
    $disabled_account_query_run = mysqli_query($con, $disabled_account_query);

    if ($disabled_account_query_run) {
        redirect('../admin/user-account.php', "Bạn đã khóa tài khoản với tên $name");
    } else {
        redirect('../admin/user-account.php', "Không thể khóa tài khoản");
    }
}

if (isset($_POST["admin_enabled_account"])) {
    $id_user = mysqli_real_escape_string($con, $_POST["id_user"]);
    $name = mysqli_real_escape_string($con, $_POST["name"]);

    $enable_account_query = "UPDATE users SET status = '0' WHERE id_user = '$id_user'";
    $enabled_account_query_run = mysqli_query($con, $enable_account_query);

    if ($enabled_account_query_run) {
        redirect('../admin/user-account.php', "Bạn đã mở khóa tài khoản với tên $name");
    } else {
        redirect('../admin/user-account.php', "Không thể mở khóa tài khoản");
    }
}
if (isset($_POST['delete_user_account'])) {
    $id_user = mysqli_real_escape_string($con, $_POST['id_user']);
    $query_check = "SELECT * FROM users WHERE id_user = '$id_user' AND type = 1";
    $result_check = mysqli_query($con, $query_check);
    if (mysqli_num_rows($result_check) > 0) {
        $_SESSION['message'] = "Không thể xóa tài khoản Admin!";
        header("Location: ../admin/user-account.php");
        exit(0);
    }
    $query_cart_check = "SELECT * FROM carts WHERE user_id = '$id_user'";
    $result_cart_check = mysqli_query($con, $query_cart_check);
    if (mysqli_num_rows($result_cart_check) > 0) {
        $_SESSION['message'] = "Tài khoản có hoạt động. Không thể xóa.";
        header("Location: ../admin/user-account.php");
        exit(0);
    }
    $query_cart_check = "SELECT * FROM wishlist WHERE user_id = '$id_user'";
    $result_cart_check = mysqli_query($con, $query_cart_check);
    if (mysqli_num_rows($result_cart_check) > 0) {
        $_SESSION['message'] = "Tài khoản có hoạt động. Không thể xóa.";
        header("Location: ../admin/user-account.php");
        exit(0);
    }
    $query_cart_check = "SELECT * FROM orders WHERE user_id = '$id_user'";
    $result_cart_check = mysqli_query($con, $query_cart_check);
    if (mysqli_num_rows($result_cart_check) > 0) {
        $_SESSION['message'] = "Tài khoản có hoạt động. Không thể xóa.";
        header("Location: ../admin/user-account.php");
        exit(0);
    }
    $query = "DELETE FROM users WHERE id_user = '$id_user'";
    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        $_SESSION['message'] = "Tài khoản đã được xóa thành công!";
        header("Location: ../admin/user-account.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Tài khoản có hoạt động. Không thể xóa.";
        header("Location: ../admin/user-account.php");
        exit(0);
    }
}

if (isset($_POST["add-nhanvien"])) {
    $id_user = mysqli_real_escape_string($con, $_POST["id_user"]);
    $name = mysqli_real_escape_string($con, $_POST["name"]);

    $add_account_query = "UPDATE users SET type = '2' WHERE id_user = '$id_user'";
    $add_account_query_run = mysqli_query($con, $add_account_query);

    if ($add_account_query_run) {
        redirect('../admin/user-account.php', "Bạn đã $name là nhân viên của mình");
    } else {
        redirect('../admin/user-account.php', "Không thể thêm nhân viên");
    }
}
if (isset($_POST["remove-nhanvien"])) {
    $id_user = mysqli_real_escape_string($con, $_POST["id_user"]);
    $name = mysqli_real_escape_string($con, $_POST["name"]);

    $add_account_query = "UPDATE users SET type = '0' WHERE id_user = '$id_user'";
    $add_account_query_run = mysqli_query($con, $add_account_query);

    if ($add_account_query_run) {
        redirect('../admin/user-account.php', "Bạn đã xa thải nhân viên: $name ");
    } else {
        redirect('../admin/user-account.php', "Không xa thải nhân viên");
    }
}

?>