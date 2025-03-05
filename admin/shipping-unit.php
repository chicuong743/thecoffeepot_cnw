<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Đơn vị vận chuyển
                    <a href="add-shipping-unit.php" class="btn btn-info float-end">Thêm đơn vị vận chuyển</a>
                    </h4>
                </div>
             
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>

                                <!-- <th>ID</th> -->
                                <th>Đơn vị vận chuyển</th>
                                <th>Giá (VNĐ)</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $shipping = getAll("shipping_unit");
                            if (mysqli_num_rows($shipping) > 0) {
                                foreach ($shipping as $item) {
                                    ?>
                                    <tr>
                                        <!-- <td>
                                            <?= $item['id'] ?>
                                        </td> -->
                                        <td>
                                            <?= $item['name_ship'] ?>
                                        </td>
                                        <td>
                                        <?= number_format($item['price'], 0, ',', '.') ?> 
                                        </td>
                                      
                                        <td>
                                            <a href="edit-shipping-unit.php?id=<?= $item['id']; ?>" class="btn btn-primary">Sửa</a>
                                           <form action="process.php" method="POST">
                                                <input type="hidden" name="ship_id" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn btn-danger" name="delete_shipping">Xóa</button>
                                           </form>
                                        </td>

                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "Không có danh mục";
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include('includes/footer.php');
?>