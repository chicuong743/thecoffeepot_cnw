<?php
session_start();
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

    require_once "../PHPMailer/PHPMailer.php";
    require_once "../PHPMailer/SMTP.php";
    require_once "../PHPMailer/Exception.php";

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

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //lấy dữ liệu từ form
    
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $district = mysqli_real_escape_string($con, $_POST['district']);
    $ward = mysqli_real_escape_string($con, $_POST['ward']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $shipping = isset($_POST['shipping']) ? mysqli_real_escape_string($con,$_POST['shipping']):'12';//hình thức mặt định là id 12
    $payment_mode = mysqli_real_escape_string($con, $_POST['payment_mode']);
    $user_id = isset($_SESSION['auth']) ? $_SESSION['auth_user']['user_id'] :1;
    $payment_id = isset($_POST['payment_id']) ? mysqli_real_escape_string($con, $_POST['payment_id']) : null;
    //kiểm tra thông tin đầy đủ chưa
    if ($name == "" || $email == "" || $phone == "" || $city=="" ||  $district==""||  $ward=="" || $address == "") {
        header('Location:../checkout.php ');
        $_SESSION['message'] = "Hãy điền đầy đủ thông tin";
        exit();
    }
    // Tạo tracking number cho khách vãng lai
    $tracking_no = "ntv" . rand(1111, 9999) . substr($phone, 2);
    // Tính tổng đơn hàng
    //lấy tiền ship theo các loại ship trong bản shipping_unit theo $shipping 
    $shipping_query = "SELECT price FROM shipping_unit WHERE id = '$shipping'";
    $shipping_result = mysqli_query($con, $shipping_query);

    if ($shipping_result && mysqli_num_rows($shipping_result) > 0) {
        $shipping_data = mysqli_fetch_assoc($shipping_result);
        $shipping_price = $shipping_data['price']; // Lấy phí vận chuyển
    } else {
        $shipping_price = 150000; // Mặc định là 150k nếu không tìm thấy phương thức giao hàng
    }
    $totalPrice = 0;
    if (isset($_SESSION['auth'])) {
        // Lấy thông tin giỏ hàng của khách đã đăng nhập
        $user_id = $_SESSION['auth_user']['user_id'];
        $query = "SELECT c.id as cid, c.prod_id, c.prod_qty, p.id as pid, p.productName, p.image, p.price, p.sale
                FROM carts c
                JOIN product p ON c.prod_id = p.id
                WHERE c.user_id = '$user_id'
                ORDER BY c.id DESC";
        $query_run = mysqli_query($con, $query);

        foreach ($query_run as $citem) {
            $totalPrice += ($citem['price']-($citem['price']*$citem['sale']/100)) * $citem['prod_qty'];
        }
    } else if (isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart'])) {
        // Lấy thông tin giỏ hàng của khách vãng lai
        foreach ($_SESSION['guest_cart'] as $citem) {
            $prod_id = $citem['prod_id'];
            $prod_qty = $citem['prod_qty'];
            $query = "SELECT * FROM product WHERE id = '$prod_id' LIMIT 1";
            $result = mysqli_query($con, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $productData = mysqli_fetch_assoc($result);
                $sale=$productData['sale'];
                $pricepr = $productData['price'];
                $totalPrice += ($pricepr -($pricepr*$sale/100)) * $prod_qty;
            } else {
                $_SESSION['message'] = "Không tìm thấy sản phẩm với ID: $prod_id";
                header('Location: ../checkout.php');
                exit(0);
            }
        }
    }
    $totalPrice = $totalPrice + $shipping_price;
    // Hàm lưu đơn hàng vào cơ sở dữ liệu sau khi thanh toán thành công
    function saveOrder($con, $user_id, $totalPrice, $name, $email, $phone, $city, $district, $ward, $address, $payment_mode, $payment_id, $shipping, $tracking_no) {
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
                        $prod_id= $citem['prod_id'];
                        $prod_qt= $citem['prod_qty'];

                        //lấy giá từ bản product
                        $query = "SELECT price,sale FROM product WHERE id = $prod_id LIMIT 1";
                        $result = mysqli_query($con, $query);
        
                        if ($result && mysqli_num_rows($result) > 0) {
                            $productData = mysqli_fetch_assoc($result);
                            $pricepr = $productData['price']; 
                            $sale=$productData['sale'];
                            $price=($pricepr-($pricepr*$sale/100)) * $prod_qt;
                        } else {
                            // Nếu không lấy được giá, xử lý lỗi ở đây (nếu cần)
                            $_SESSION['message'] = "Không tìm thấy sản phẩm với ID: $prod_id";
                            header('Location: ../checkout.php');
                            exit(0);
                        }
                        saveOrderItems($con, $order_id, $prod_id, $prod_qt, $price);
                        send_order_mail($con ,$email, $name, $phone, $payment_mode,$shipping, $tracking_no );
                    }
                    // Xóa dữ liệu trong bảng cart sau khi lưu vào order_items
                    $delete_cart_query = "DELETE FROM carts WHERE user_id = '$user_id'";
                    mysqli_query($con, $delete_cart_query);
                }
            } else {
                foreach ($_SESSION['guest_cart'] as $citem) {
                    // Truy vấn để lấy giá từ bảng product theo prod_id
                    $prod_id = $citem['prod_id'];
                    $prod_qt = $citem['prod_qty'];
                    $query = "SELECT price,sale FROM product WHERE id = $prod_id LIMIT 1";
                    $result = mysqli_query($con, $query);
    
                    if ($result && mysqli_num_rows($result) > 0) {
                        $productData = mysqli_fetch_assoc($result);
                        $sale= $productData['sale'];
                        $pricepr = $productData['price']; 
                        $price = ($pricepr-($pricepr*$sale/100)) * $prod_qt;
                    } else {
                        // Nếu không lấy được giá, xử lý lỗi ở đây (nếu cần)
                        $_SESSION['message'] = "Không tìm thấy sản phẩm với ID: $prod_id";
                        header('Location: ../checkout.php');
                        exit(0);
                    }
                    saveOrderItems($con, $order_id, $citem['prod_id'], $citem['prod_qty'], $price);
                    send_order_mail($con ,$email, $name, $phone, $payment_mode,$shipping, $tracking_no );
                }
                unset($_SESSION['guest_cart']); // Xóa giỏ hàng của khách vãng lai
            }
        }
    }
    
    function saveOrderItems($con, $order_id, $prod_id, $prod_qty, $price) {
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
                // Xử lý khi số lượng không đủ
                $_SESSION['message'] = "Không đủ hàng tồn kho cho sản phẩm ID: $prod_id";
                header('Location: ../checkout.php');
                exit(0);
            }
        } else {
            // Nếu sản phẩm không tồn tại
            $_SESSION['message'] = "Không tìm thấy sản phẩm với ID: $prod_id";
            header('Location: ../checkout.php');
            exit(0);
        }
    }
    
    //MOMO
    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    // Kiểm tra phương thức thanh toán
    if ($payment_mode === 'Thanh toán khi nhận hàng') {
        if (isset($_SESSION['auth'])) {
            header('Location: ../my-orders.php?' . '&payment_mode=' . urlencode($payment_mode));
        } else {
            header('Location: ../thank-you.php?' . '&payment_mode=' . urlencode($payment_mode));
        }
        $_SESSION['message'] = "Đặt hàng thành công!";
        saveOrder($con, $user_id, $totalPrice, $name, $email, $phone, $city, $district, $ward, $address, $payment_mode, $payment_id, $shipping, $tracking_no);
    } 
    elseif ($payment_mode === 'Thanh toán qua MoMo') {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán qua MoMo";
        $amount = $totalPrice;
        $orderId =$tracking_no;// lưu ý
        if (isset($_SESSION['auth'])) {
            $redirectUrl = 'http://localhost:82/bannoithat/my-orders.php?' .
                           'totalPrice=' . urlencode($totalPrice) .
                           '&user_id='.urlencode( $user_id).
                           '&name=' . urlencode($name) .
                           '&email=' . urlencode($email) .
                           '&phone=' . urlencode($phone) .
                           '&city=' . urlencode($city) .
                           '&district=' . urlencode($district) .
                           '&ward=' . urlencode($ward) .
                           '&address=' . urlencode($address) .
                           '&payment_mode=' .urlencode($payment_mode) .
                           '&shipping=' . urlencode($shipping) .
                           '&payment_id='.urlencode($payment_id ).
                           '&tracking_no=' . urlencode($tracking_no);
        } else {
            $redirectUrl = 'http://localhost:82/bannoithat/thank-you.php?' .
                           'totalPrice=' . urlencode($totalPrice) .
                           '&user_id='.urlencode( $user_id).
                           '&name=' . urlencode($name) .
                           '&email=' . urlencode($email) .
                           '&phone=' . urlencode($phone) .
                           '&city=' . urlencode($city) .
                           '&district=' . urlencode($district) .
                           '&ward=' . urlencode($ward) .
                           '&address=' . urlencode($address) .
                           '&payment_mode=' .urlencode($payment_mode) .
                           '&shipping=' . urlencode($shipping) .
                           '&payment_id='.urlencode($payment_id ).
                           '&tracking_no=' . urlencode($tracking_no);
        }
        $ipnUrl = $redirectUrl;
        $extraData = "";
        $serectkey = $secretKey;
        $requestId = time() . "";
        //$requestType="captureWallet"; //thanh toán bằng mã QR khi thanh toán thật.
        $requestType = "payWithATM";//thanh toán bằng thẻ khi thanh toán thật và test.
        //$extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
        //before sign HMAC SHA256 signature

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $serectkey);
        $data = array('partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true); // decode json
        header('Location: ' . $jsonResult['payUrl']);
        exit();
    }
    elseif ($payment_mode === 'Thanh toán qua PayPal') {
    // Thanh toán qua PayPal
    saveOrder($con, $user_id, $totalPrice, $name, $email, $phone, $city, $district, $ward, $address, "Thanh toán qua PayPal", $payment_id, $shipping, $tracking_no);
    $_SESSION['message'] = "Thanh toán thành công qua PayPal";
    }
}
?>