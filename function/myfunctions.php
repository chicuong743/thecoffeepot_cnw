<?php

// session_start();
include('config/dbcon.php');
ob_start();
function getAll($table)
{
    global $con;
    $query = "Select * from $table ";
    return $query_run = mysqli_query($con, $query);
}
function getByID($table, $id)
{
    global $con;
    $query = "Select * from $table where id='$id' ";
    return $query_run = mysqli_query($con, $query);
}
function getAllOrders()
{
    global $con;
    // $query = "SELECT o.*, u.name FROM orders o,users u where status='0' AND o.user_id = u.id_user ";
    $query = "SELECT * FROM orders WHERE status = '0'";
    return $query_run = mysqli_query($con, $query);
}
function getOrdersHistory0()
{//đơn hàng mới 
    global $con;
    $query = "SELECT * FROM orders WHERE status != '1' AND status != '2'";
    return $query_run = mysqli_query($con, $query);
}
function getOrdersHistory()
{//đã giao
    global $con;
    // $query = "SELECT o.*, u.name FROM orders o,users u where status='0' AND o.user_id = u.id_user ";
    $query = "SELECT * FROM orders WHERE status != '0' AND status != '2'";
    return $query_run = mysqli_query($con, $query);
}
function getOrdersHistory1()
{//đã hủy
    global $con;
    // $query = "SELECT o.*, u.name FROM orders o,users u where status='0' AND o.user_id = u.id_user ";
    $query = "SELECT * FROM orders WHERE status != '0' AND status != '1'";
    return $query_run = mysqli_query($con, $query);
}
function getNumberOrderHistory0() {
    global $con;
    $query = "SELECT COUNT(*) AS new_orders FROM orders WHERE status = 0";
    $result = mysqli_query($con, $query);
    $ordernew = 0;
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $ordernew = $row['new_orders']; 
    }
    return $ordernew;
}


//function để thông báo 
function redirect($url, $message)
{
    $_SESSION['message'] = $message;
    header('Location:' .$url);
    ob_end_flush();
    exit(0);
}
function checkTrackingNoValid($tracking_no)
{
    global $con;
    $query = "SELECT * FROM orders WHERE tracking_no = '$tracking_no' ";
    return $query_run = mysqli_query($con, $query);
}

function checkMessage($id){
    global $con;
    $query = "SELECT * FROM helpper WHERE id = '$id'";
    return $query_run = mysqli_query($con, $query);
}
?>