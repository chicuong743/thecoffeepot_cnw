<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>
        Admin Dashboard!
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <!-- <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" /> -->
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="assets/css/material-dashboard.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css">

    

    <!-- Alertify js -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .form-control {
            border: 1px solid #b3a1a1 !important;
            padding: 8px 10px;
        }

    </style>

</head>

<body class="g-sidenav-show  bg-gray-200">
    <?php
    include('sidebar.php');
    ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- <?php include('navbar.php');  ?>-->