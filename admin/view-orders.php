<?php

include('includes/header.php');
include('../middleware/adminMiddleware.php');



if (isset($_GET['t'])) {
    $tracking_no = $_GET['t'];

    $orderData = checkTrackingNoValid($tracking_no);
    if (mysqli_num_rows($orderData) < 0) {
        ?>
        <h4>Mã đơn hàng không tồn tại!</h4>
        <?php
        die();
    }

} else {
    ?>
    <h4>Mã đơn hàng không tồn tại!</h4>
    <?php
    die();
}
$data = mysqli_fetch_array($orderData);
?>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <span class="text-white fs-3">Chi tiết đơn hàng</span>

                    <a href="orders.php" class="btn btn-warning float-end"><i class="fa fa-reply"></i> Trở về</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <h5>Thông tin giao hàng</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="fw-bold">Tên </label>
                                    <div class="border p-1">
                                        <?= $data['name'] ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="fw-bold">Email </label>
                                    <div class="border p-1">
                                        <?= $data['email'] ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="fw-bold">Số điện thoại </label>
                                    <div class="border p-1">
                                        <?= $data['phone'] ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="fw-bold">Mã đơn hàng</label>
                                    <div class="border p-1">
                                        <?= $data['tracking_no'] ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="fw-bold">Địa chỉ giao hàng</label></label>
                                    <div class="border p-1">
                                        <span><?= $data['address'] ?>, <?= $data['ward'] ?>, <?= $data['district'] ?>, <?= $data['city'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <h5>Thông tin đơn hàng</h5>
                            <hr>
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Hình ảnh</th>
                                        <th>Số lượng</th>
                                        <th>Giá (VNĐ)</th>

                                    </tr>
                                </thead>
                                <tbody>


                                    <?php
                                    $user_id = $_SESSION['auth_user']['user_id'];
                                    // $order_query = "SELECT o.id as oid, o.tracking_no, o.user_id, oi.*, p.* FROM orders o, order_items oi, product p WHERE o.user_id = '$user_id' AND oi.order_id = o.id AND p.id =oi.prod_id AND o.tracking_no = '$tracking_no'";
                                    $order_query = "SELECT o.id as oid, o.tracking_no, o.user_id, oi.price as price_detail, oi.*, p.* FROM orders o, order_items oi, product p WHERE oi.order_id = o.id AND p.id =oi.prod_id AND o.tracking_no = '$tracking_no'";
                                    $order_query_run = mysqli_query($con, $order_query);

                                    if (mysqli_num_rows($order_query_run) > 0) {
                                        foreach ($order_query_run as $item) {
                                            ?>
                                            <tr class="table-group-divider">
                                                <td class="align-middle">
                                                    <?= $item['productName'] ?>
                                                </td>
                                                <td class="align-middle">
                                                    <img src="../uploads/<?= $item['image']; ?>" width="50px" height="50px"
                                                        alt="">
                                                </td>
                                                <td class="align-middle">
                                                    <?= $item['qty'] ?>
                                                </td>
                                                <td class="align-middle">
                                                <?= number_format($item['price_detail'], 0, ',', '.') ?> 
                                                    <!-- <?= $item['price'] ?>  -->
                                                </td>
                                            </tr>
                                            <?php

                                        }
                                    }

                                    ?>
                                </tbody>
                            </table>
                          <?php 
                              $id_ship = $data['shipping'];
                              $query = "SELECT price FROM shipping_unit WHERE id = $id_ship";
                              $result = mysqli_query($con, $query); 
                              if (mysqli_num_rows($result) > 0) {
                                  while ($item = mysqli_fetch_assoc($result)) { ?>
                            <h5>Phí vận chuyển: <span class="float-end fw-normal"><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</span></h5><hr>
                            <?php
                                    }
                                }?>
                            <h4>Tổng giá : <span class="float-end fw-bold">
                            <?= number_format($data['total_price'], 0, ',', '.') ?> VNĐ
                                    <!-- <?= $data['total_price']; ?> VNĐ -->
                                </span></h4>
                            <hr>
                            <div class="border p-1 mb-3 fw-bold">
                                <label for="">Phương thức thanh toán: </label>
                                <span class="badge bg-success">
                                    <?= $data['payment_mode'] ?> 
                                </span>
                            </div>
                            <div class="border p-1 mb-3 fw-bold">
                                <label for="">Hình thức vận chuyển: </label>
                                <?php 
                                $id_ship = $data['shipping'];
                                $query = "SELECT name_ship FROM shipping_unit WHERE id = $id_ship";
                                $result = mysqli_query($con, $query); 
                                if (mysqli_num_rows($result) > 0) {
                                    while ($item = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <span class="badge bg-danger">
                                            <?=$item['name_ship']?> 
                                        </span>
                                        <?php
                                    }
                                } else {
                                    echo "Mặc Định";
                                }
                                ?>
                            </div>
                            <div class="border p-1 mb-3 fw-bold">
                                <label for="">Trạng thái đơn hàng:</label>
                                <?php if ($data['status'] == 0) { ?>
                                    <span class="badge bg-warning text-dark">Đang chờ xử lý</span>
                                <?php } else if ($data['status'] == 1) { ?>
                                        <span class="badge bg-success">Đã giao thành công</span>
                                <?php } else if ($data['status'] == 2) { ?>
                                            <span class="badge bg-danger">Hủy đơn hàng</span>
                                <?php } ?>
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="tracking_no" value="<?= $data['tracking_no'] ?>">
                                    <select name="order_status" class="form-select custom-select">
                                        <option value="0" <?= $data['status'] == 0 ? "selected" : "" ?>>Đang chờ xử lý
                                        </option>
                                        <option value="1" <?= $data['status'] == 1 ? "selected" : "" ?>>Đã giao thành công
                                        </option>
                                        <option value="2" <?= $data['status'] == 2 ? "selected" : "" ?>>Hủy đơn hàng
                                        </option>
                                    </select>
                                    <button type="" name="update_order_btn" class="btn btn-primary mt-2">Cập nhật đơn hàng</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>


<?php include('includes/footer.php'); ?>