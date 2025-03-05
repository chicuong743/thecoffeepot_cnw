<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $shipping = getByID("shipping_unit", $id);

                if (mysqli_num_rows($shipping) > 0) {
                  
                        $data = mysqli_fetch_array($shipping);
                        ?>
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h4 class="text-white">Sửa phân loại</h4>
                        </div>
                        <div class="card-body">
                            <form action="process.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="ship_id" value="<?= $data['id'] ?>">
                                        <label for="">Tên phân loại</label>
                                        <input type="text" value="<?= $data['name_ship'] ?>" class="form-control mb-2" name="name_ship"
                                            placeholder="Nhập vào tên đơn vị vận chuyển..">
                                        <label for="">Giá</label>
                                        <input type="text" value="<?= $data['price'] ?>" class="form-control mb-2" name="price" placeholder="Nhập giá...">
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" name="edit_ship_btn"> Lưu</button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                }


            } else {
                echo "id không tồn tại!";
            }
            ?>
        </div>
    </div>
</div>


<?php
include('includes/footer.php');
?>