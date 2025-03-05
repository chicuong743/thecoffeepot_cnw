<?php 
    $host ="localhost";
    $username ="root";
    $password ="";
    $database ="thecoffeepot";

    // tạo connect với database
    $con = mysqli_connect($host,$username,$password,$database);

    //kiem tra connect

    if(!$con){
        die("Kết nối db thất bại!".mysqli_connect_error());

    }else{
        // echo "Kết nối db thành công!";
    }

?>
