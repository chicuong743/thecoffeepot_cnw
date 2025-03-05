<?php
// session_start();
include('function/userfunctions.php');
include('includes/header.php');

include('authenticate.php');

// include('function/handlecart.php') ?>

<div class="py-3 bg-primary">
    <div class="container">
        <h6 class="text-white ">
            <a class="text-white" href="index.php">
                Home /
            </a>
            <a class="text-white" href="wishlist.php">
                Yêu thích
            </a>
        </h6>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="col-md-12">
            <div class="text-center">
                <h4> Sản phẩm bạn đã yêu thích</h4>
            </div>
            <div id="message" class="message"></div>

            <hr>
            <div class="row" id="wishlist">

                <?php

                $wishlist = getWishlistItems();
                if (mysqli_num_rows($wishlist) > 0) {
                    foreach ($wishlist as $item) {

                        ?>
                        <div class="col-md-3 mb-2">

                            <div class="card shadow category-card ">

                                <div class="card-body">
                                    <div class="text-center wishlist bg-danger">
                                    <i class="text-white fas fa-trash"></i>
                                    <button class="delete-icon delete-wishlist fw-bold text-white " value="<?= $item['id'] ?>">XÓA</button>
                                    </div>
                                    
                                    <a href="product-view.php?productid=<?= $item['prod_id'] ?>">
                                        <img src="uploads/<?= $item['image'] ?>" alt="" class="w-100 " style='height: 160px;'>
                                        <h4 class="text-dark">
                                            <?= $item['productName']; ?>
                                        </h4>
                                        <p class="text-danger">
                                            <?= number_format($item['price'], 0, ',', '.') ?> VNĐ
                                            <p>
                                    </a>

                                </div>
                            </div>

                        </div>

                        <?php

                    }
                } else {
                    ?>
                    <div class=" text-center " style=" height: 100vh;">
                        <h4 class=" text-info fs-2 fw-bold">Danh mục không có sản phẩm!</h4>
                    </div>
                    <?php
                    // echo "Danh mục không có sản phẩm";
                }

                ?>


            </div>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>