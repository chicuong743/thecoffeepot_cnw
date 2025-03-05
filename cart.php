<?php
// session_start();
include('function/userfunctions.php');
include('includes/header.php');
include('function/handlecart.php')

//  ?>

<div class="py-3 bg-primary">
    <div class="container">
        <h6 class="text-white ">
            <a class="text-white" href="index.php">
                Home /
</a>
            <a class="text-white" href="cart.php">
            Giỏ hàng
</a>
        </h6>
    </div>
</div>
<div class="py-5">
    <div class="container">
        <div class="card card-body shadow">
            <div id="message"></div>
            <div class="row">
                <div class="col-md-12">
                    <div id="mycart">
                    <?php
                    $items = getCartItems();
                    // Nếu là người dùng đã đăng nhập, kiểm tra số hàng trong cơ sở dữ liệu
                    if (is_object($items) && $items->num_rows > 0)  { ?>
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <h5>Hình ảnh</h5>
                            </div>
                            <div class="col-md-3">
                                <h5>Tên Sản phẩm</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>Đơn Giá (VNĐ)</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>Số lượng</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>Thành tiền</h5>
                            </div>
                            <div class="col-md-1">
                                <h5>Tác vụ</h5>
                            </div>
                        </div>
                        <div>
                        <?php foreach ($items as $citem) { ?>
                            <div class="card product_data shadow mb-2">
                                <div class="row align-items-center mt-3">
                                    <div class="col-md-2 ">
                                        <img class="mb-3" style="margin-left: 1rem" src="uploads/<?= $citem['image'] ?>" alt="image" width="80px" height="80px">
                                    </div>
                                    <div class="col-md-3  position-relative">
                                        <h5><?= $citem['productName']?></h5>
                                        <?php if($citem['sale'] > 0){ ?>
                                                <div class="sale-badge-cart position-absolute" >Sale <?=$citem['sale']?>%</div>
                                        <?php }?>
                                    </div>
                                    <div class="col-md-2 d-flex">
                                        <?php if($citem['sale'] > 0){ ?>
                                            <h5><?= number_format($citem['price']-($citem['price']*$citem['sale']/100), 0, ',', '.') ?></h5>
                                            <p class="ms-1 fs-6 text-decoration-line-through align-items-end opacity"><?= number_format($citem['price'], 0, ',', '.') ?></p>
                                        <?php } else{?>
                                            <h5><?= number_format($citem['price'], 0, ',', '.') ?></h5>
                                        <?php }?>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" class="prodId" value="<?= $citem['product_id'] ?>">
                                        <div class="input-group mb-3" style="width:130px">
                                            <button class="input-group-text update_qty tru_btn">-</button>
                                            <input type="text" class="form-control text-center input-qty bg-white" value="<?= $citem['prod_qty'] ?>" disabled>
                                            <button class="input-group-text update_qty cong_btn">+</button> 
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <h5>
                                            <?= number_format($citem['prod_qty'] * ($citem['price']-($citem['price']*$citem['sale']/100)), 0, ',', '.') ?>
                                        </h5>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger mb-3 deleteItem" value="<?= $citem['cart_id'] ?>">Xóa</button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <div class="float-start">
                            <a href="category.php" class="btn btn-outline-danger mt-3" style="padding: 6px 40px;">Tiếp tục mua hàng</a>
                        </div>
                        <div class="float-end">
                            <a href="checkout.php" class="btn btn-outline-primary mt-3" style="padding: 6px 40px;">Thanh Toán</a>
                        </div>
                    <?php }elseif(isset($_SESSION['auth_user']['user_id'])){ ?>
                        <div class="card card-body text-center shadow">
                            <h4 class="py-3">Giỏ hàng trống!</h4>
                        </div>
                    <?php }elseif(!empty($items)) {    ?>
                    <!--giỏ hàng của khách hàng vãng lai-->
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <h5>Hình ảnh</h5>
                        </div>
                        <div class="col-md-3">
                            <h5>Tên Sản phẩm</h5>
                        </div>
                        <div class="col-md-2">
                            <h5>Đơn Giá (VNĐ)</h5>
                        </div>
                        <div class="col-md-2">
                            <h5>Số lượng</h5>
                        </div>
                        <div class="col-md-2">
                            <h5>Thành tiền</h5>
                        </div>
                        <div class="col-md-1">
                            <h5>Tác vụ</h5>
                        </div>
                    </div>
                    <?php foreach ($items as $citem) { $cart_id = $citem['cart_id']; ?>
                    <div class="card product_data shadow mb-2">
                        <div class="row align-items-center mt-3">
                            <div class="col-md-2 ">
                                <img class="mb-3" style="margin-left: 1rem" src="uploads/<?= $citem['image'] ?>" alt="image" width="80px" height="80px">
                            </div>
                            <div class="col-md-3  position-relative">
                                <h5><?= $citem['productName']?></h5>
                                <?php if($citem['sale'] > 0){ ?>
                                        <div class="sale-badge-cart position-absolute" >Sale <?=$citem['sale']?>%</div>
                                <?php }?>
                            </div>
                            <div class="col-md-2 d-flex">
                                <?php if($citem['sale'] > 0){ ?>
                                    <h5><?= number_format($citem['price']-($citem['price']*$citem['sale']/100), 0, ',', '.') ?></h5>
                                    <p class="ms-1 fs-6 text-decoration-line-through align-items-end opacity"><?= number_format($citem['price'], 0, ',', '.') ?></p>
                                    <?php } else{?>
                                        <h5><?= number_format($citem['price'], 0, ',', '.') ?></h5>
                                    <?php }?>
                                </div>
                            <div class="col-md-2">
                                <input type="hidden" class="prodId" value="<?= $citem['prod_id'] ?>">
                                <div class="input-group mb-3" style="width:130px">
                                    <button class="input-group-text update_qty tru_btn">-</button>
                                        <input type="text" class="form-control text-center input-qty bg-white" value="<?= $citem['prod_qty'] ?>" disabled>
                                    <button class="input-group-text update_qty cong_btn" >+</button> 
                                </div>
                            </div>
                            <div class="col-md-2">
                                <h5>
                                    <?= number_format($citem['prod_qty'] * ($citem['price']-($citem['price']*$citem['sale']/100)), 0, ',', '.') ?>
                                </h5>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-danger mb-3 deleteItem" value="<?= $cart_id ?>">Xóa</button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="float-start">
                        <a href="category.php" class="btn btn-outline-danger mt-3" style="padding: 6px 40px;">Tiếp tục mua hàng</a>
                    </div>
                    <div class="float-end">
                        <a href="checkout.php" class="btn btn-outline-primary mt-3" style="padding: 6px 40px;">Thanh Toán</a>
                    </div>
                    <?php } else { ?>
                        <div class="card card-body text-center shadow">
                            <h4 class="py-3">Giỏ hàng trống!</h4>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>