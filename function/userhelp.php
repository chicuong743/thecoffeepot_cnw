<?php
session_start();

include('../admin/config/dbcon.php');

function redirect($url, $message)
{
    $_SESSION['message'] = $message;
    header('Location:' . $url);
    exit(0);
}
use PHPMailer\PHPMailer\PHPMailer;

function send_message($name, $recipientEmail, $message){

    $body = "
    <h2>Chào bạn $name !</h2>
    <h3>Cảm ơn về những đóng góp và góp ý từ $name .</h3>
    <h4>$message</h4>
        ";
$subject = 'Phản hồi từ The Coffee Pot ';

require_once "../PHPMailer/PHPMailer.php";
require_once "../PHPMailer/SMTP.php";
require_once "../PHPMailer/Exception.php";
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';
$mail->isSMTP();
$mail->Host = "smtp.gmail.com"; // smtp address of your email
$mail->SMTPAuth = true;
$mail->Username = 'cuongchi0704@gmail.com';
$mail->Password = 'noxo kfkc ihba tdoj';
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
$mail->setFrom('cuongchi0704@gmail.com', 'TheCoffeePot');
$mail->addAddress($recipientEmail); // enter email address whom you want to send
$mail->Subject = ("$subject");
$mail->Body = $body;
$mail->send();

}

if (isset($_POST['helper'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // Thực hiện truy vấn để chèn dữ liệu vào CSDL
    $sql = "INSERT INTO helpper (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
    
    $query_sql_run = mysqli_query($con, $sql);

    if ($query_sql_run) {
        header('Location: ../index.php');
        $_SESSION['message'] = "Đã gửi hỗ trợ thành công!";
        exit();
    } else {
        echo "Lỗi khi thực hiện truy vấn: " . mysqli_error($con);
    }
}

if (isset($_POST['repply_message'])) {

    $id = mysqli_real_escape_string($con, $_POST['id']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $message = mysqli_real_escape_string($con, $_POST['repply']);

    $sql = "UPDATE helpper SET status = 1 WHERE id = '$id' ";
    $query_sql_run = mysqli_query($con, $sql);

    if($query_sql_run){
        send_message($name,$email,$message);
        $_SESSION["message"] = "Bạn đã gửi phản hồi thành công đến $email $name $message";
        header("Location:../admin/message.php");
        exit(0);
    }else{
        $_SESSION["message"] = "Phản hồi không thành công đến $email ";
        header("Location:../message.php");
        exit(0);
    }

}

if (isset($_POST['add-feedback'])) {
    $id_product = $_POST['id_product'];
    $id_user = $_POST['id_user'] ?? 1;
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $rating = $_POST['rating'];
    $note = $_POST['note'];
    $status = 1;
    // Xử lý upload ảnh (nếu có)
    $image = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : null;
    $imagePath = null;
    if ($image) {
        $path = "./uploads/";
        $image_ext = pathinfo($image, PATHINFO_EXTENSION);
        $filename = time(). $image_ext;
        $imagePath = $path . $filename;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            die("Không thể tải ảnh lên. Vui lòng thử lại.");
        }
    }

    // Kiểm tra các trường bắt buộc
    if (!$id_product || !$name || !$phone || !$email || !$rating || !$note) {
        die("Vui lòng nhập đầy đủ thông tin bắt buộc: tên, số điện thoại, email và đánh giá.");
    }
    $id_user_check_query = "SELECT COUNT(*) as count FROM users WHERE id_user = '$id_user'";
    $result = mysqli_query($con, $id_user_check_query);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] == 0) {
        $id_user = 1; 
    }
    $insert_items_query = "INSERT INTO feedback (id_user, id_product, name, phone, email, rating, note, image, status) 
        VALUES ('$id_user', '$id_product', '$name', '$phone', '$email', '$rating', '$note', '$imagePath', '$status')";

    if(mysqli_query($con, $insert_items_query)){
        $_SESSION['message'] = "Đánh giá sản phẩm thành công!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    else{
        $_SESSION['message'] = "Đã xảy ra lỗi vui lòng thử lại sau!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

?>
