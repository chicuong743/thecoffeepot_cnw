<?php
// session_start();
include('function/userfunctions.php');
include('includes/header.php');
include('authenticate.php');
include('C:\xampp\htdocs\thecoffeepot\admin\config\dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
function send_order_mail($con, $email, $name, $phone, $payment_mode, $shipping ,$tracking_no) {
    $adminEmail = 'cuongchi0704@gmail.com'; // Email của admin để nhận thông báo đơn hàng mới

    // Lấy id của đơn hàng từ tracking_no
    $orderQuery = "SELECT id FROM orders WHERE tracking_no = '$tracking_no' LIMIT 1";
    $orderResult = mysqli_query($con, $orderQuery);

    if ($orderResult && mysqli_num_rows($orderResult) > 0) {
        $orderData = mysqli_fetch_assoc($orderResult);
        $order_id = $orderData['id'];
        
        // Đặt tổng giá trị đơn hàng ban đầu bằng 0
        $totalPrice = 0;

        // Lấy các mặt hàng trong đơn hàng từ bảng order_items
        $itemsQuery = "SELECT oi.prod_id, oi.qty, oi.price, p.productName, p.image 
                       FROM order_items oi 
                       JOIN product p ON oi.prod_id = p.id 
                       WHERE oi.order_id = '$order_id'";
        $itemsResult = mysqli_query($con, $itemsQuery);

        $productDetails = "";
        //lấy tiền ship theo các loại ship trong bản shipping_unit theo $shipping 
        $shipping_query = "SELECT price FROM shipping_unit WHERE id = '$shipping'";
        $shipping_result = mysqli_query($con, $shipping_query);

        if ($shipping_result && mysqli_num_rows($shipping_result) > 0) {
            $shipping_data = mysqli_fetch_assoc($shipping_result);
            $shipping_price = $shipping_data['price']; // Lấy phí vận chuyển
        } else {
            $shipping_price = 150000; // Mặc định là 150k nếu không tìm thấy phương thức giao hàng
        }
        while ($item = mysqli_fetch_assoc($itemsResult)) {
            $productName = $item['productName'];
            $quantity = $item['qty'];
            $price = $item['price'];
            $image = $item['image'];
            
            // Cộng dồn giá trị sản phẩm vào tổng đơn hàng
            $totalPrice += $price;

            $productDetails .= "
                <tr>
                    <td><img src='$image' alt='$productName' width='50'></td>
                    <td>$productName</td>
                    <td>$quantity</td>
                    <td>" . number_format($price, 0, ',', '.') . " VND</td>
                </tr>
            ";
        }
        $totalPrice = $totalPrice + $shipping_price;

        // Nội dung email cho khách hàng
        $customerBody = "
            <h2>Cảm ơn $name đã đặt hàng tại The Coffee Pot!</h2>
            <p>Phương thức thanh toán: <span style='color:blue;'>$payment_mode</span></p>
            <p>Chi tiết đơn hàng: $tracking_no</p>
            <table border='1' cellpadding='5'>
                <tr>
                    <th>Ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
                $productDetails
            </table>
            <p><b>Phí Vận chuyên chuyển:</b> " . number_format($shipping_price, 0, ',', '.') . " VNĐ</p>
            <p><b>Tổng thanh toán:</b> " . number_format($totalPrice, 0, ',', '.') . " VNĐ</p>
            <br><hr>
            <span style='color: red;'>Chân thành cảm ơn!</span>
        ";

        // Phần gửi email vẫn giữ nguyên
    } else {
        $_SESSION['message'] = "Không tìm thấy đơn hàng với mã tracking: $tracking_no";
    }

    // Nội dung email cho quản trị viên
    $adminBody = "
        <h2>Có đơn hàng mới từ The Coffee Pot!</h2>
        <p>Khách hàng: $name </p>
        <p>Email: $email</p>
        <p>Số điện thoại: $phone </p>
        <p>Phương thức thanh toán: <span style='color:blue;'>$payment_mode</span></p>
        <br><hr>
        <span style='color: red;'>Vui lòng kiểm tra và xác nhận đơn hàng sớm nhất!</span>
    ";

    $subjectCustomer = 'Đặt hàng thành công';
    $subjectAdmin = 'Thông báo đơn hàng mới';

    require_once "./PHPMailer/PHPMailer.php";
    require_once "./PHPMailer/SMTP.php";
    require_once "./PHPMailer/Exception.php";

    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com"; 
    $mail->SMTPAuth = true;
    $mail->Username = 'cuongchi0704@gmail.com'; // Tài khoản email gửi
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

    // Gửi email cho khách hàng
    $mail->addAddress($email); 
    $mail->Subject = $subjectCustomer;
    $mail->Body = $customerBody;
    $mail->send();
    $mail->clearAddresses(); // Xóa địa chỉ email sau khi gửi

    // Gửi email cho quản trị viên
    $mail->addAddress($adminEmail); 
    $mail->Subject = $subjectAdmin;
    $mail->Body = $adminBody;
    $mail->send();
}
if(!isset($_GET['payment_mode'])){
//trường hợp khách vào xem đơn nên không truyền payment_mode
}else if(isset($_GET['payment_mode']) && $_GET['payment_mode']=="Thanh toán khi nhận hàng"){
    
}else if(isset($_GET['payment_mode']) && $_GET['payment_mode']=="Thanh toán qua PayPal"){
//trường hợp thanh toán khi nhận hàng['payment_mode']=="Thanh toán khi nhận hàng"
}else if (isset($_GET['payment_mode']) && $_GET['payment_mode']=="Thanh toán qua MoMo" && isset($_GET['resultCode']) && $_GET['resultCode'] == 0) {
    //thanh toán momo thành công ['resultCode'] == 0
    // Lấy các tham số từ URL
    $partnerCode = $_GET['partnerCode'];
    $orderId = $_GET['orderId'];
    $user_id=$_GET['user_id'];
    $requestId = $_GET['requestId'];
    $amount = $_GET['amount'];
    $orderInfo = $_GET['orderInfo'];
    $orderType = $_GET['orderType'];
    $transId = $_GET['transId'];
    $payType = $_GET['payType'];
    $signature = $_GET['signature'];

    // Dữ liệu cần thiết để lưu đơn hàng
    $totalPrice = $amount;
    $name = $_GET['name']; 
    $user_id=$_GET['user_id'];
    $email = $_GET['email']; 
    $phone = $_GET['phone']; 
    $city = $_GET['city'];
    $district = $_GET['district'];
    $ward = $_GET['ward'];
    $address = $_GET['address'];
    $payment_mode = $_GET['payment_mode'];
    $shipping = $_GET['shipping'];
    $payment_id= $transId;
    $tracking_no = $orderId; 


// Lưu đơn hàng vào cơ sở dữ liệu sau khi thanh toán thành công
$insert_query = "INSERT INTO orders (tracking_no, user_id, name, email, phone, city, district, ward, address, total_price, payment_mode, payment_id, shipping) 
                VALUES ('$tracking_no', '$user_id', '$name', '$email', '$phone', '$city', '$district', '$ward', '$address', '$totalPrice', '$payment_mode', '$payment_id', '$shipping')";
$insert_query_run = mysqli_query($con, $insert_query);

if ($insert_query_run) {
    $order_id = mysqli_insert_id($con);

    // Lưu các mặt hàng từ bảng cart vào order_items cho người dùng đã đăng nhập
    if (isset($_SESSION['auth'])) {
        $user_id = $_SESSION['auth_user']['user_id'];
        $cart_query = "SELECT * FROM carts WHERE user_id = '$user_id'";
        $cart_query_run = mysqli_query($con, $cart_query);

        if ($cart_query_run && mysqli_num_rows($cart_query_run) > 0) {
            while ($citem = mysqli_fetch_assoc($cart_query_run)) {
                $prod_id = $citem['prod_id'];
                $prod_qty = $citem['prod_qty'];
                // Truy vấn để lấy giá từ bảng product theo prod_id
                $query = "SELECT price FROM product WHERE id = $prod_id LIMIT 1";
                $result = mysqli_query($con, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $productData = mysqli_fetch_assoc($result);
                    $pricepr = $productData['price'];
                    $price = $pricepr * $prod_qty;
                } else {
                    $_SESSION['message'] = "Không tìm thấy sản phẩm với ID: $prod_id";
                    header('Location: ../checkout.php');
                    exit(0);
                }

                $insert_items_query = "INSERT INTO order_items (order_id, prod_id, qty, price) VALUES ('$order_id', '$prod_id', '$prod_qty', '$price')";
                mysqli_query($con, $insert_items_query);

                // Cập nhật số lượng trong kho
                $product_query = "SELECT quantity FROM product WHERE id = '$prod_id' LIMIT 1";
                $product_query_run = mysqli_query($con, $product_query);

                if ($product_query_run && mysqli_num_rows($product_query_run) > 0) {
                    $productData = mysqli_fetch_assoc($product_query_run);
                    $new_qty = $productData['quantity'] - $prod_qty;

                    // Kiểm tra số lượng tồn kho hợp lệ
                    if ($new_qty >= 0) {
                        $updateQty_query = "UPDATE product SET quantity = '$new_qty' WHERE id = '$prod_id'";
                        mysqli_query($con, $updateQty_query);
                    } else {
                        $_SESSION['message'] = "Không đủ hàng tồn kho cho sản phẩm ID: $prod_id";
                        header('Location: ../checkout.php');
                        exit(0);
                    }
                } else {
                    $_SESSION['message'] = "Không tìm thấy sản phẩm với ID: $prod_id";
                    header('Location: ../checkout.php');
                    exit(0);
                }
            }
            //gửi email.
            send_order_mail($con, $email, $name, $phone, $payment_mode, $shipping,$tracking_no);
            // Xóa dữ liệu trong bảng cart sau khi lưu vào order_items
            $delete_cart_query = "DELETE FROM carts WHERE user_id = '$user_id'";
            mysqli_query($con, $delete_cart_query);
        }
    } else {
        foreach ($_SESSION['guest_cart'] as $citem) {
            $prod_id = $citem['prod_id'];
            $prod_qty = $citem['prod_qty'];

            // Truy vấn để lấy giá từ bảng product theo prod_id
            $query = "SELECT price FROM product WHERE id = $prod_id LIMIT 1";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $productData = mysqli_fetch_assoc($result);
                $pricepr = $productData['price'];
                $price = $pricepr * $prod_qty;
            } else {
                $_SESSION['message'] = "Không tìm thấy sản phẩm với ID: $prod_id";
                header('Location: ../checkout.php');
                exit(0);
            }

            $insert_items_query = "INSERT INTO order_items (order_id, prod_id, qty, price) VALUES ('$order_id', '$prod_id', '$prod_qty', '$price')";
            mysqli_query($con, $insert_items_query);

            // Cập nhật số lượng trong kho
            $product_query = "SELECT quantity FROM product WHERE id = '$prod_id' LIMIT 1";
            $product_query_run = mysqli_query($con, $product_query);

            if ($product_query_run && mysqli_num_rows($product_query_run) > 0) {
                $productData = mysqli_fetch_assoc($product_query_run);
                $new_qty = $productData['quantity'] - $prod_qty;

                if ($new_qty >= 0) {
                    $updateQty_query = "UPDATE product SET quantity = '$new_qty' WHERE id = '$prod_id'";
                    mysqli_query($con, $updateQty_query);
                } else {
                    $_SESSION['message'] = "Không đủ hàng tồn kho cho sản phẩm ID: $prod_id";
                    header('Location: ../checkout.php');
                    exit(0);
                }
            } else {
                $_SESSION['message'] = "Không tìm thấy sản phẩm với ID: $prod_id";
                header('Location: ../checkout.php');
                exit(0);
            }
        }
        //gửi email.
        send_order_mail($con, $email, $name, $phone, $payment_mode, $shipping,$tracking_no);
        unset($_SESSION['guest_cart']); // Xóa giỏ hàng của khách vãng lai
    }
}
    // Lưu dữ liệu thanh toán MoMo vào bảng momo
    $insert_momo_query = "INSERT INTO momo (partnerCode, orderId, requestId, amount, orderInfo, orderType, transId, payType, signature) 
        VALUES ('$partnerCode', '$orderId', '$requestId', '$amount', '$orderInfo', '$orderType', '$transId', '$payType', '$signature')";
    mysqli_query($con, $insert_momo_query);

    // Hiển thị thông báo thành công
    $_SESSION['message'] = "Đặt hàng thành công qua MoMo!";
} else {
    // Nếu thanh toán không thành công, chuyển hướng về giỏ hàng hoặc trang thanh toán
    $_SESSION['message'] = "Thanh toán không thành công. Vui lòng thử lại!";
    header("Location: cart.php");
    exit();
}

?>

<div class="py-3 bg-primary">
    <div class="container">
        <h6 class="text-white ">
            <a class="text-white" href="index.php">
                Home /
            </a> 
            <a class="text-white" href="my-orders.php">
                Đơn Hàng
            </a>
        </h6>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="card card-body shadow">
            <div id="message"></div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table  table-hover ">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mã đơn hàng</th>
                                <th>Giá</th>
                                <th>Ngày đặt</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php
                            $orders = getOrders();

                            if (mysqli_num_rows($orders) > 0) {
                                foreach ($orders as $item) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $item['id'] ?>
                                        </td>
                                        <td>
                                            <?= $item['tracking_no'] ?>
                                        </td>
                                        <td>
                                        <?= number_format($item['total_price'], 0, ',', '.') ?> VNĐ
                                            <!-- <?= $item['total_price'] ?> -->
                                        </td>
                                        <td>
                                            <?= date('H:i - d/m/Y', strtotime($item['created_at'])) ?>
                                        </td>
                                        <td>
                                            <a href="view-order.php?t=<?= $item['tracking_no'] ?>" class="btn btn-primary view">Xem
                                                chi tiết</a>
                                        </td>
                                    </tr>
                                    <?php
                                }

                            } else {
                                ?>
                                <tr>
                                    <td>
                                    <td colspan="5">Bạn chưa mua sản phẩm nào</td>
                                    </td>
                                </tr>
                                <?php

                            }

                            ?>
                        </tbody>
                    </table>

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
    $('.view').click(function() {
        showLoader();});
    });
</script>