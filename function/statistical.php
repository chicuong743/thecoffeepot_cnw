<?php

include('config/dbcon.php');


function countOrder()
{

    global $con;

    $sql = "SELECT COUNT(*) as totalSold FROM orders";
    $result = mysqli_query($con, $sql);

    // Kiểm tra và trả về kết quả
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalSold'];
    } else {
        return 0;
    }
}

function countWait()
{

    global $con;

    $sql = "SELECT COUNT(*) as totalWait FROM orders WHERE status = 0 ";
    $result = mysqli_query($con, $sql);

    // Kiểm tra và trả về kết quả
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalWait'];
    } else {
        return 0;
    }

}

function countCanncel()
{

    global $con;

    $sql = "SELECT COUNT(*) as totalCan FROM orders WHERE status = 1 ";
    $result = mysqli_query($con, $sql);

    // Kiểm tra và trả về kết quả
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalCan'];
    } else {
        return 0;
    }

}

function countSuccess()
{

    global $con;

    $sql = "SELECT COUNT(*) as totalSucc FROM orders WHERE status = 2 ";
    $result = mysqli_query($con, $sql);

    // Kiểm tra và trả về kết quả
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalSucc'];
    } else {
        return 0;
    }

}

function gettotal(){
    global $con;
    $sql = "SELECT SUM(total_price) as total FROM orders";
    $result = $con->query($sql);

    if($result-> num_rows >0){
        $row = $result->fetch_assoc();
        $total = $row['total'];
    }
    return $total;

}

function gettotalwait(){
    global $con;
    $sql = "SELECT SUM(total_price) as totalwait FROM orders WHERE status = 0 ";
    $result = $con->query($sql);

    if($result-> num_rows >0){
        $row = $result->fetch_assoc();
        $totalwait = $row['totalwait'];
    }
    return $totalwait;

}
function gettotalCancel(){
    global $con;
    $sql = "SELECT SUM(total_price) as totalCancel FROM orders WHERE status = 1 ";
    $result = $con->query($sql);

    if($result-> num_rows >0){
        $row = $result->fetch_assoc();
        $totalCancel = $row['totalCancel'];
    }
    return $totalCancel;

}

function gettotalSucc(){
    global $con;
    $sql = "SELECT SUM(total_price) as totalSucc FROM orders WHERE status = 2 ";
    $result = $con->query($sql);

    if($result-> num_rows >0){
        $row = $result->fetch_assoc();
        $totalSucc = $row['totalSucc'];
    }
    return $totalSucc;

}

function sumAccount(){
    global $con;
    $sql = "SELECT SUM(total_price) as totalSucc FROM orders WHERE status = 2 ";
    $result = $con->query($sql);

    if($result-> num_rows >0){
        $row = $result->fetch_assoc();
        $totalSucc = $row['totalSucc'];
    }
    return $totalSucc;

}

function countAcc()
{

    global $con;

    $sql = "SELECT COUNT(*) as countAcc FROM users  WHERE type = 0 ";
    $result = mysqli_query($con, $sql);

    // Kiểm tra và trả về kết quả
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['countAcc'];
    } else {
        return 0;
    }

}

function getProductSales()
{
    global $con;

    $sql = "SELECT
                p.productName AS product_name,
                COALESCE(SUM(oi.qty), 0) AS total_sold
            FROM
                product p
            LEFT JOIN
                order_items oi ON p.id = oi.prod_id
            GROUP BY
                p.id, p.productName";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    } else {
        return array();
    }
}

function getLowStockProducts() {
    global $con; // Kết nối cơ sở dữ liệu
    $query = "SELECT id, productName, image, quantity, price FROM product WHERE quantity < 5";
    $result = mysqli_query($con, $query);

    $lowStockProducts = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $lowStockProducts[] = $row;
        }
    }
    return $lowStockProducts;
}

?>