<?php
ob_start(); // Bắt đầu bộ đệm đầu ra
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include(__DIR__ . '/../admin/config/dbcon.php');

if (isset($_POST['scope'])) {
    $scope = $_POST['scope'];
    if (isset($_SESSION['auth'])) {
        switch ($scope) {
            case "add-buynow":
                $prodid = $_POST['prodid'];
                $prodqty = $_POST['prodqty'];

                // Kiểm tra số lượng tồn kho
                $check_quantity = "SELECT quantity FROM product WHERE id = '$prodid'";
                $check_quantity_run = mysqli_query($con, $check_quantity);
                $row = mysqli_fetch_assoc($check_quantity_run);
                $quantity = $row['quantity'];

                if ($quantity == 0) {
                    echo json_encode(['status' => 'error', 'message' => '<div class=""><strong>Xin lỗi! Sản phẩm này đã hết hàng.</strong></div>']);
                } elseif ($prodqty > $quantity) {
                    echo json_encode(['status' => 'error', 'message' => '<div class=""><strong> Xin lỗi! Số lượng sản này chỉ còn: ' . $quantity . ' sản phẩm. </strong></div>']);
                } else {
                    $user_id = $_SESSION['auth_user']['user_id'];

                    // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
                    $check_existing_cart = "SELECT * FROM carts WHERE prod_id = '$prodid' AND user_id = '$user_id'";
                    $check_existing_cart_run = mysqli_query($con, $check_existing_cart);

                    if (mysqli_num_rows($check_existing_cart_run) > 0) {
                        // Cập nhật số lượng nếu sản phẩm đã tồn tại
                        $existing_cart = mysqli_fetch_assoc($check_existing_cart_run);
                        $current_qty_in_cart = $existing_cart['prod_qty'];
                        $new_qty = $current_qty_in_cart + $prodqty;

                        if ($new_qty > $quantity) {
                            echo json_encode(['status' => 'error', 'message' => '<div class=""><strong> Xin lỗi! Số lượng sản này chỉ còn: '.$quantity - $current_qty_in_cart.' sản phẩm.</strong></div>']);
                        } else {
                            $update_cart_query = "UPDATE carts SET prod_qty = '$new_qty' WHERE prod_id = '$prodid' AND user_id = '$user_id'";
                            $update_cart_query_run = mysqli_query($con, $update_cart_query);

                            if ($update_cart_query_run) {
                                echo json_encode(['status' => 'success', 'redirect' => 'cart.php']);
                            } else {
                                echo json_encode(['status' => 'error', 'message' => 'Cập nhật sản phẩm bị lỗi.']);
                            }
                        }
                    } else {
                        // Thêm sản phẩm mới vào giỏ hàng
                        $insert_query = "INSERT INTO carts (user_id, prod_id, prod_qty) VALUES ('$user_id', '$prodid', '$prodqty')";
                        $insert_query_run = mysqli_query($con, $insert_query);

                        if ($insert_query_run) {
                            echo json_encode(['status' => 'success', 'redirect' => 'cart.php']);
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Thêm sản phẩm bị lỗi.']);
                        }
                    }
                }
                break;

              
            case "add": // thêm vào giỏ hàng
                $prodid = $_POST['prodid'];
                $prodqty = $_POST['prodqty'];
                // Kiểm tra số lượng tồn kho của sản phẩm
                $check_quantity = "SELECT quantity FROM product WHERE id = '$prodid'";
                $check_quantity_run = mysqli_query($con, $check_quantity);
                $row = mysqli_fetch_assoc($check_quantity_run);
                $quantity = $row['quantity'];
                // Nếu số lượng tồn kho bằng 0 hoặc số lượng yêu cầu lớn hơn số lượng có sẵn
                if($quantity == 0){
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Xin lỗi! Sản phẩm này đã hết hàng.</strong></div>';
                }elseif ( $prodqty > $quantity) {
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong> Xin lỗi! Số lượng sản này chỉ còn: ' . $quantity . ' sản phẩm. </strong></div>';
                } else {
                    // Lấy ID của người dùng từ session
                    $user_id = $_SESSION['auth_user']['user_id'];
                    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
                    $check_existing_cart = "SELECT * FROM carts WHERE prod_id = '$prodid' AND user_id = '$user_id'";
                    $check_existing_cart_run = mysqli_query($con, $check_existing_cart);
                    if (mysqli_num_rows($check_existing_cart_run) > 0) {
                        // Nếu sản phẩm đã có trong giỏ hàng, lấy số lượng hiện tại của nó
                        $existing_cart = mysqli_fetch_assoc($check_existing_cart_run);
                        $current_qty_in_cart = $existing_cart['prod_qty'];
                        // Tính số lượng mới sau khi cộng thêm số lượng mua từ POST
                        $new_qty = $current_qty_in_cart + $prodqty;
                        // Kiểm tra nếu số lượng mới vượt quá tồn kho
                        if($quantity - $current_qty_in_cart==0){
                            echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Xin lỗi! Sản phẩm này đã hết hàng.</strong></div>';
                        }
                        elseif ($new_qty > $quantity) {
                            echo '<div class="alert alert-danger alert-dismissible mt2"><strong> Xin lỗi! Số lượng sản này chỉ còn: '.$quantity - $current_qty_in_cart.' sản phẩm.</strong></div>';
                        } else{
                            // Cập nhật lại số lượng sản phẩm trong giỏ hàng
                            $update_cart_query = "UPDATE carts SET prod_qty = '$new_qty' WHERE prod_id = '$prodid' AND user_id = '$user_id'";
                            $update_cart_query_run = mysqli_query($con, $update_cart_query);
                            
                            if ($update_cart_query_run) {
                                echo '<div class="alert alert-success alert-dismissible mt2"><strong> Đã cập nhật số lượng sản phẩm trong giỏ hàng </strong></div>';
                            } else {
                                echo '<div class="alert alert-danger alert-dismissible mt2"><strong> Cập nhật sản phẩm bị lỗi</strong></div>';
                            }
                        }
                    } else {
                        // Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới vào giỏ
                        $insert_query = "INSERT INTO carts (user_id,prod_id,prod_qty) VALUES ('$user_id','$prodid','$prodqty')";
                        $insert_query_run = mysqli_query($con, $insert_query);
                        
                        if ($insert_query_run) {
                            echo '<div class="alert alert-success alert-dismissible mt2"><strong> Đã thêm sản phẩm vào giỏ hàng </strong></div>';
                        } else {
                            echo '<div class="alert alert-danger alert-dismissible mt2"><strong> Thêm sản phẩm bị lỗi</strong></div>';
                        }
                    }
                }
                break;
            case "update": // cập nhật lại giỏ hàng
                $prod_id = $_POST['prod_id'];
                $prod_qty = $_POST['prod_qty'];
                $user_id = $_SESSION['auth_user']['user_id'];
                
                // Kiểm tra số lượng tồn kho của sản phẩm
                $check_quantity = "SELECT quantity FROM product WHERE id = '$prod_id'";
                $check_quantity_run = mysqli_query($con, $check_quantity);
                $row = mysqli_fetch_assoc($check_quantity_run);
                $quantity = $row['quantity'];
                
                // Nếu số lượng yêu cầu lớn hơn số lượng tồn kho
                if ($prod_qty > $quantity) {
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Xin lỗi! Số lượng sản của chúng tôi chỉ còn '.$quantity.'</strong></div>';
                } else {
                    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
                    $check_existing_cart = "SELECT * FROM carts WHERE prod_id = '$prod_id' AND user_id = '$user_id'";
                    $check_existing_cart_run = mysqli_query($con, $check_existing_cart);
                    
                    if (mysqli_num_rows($check_existing_cart_run) > 0) {
                        // Cập nhật số lượng trong giỏ hàng nếu đủ tồn kho
                        $update_query = "UPDATE carts SET prod_qty = '$prod_qty' WHERE prod_id = '$prod_id' AND user_id = '$user_id'";
                        $update_query_run = mysqli_query($con, $update_query);
                        
                        if ($update_query_run) {
                            echo '<div class="alert alert-success alert-dismissible mt2"><strong>Cập nhật số lượng thành công</strong></div>';
                        } else {
                            echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Cập nhật số lượng không thành công</strong></div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Đã xảy ra sự cố!</strong></div>';
                    }
                }
                break;
            case "delete":
                $cart_id = $_POST['cart_id'];
                $user_id = $_SESSION['auth_user']['user_id'];
                $check_existing_cart = "SELECT * FROM carts WHERE id = '$cart_id' AND user_id = '$user_id'";
                $check_existing_cart_run = mysqli_query($con, $check_existing_cart);

                if (mysqli_num_rows($check_existing_cart_run) > 0) {
                    $delete_query = "DELETE FROM carts WHERE id ='$cart_id' ";
                    $delete_query_run = mysqli_query($con, $delete_query);
                    if ($delete_query_run) {
                        echo '<div class="alert alert-success alert-dismissible mt2"><strong>Xóa sản phẩm thành công</strong></div>';
                    } else {
                        echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Xóa sản phẩm không thành công</strong></div>';
                    }
                } else {
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Đã xảy ra sự cố!</strong></div>';
                }
                break;
            case "wishlist": // thêm vào wishlist
                $prodid = $_POST['prod_id'];
                $user_id = $_SESSION['auth_user']['user_id'];

                // Kiểm tra xem sản phẩm đã có trong wishlist chưa
                $check_existing_wishlist = "SELECT * FROM wishlist WHERE prod_id = '$prodid' AND user_id = '$user_id'";
                $check_existing_wishlist_run = mysqli_query($con, $check_existing_wishlist);

                if (mysqli_num_rows($check_existing_wishlist_run) > 0) {
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong> Sản phẩm đã có trong Yêu thích </strong></div>';
                } else {
                    // Thêm sản phẩm vào wishlist
                    $insert_wishlist_query = "INSERT INTO wishlist (user_id, prod_id) VALUES ('$user_id', '$prodid')";
                    $insert_wishlist_query_run = mysqli_query($con, $insert_wishlist_query);

                    if ($insert_wishlist_query_run) {
                        echo '<div class="alert alert-success alert-dismissible mt2"><strong> Đã thêm sản phẩm vào Yêu thích </strong></div>';
                    } else {
                        echo '<div class="alert alert-danger alert-dismissible mt2"><strong> Thêm sản phẩm vào Yêu thích bị lỗi</strong></div>';
                    }
                }
                break;
            case "delete_wishlist": // xóa khỏi wishlist
                $wishlist_id = $_POST['wishlist_id'];
                $user_id = $_SESSION['auth_user']['user_id'];

                // Kiểm tra xem sản phẩm có trong wishlist không
                $check_existing_wishlist = "SELECT * FROM wishlist WHERE id = '$wishlist_id' AND user_id = '$user_id'";
                $check_existing_wishlist_run = mysqli_query($con, $check_existing_wishlist);

                if (mysqli_num_rows($check_existing_wishlist_run) > 0) {
                    // Xóa sản phẩm khỏi wishlist
                    $delete_wishlist_query = "DELETE FROM wishlist WHERE id = '$wishlist_id' AND user_id = '$user_id'";
                    $delete_wishlist_query_run = mysqli_query($con, $delete_wishlist_query);

                    if ($delete_wishlist_query_run) {
                        // echo "123";
                       echo '<div class="alert alert-success alert-dismissible mt2 fw-bold">Sản phẩm đã bị xóa khỏi Yêu thích<strong></strong></div>';
                    } else {
                        echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Xóa sản phẩm khỏi Yêu thích bị lỗi</strong></div>';
                    }
                } else {
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Sản phẩm không tồn tại trong Yêu thích</strong></div>';
                }
                break;


            default:
                echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Đã xảy ra sự cố1!</strong></div>';
                break;
            }
    }
     else {
        switch ($scope) {
            case "add-buynow":
                $prodid = $_POST['prodid']; // Lấy prodid từ form
                $prodqty = $_POST['prodqty']; // Lấy prodqty từ form
            
                // Kiểm tra số lượng tồn kho
                $check_quantity = "SELECT quantity FROM product WHERE id = '$prodid'";
                $check_quantity_run = mysqli_query($con, $check_quantity);
                $row = mysqli_fetch_assoc($check_quantity_run);
                $quantity = $row['quantity'];
                
                if($quantity == 0){
                    echo json_encode(['status' => 'error', 'message' => '<div class=""><strong>Xin lỗi! Sản phẩm này đã hết hàng.</strong></div>']);
                }
                elseif($prodqty > $quantity) {
                    echo json_encode(['status' => 'error', 'message' => '<div class=""><strong> Xin lỗi! Số lượng sản này chỉ còn: ' . $quantity . ' sản phẩm. </strong></div>']);
                } else {
                    // Khởi tạo giỏ hàng trong session nếu chưa tồn tại
                    if (!isset($_SESSION['guest_cart'])) {
                        $_SESSION['guest_cart'] = [];
                    }
                    $cart_id = mt_rand(100000, 999999); 
                    // Kiểm tra xem sản phẩm đã có trong giỏ hàng hay chưa
                    if (isset($_SESSION['guest_cart'][$prodid])) {
                        // Nếu sản phẩm đã tồn tại, lấy số lượng hiện tại và cộng thêm số lượng mới
                        $current_qty = $_SESSION['guest_cart'][$prodid]['prod_qty'];
                        $new_qty = $current_qty + $prodqty;
                        // Kiểm tra tổng số lượng không vượt quá tồn kho
                        if ($new_qty > $quantity) {
                            echo json_encode(['status' => 'error', 'message' => '<div class=""><strong> Xin lỗi! Số lượng sản này chỉ còn: '.$quantity - $current_qty.' sản phẩm.</strong></div>']);
                         } else{
                            // Cập nhật số lượng mới trong giỏ hàng
                            $_SESSION['guest_cart'][$prodid]['prod_qty'] = $new_qty;
                            echo json_encode(['status' => 'success', 'redirect' => 'cart.php']);
                        }
                    } else {
                        // Thêm sản phẩm mới vào giỏ hàng nếu chưa tồn tại
                        $_SESSION['guest_cart'][$prodid] = [
                            'cart_id' =>$cart_id,
                            'prod_id' => $prodid,
                            'prod_qty' => $prodqty
                        ];
                        echo json_encode(['status' => 'success', 'redirect' => 'cart.php']);
                    }
                }
                break;

            case "add": // Thêm vào giỏ hàng khách vãng lai
                $prodid = $_POST['prodid']; // Lấy prodid từ form
                $prodqty = $_POST['prodqty']; // Lấy prodqty từ form
            
                // Kiểm tra số lượng tồn kho
                $check_quantity = "SELECT quantity FROM product WHERE id = '$prodid'";
                $check_quantity_run = mysqli_query($con, $check_quantity);
                $row = mysqli_fetch_assoc($check_quantity_run);
                $quantity = $row['quantity'];
                
                if($quantity == 0){
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong> Xin lỗi!  Sản phẩm này đã hết hàng.</strong></div>';
                }
                elseif($prodqty > $quantity) {
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong> Xin lỗi! Số lượng sản phẩm này chỉ còn: ' . $quantity . ' sản phẩm.</strong></div>';
                } else {
                    // Khởi tạo giỏ hàng trong session nếu chưa tồn tại
                    if (!isset($_SESSION['guest_cart'])) {
                        $_SESSION['guest_cart'] = [];
                    }
                    $cart_id = mt_rand(100000, 999999); 
                    // Kiểm tra xem sản phẩm đã có trong giỏ hàng hay chưa
                    if (isset($_SESSION['guest_cart'][$prodid])) {
                        // Nếu sản phẩm đã tồn tại, lấy số lượng hiện tại và cộng thêm số lượng mới
                        $current_qty = $_SESSION['guest_cart'][$prodid]['prod_qty'];
                        $new_qty = $current_qty + $prodqty;
                        // Kiểm tra tổng số lượng không vượt quá tồn kho
                        if($quantity - $current_qty==0){
                            echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Xin lỗi! Sản phẩm này đã hết hàng.</strong></div>';
                        }elseif ($new_qty > $quantity) {
                            echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Xin lỗi! Số lượng sản phẩm này chỉ còn: ' . $quantity - $current_qty . ' sản phẩm.</strong></div>';
                        } else{
                            // Cập nhật số lượng mới trong giỏ hàng
                            $_SESSION['guest_cart'][$prodid]['prod_qty'] = $new_qty;
                            echo '<div class="alert alert-success alert-dismissible mt2"><strong>Cập nhật số lượng sản phẩm trong giỏ hàng thành công</strong></div>';
                        }
                    } else {
                        // Thêm sản phẩm mới vào giỏ hàng nếu chưa tồn tại
                        $_SESSION['guest_cart'][$prodid] = [
                            'cart_id' =>$cart_id,
                            'prod_id' => $prodid,
                            'prod_qty' => $prodqty
                        ];
                        echo '<div class="alert alert-success alert-dismissible mt2"><strong>Đã thêm sản phẩm vào giỏ hàng</strong></div>';
                    }
                }
                break;
            case "update": // Cập nhật giỏ hàng khách vãng lai
                 // Lấy thông tin từ POST
                $prodid = $_POST['prod_id']; // ID sản phẩm
                $prodqty = $_POST['prod_qty']; // Số lượng sản phẩm mới
                $cart_id = $_POST['cart_id']; // ID giỏ hàng (cart_id)

                // Lấy số lượng tồn kho của sản phẩm
                $check_quantity = "SELECT quantity FROM product WHERE id = '$prodid'";
                $check_quantity_run = mysqli_query($con, $check_quantity);
                $row = mysqli_fetch_assoc($check_quantity_run);
                $quantity_in_stock = $row['quantity']; // Số lượng tồn kho

                // Kiểm tra số lượng tồn kho
                if ($prodqty > $quantity_in_stock) {
                    // Nếu số lượng yêu cầu lớn hơn số lượng tồn kho
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Xin lỗi! Số lượng sản phẩm này chỉ còn: ' . $quantity_in_stock . ' sản phẩm.</strong></div>';
                } else {
                    // Kiểm tra xem sản phẩm có trong giỏ hàng không
                    if (isset($_SESSION['guest_cart'][$prodid])) {
                        // Cập nhật số lượng sản phẩm trong giỏ hàng
                        $_SESSION['guest_cart'][$prodid]['prod_qty'] = $prodqty;
                        echo '<div class="alert alert-success alert-dismissible mt2"><strong>Cập nhật số lượng thành công </strong></div>';
                    } else {
                        echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Sản phẩm không tồn tại trong giỏ hàng</strong></div>';
                    }
                }
                break;
            case "delete": // Xóa sản phẩm khỏi giỏ hàng khách vãng lai
                $prodid = $_POST['prod_id']; 
        
                if (isset($_SESSION['guest_cart'][$prodid])) {
                    unset($_SESSION['guest_cart'][$prodid]);
                    echo '<div class="alert alert-success alert-dismissible mt2"><strong>Xóa sản phẩm thành công</strong></div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Đã xảy ra sự cố!</strong></div>';
                }
                break;
            default:
                echo '<div class="alert alert-danger alert-dismissible mt2"><strong>Đã xảy ra sự cố!</strong></div>';
                break;
        }
    }
}
?>