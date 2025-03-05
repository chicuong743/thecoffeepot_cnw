<?php
session_start();
include('config/dbcon.php');
include('../function/myfunctions.php');

if (isset($_POST['add_shipping_btn'])) {
    $name_ship = $_POST['name_ship'];
    $price = $_POST['price'];

    // Thêm kiểm tra nếu cần thiết, ví dụ: kiểm tra giá trị dương cho giá vận chuyển

    // Trước tiên, kiểm tra xem tên đơn vị vận chuyển đã tồn tại trong cơ sở dữ liệu chưa
    $check_query = "SELECT * FROM shipping_unit WHERE name_ship = '$name_ship'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Tên đơn vị vận chuyển đã tồn tại, xuất thông báo lỗi
        redirect("add-shipping-unit.php", "Đơn vị vận chuyển đã tồn tại");
    } else {
        // Tên đơn vị vận chuyển chưa tồn tại, thêm vào cơ sở dữ liệu
        $shipping_query = "INSERT INTO shipping_unit (name_ship, price) VALUES ('$name_ship', '$price')";
        $shipping_query_run = mysqli_query($con, $shipping_query);

        if ($shipping_query_run) {
            redirect("add-shipping-unit.php", "Thêm đơn vị vận chuyển thành công!");
        } else {
            redirect("add-shipping-unit.php", "Thêm đơn vị vận chuyển thất bại!");
        }
    }
}
if (isset($_POST["delete_shipping"])) {

    $ship_id = mysqli_real_escape_string($con, $_POST['ship_id']);

    $delete_query = "delete from shipping_unit where id = '$ship_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        redirect("shipping-unit.php", "Xóa đơn vị vận chuyển thành công");
    } else {
        redirect("shipping-unit.php", "Xóa đơn vị vận chuyển thất bại");
    }
}
if (isset($_POST['edit_ship_btn'])) {
    $name_ship = $_POST['name_ship'];
    $ship_id = $_POST['ship_id'];
    $price = $_POST['price'];


    //kiem tra ten thương hiệu

    $check_query = "select * from shipping_unit where name_ship = '$name_ship' AND price = '$price'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        redirect("edit-shipping-unit.php?id=$ship_id", "Tên đơn vị vận chuyển đã tồn tại");
    } else {
        $update_query = "update shipping_unit set name_ship = '$name_ship', price = '$price' where id = '$ship_id' ";
        $update_query_run = mysqli_query($con, $update_query);
        if ($update_query_run) {
            redirect("shipping-unit.php", "Cập nhật đơn vị vận chuyển thành công!");

        } else {
            redirect("edit-shipping-unit.php?id=$ship_id", "Cập nhật phân loại thất bại");
        }

    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy giá trị tuần từ biểu mẫu
    $selectedWeek = $_POST["week"];

    generateChart($selectedWeek);
    // header("Location: index.php");
    // exit();
    
}
function generateChart($week) {
    // Sử dụng biến toàn cục $con
    global $con;

    // Thực hiện câu truy vấn SQL để lấy dữ liệu cho tuần được chọn
    $query = "SELECT DAYNAME(created_at) as day_of_week, SUM(total_price) as daily_sales
              FROM orders
              WHERE WEEK(created_at) = $week AND YEAR(created_at) = YEAR(NOW())
              GROUP BY day_of_week
              ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";

    $result = $con->query($query);

    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

        // Thiết lập loại nội dung là JSON
        header('Content-Type: application/json');

        // Truyền dữ liệu về JavaScript
        echo json_encode($data);
}


?>