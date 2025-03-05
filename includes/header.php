<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/loading.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=AR+One+Sans&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <!-- <link href="assets/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css" /> -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css' />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>The Coffee Pot</title>

    <style>
    a {
        text-decoration: none;
    }
    .opacity {
        opacity: 0.75; 
    }
    .owl-nav button.owl-prev {
        left: 10px; /* Vị trí nút Previous */
        font-size: 30px;
    }
    .owl-nav button.owl-next {
        right: 10px; /* Vị trí nút Next */
    }
    #brand-menu:hover {
        background-color: #6db4ff; 
        border-radius: 10px;
    }
    /* Style for the sale badge */
    .carousel-item img{
        z-index: -1;
        width: 100%;
        
    }
    .sale-badge-cart {
        top:-20px;
        left:-10px;
        color: red;
        font-size: 16px;
        font-weight: 600;
        font-style: italic; 
    }
    .sale-badge-product{
        z-index: 1000;
        position: absolute;
        top: 5px;
        left: 5px;
        padding: 3px 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: red;
        color: white;
        font-size: 15px;
        text-align: center;
    }
    .sale-badge-product-sale {
        z-index: 100;
        position: absolute;
        top: -5px;
        right: -10px;
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: red;
        color: white;
        font-size: 15px;
        font-weight: bold;
        text-align: center;
        line-height: 40px;
        border-radius: 50%;
        transform: translate(50%, -50%);
        animation: glow 1.5s infinite alternate; 
        box-shadow: 0 0 10px rgba(255, 0, 0, 0.5); 
    }
    .icon_sale img{
        z-index: 100;
        width: 100px;
    }
    .icon_sale {
        right:20px;
        top: -15px;
        transform: translate(50%, -50%) scale(1); 
        animation: scaleEffect 1s infinite alternate; 
    }
    #reviews-list, #feedback-form {
            transition: all 0.3s ease-in-out;
        }

    @keyframes scaleEffect {
        0% {
            transform: translate(50%, -50%) scale(1); /* Ban đầu là kích thước bình thường */
        }
        100% {
            transform: translate(50%, -50%) scale(1.2); /* Kích thước to hơn */
        }
    }
    /* Hiệu ứng chớp nháy cho viền ngoài */
    @keyframes glow {
        0% {
            opacity: 1; 
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.3), 
                        0 0 20px rgba(255, 0, 0, 0.2);
        }
        50% {
            opacity: 0; 
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.7), 
                        0 0 30px rgba(255, 0, 0, 0.5);
        }
        100% {
            opacity: 1; 
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.3), 
                        0 0 20px rgba(255, 0, 0, 0.2);
        }
    }


 

    </style>

</head>

<body>
    <?php include('includes/navbar.php'); ?>
    <?php include('includes/loading.php'); ?>