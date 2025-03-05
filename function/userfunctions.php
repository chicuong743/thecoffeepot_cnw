<?php
ob_start(); // Bắt đầu bộ đệm đầu ra
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../admin/config/dbcon.php');


function getAll($table)
{
    global $con;
    $query = "Select * from  $table ";
    return $query_run = mysqli_query($con, $query);
}
function getSlider(){
    global $con;
    $sql_banner_index = "SELECT * FROM ads WHERE type_ads = 'Banner Đầu Trang' AND status = 1"; 
    return $query_run = mysqli_query($con,$sql_banner_index);
}
function getSliderSale(){
    global $con;
    $sql_banner_index = "SELECT * FROM ads WHERE type_ads = 'Banner Sale' AND status = 1"; 
    return $query_run = mysqli_query($con,$sql_banner_index);
}
function getContent(){
    global $con;
    $sql_banner_index = "SELECT * FROM ads WHERE type_ads = 'Bài Viết' AND status = 1"; 
    return $query_run = mysqli_query($con,$sql_banner_index);
}

function redirect($url, $message)
{
    $_SESSION['message'] = $message;
    header('Location:' . $url);
    ob_end_flush();
    exit();
}
function getProductReviews($product_id) {
    global $con; // Assuming you have a database connection object $conn
    $query = "SELECT * FROM feedback WHERE id_product = '$product_id' AND status = 1 ORDER BY created_at DESC"; 
    return mysqli_query($con, $query);
}
function getProductRating($product_id){
    global $con;
    $dem = 0; 
    $totalrating = 0; 
    
    $query = "SELECT * FROM feedback WHERE id_product = '$product_id' AND status = 1"; 
    $result = mysqli_query($con, $query);
    if ($result) {
        // Duyệt qua từng dòng kết quả
        while ($row = mysqli_fetch_assoc($result)) {
            $dem++; 
            $totalrating += $row['rating'];
        }
        $average_rating = $dem > 0 ? round($totalrating / $dem, 1) : 5; // Tránh chia cho 0 nếu không có đánh giá
       
        return $average_rating;
    } 
}


function getID($table, $id)
{
    global $con;
    $query = "SELECT * FROM $table WHERE id = '$id'";
    return $query_run = mysqli_query($con, $query);
}
function getProductbyCid($table, $cid)
{
    global $con;
    $query = "SELECT * FROM $table WHERE catid = '$cid'";
    return $query_run = mysqli_query($con, $query);
}
function getProductByBrandId($brand_id)
{
    global $con;
    $query = "
        SELECT p.id, p.productName, p.catid, p.product_desc, p.image, 
        p.quantity, p.trending, p.price, p.sale 
        FROM product AS p
        INNER JOIN product_brands AS pb ON p.id = pb.product_id
        WHERE pb.brand_id = '$brand_id'
    ";
    return mysqli_query($con, $query);
}
function getProductByIdSale(){
    global $con;
    $query="SELECT * FROM product where sale > '0' ";
    return mysqli_query($con, $query);
}

// Hàm để lấy giỏ hàng cho người dùng đã đăng nhập hoặc khách vãng lai
function getCartItems() {
    global $con;
    // Kiểm tra người dùng đã đăng nhập
    if (isset($_SESSION['auth_user']['user_id'])) {
        $user_id = $_SESSION['auth_user']['user_id'];
        $query = "SELECT c.id AS cart_id, p.id AS product_id, p.productName, p.image, p.price,p.sale, c.prod_qty 
                  FROM carts c JOIN product p ON c.prod_id = p.id WHERE c.user_id = '$user_id'";
        return mysqli_query($con, $query);
    } else if (isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart'])) {
        $items = [];
        foreach ($_SESSION['guest_cart'] as $prod_id => $product) {
            if (isset($product['prod_id'])) {
                $prod_id = $product['prod_id'];
                $query = "SELECT productName, image, price,sale FROM product WHERE id = '$prod_id'";
                $result = mysqli_query($con, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $productData = mysqli_fetch_assoc($result);
                    $items[] = [
                        'cart_id' => $product['cart_id'],
                        'prod_id' => $prod_id,
                        'productName' => $productData['productName'],
                        'image' => $productData['image'],
                        'price' => $productData['price'],
                        'prod_qty' => $product['prod_qty'],
                        'sale'=>$productData['sale']
                    ];
                }
            }
        }
        return $items;
    }
    return []; // Trả về mảng rỗng nếu không có sản phẩm
}
function getWishlistItems() {
    global $con;
    $user_id = $_SESSION['auth_user']['user_id'];
    $query = "SELECT wishlist.*, product.image, product.productName , product.price
    FROM wishlist 
    JOIN product ON wishlist.prod_id = product.id 
    WHERE wishlist.user_id = $user_id";
    $result = mysqli_query($con, $query);
    return $result;
}
function getOrders()
{
    global $con;
    if (isset($_SESSION['auth_user']['user_id'])) {
    //getOrders của khách hàng đã đăng nhập.
    $user_id = $_SESSION['auth_user']['user_id'];

    $query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC";
    return $query_run = mysqli_query($con, $query);
    }else{
    //getOrders của khách vãng lai.

    }
}
# Kiểm tra xe nó gủi cái tracking_no sang có đúng không bên view_orders
function checkTrackingNoValid($tracking_no)
{
    global $con;
    $user_id = $_SESSION['auth_user']['user_id'];

    $query = "SELECT * FROM orders WHERE tracking_no = '$tracking_no' AND user_id = '$user_id' ";
    return $query_run = mysqli_query($con, $query);
}
function getAllTrending()
{
    global $con;
    $query = "SELECT * FROM product WHERE trending ='1' ";
    return $query_run = mysqli_query($con, $query);
}
function getByUID($table, $id)
{
    global $con;
    $query = "Select * from $table where id_user='$id' ";
    return $query_run = mysqli_query($con, $query);
}
?>