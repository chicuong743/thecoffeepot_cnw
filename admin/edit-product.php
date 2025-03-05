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
                $product = getByID("product", $id);

                if (mysqli_num_rows($product) > 0) {
                    $data = mysqli_fetch_array($product);
                    ?>
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h4 class="text-white">Sửa sản phẩm
                                <a href="product.php" class="btn btn-warning float-end"><i class="fa fa-reply"></i> Trở về</a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="code.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="product_id" value="<?= $data['id'] ?>">
                                    <div class="col-md-12">
                                        <label for="">Tên sản phẩm</label>
                                        <input type="text" class="form-control mb-2" required name="name"
                                            value="<?= $data['productName'] ?>" placeholder="Nhập vào tên sản phẩm...">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <textarea required name="desc" class="form-control mb-2" id="floatingTextarea2" style="height: 100px"><?= $data['product_desc'] ?></textarea>
                                            <label for="floatingTextarea2">Chi tiết sản phẩm</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Danh mục</label>
                                        <select class="form-select" name="catid">
                                            <option>Chọn danh mục</option>
                                            <?php
                                            $category = getAll("category");
                                            if (mysqli_num_rows($category) > 0) {
                                                foreach ($category as $item) {
                                                    $selected = ($data['catid'] == $item['id']) ? 'selected' : '';
                                                    echo "<option value='{$item['id']}' {$selected}>{$item['name']}</option>";
                                                }
                                            } else {
                                                echo "<option disabled>Danh mục trống</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="">Phân loại vị trí</label>
                                        <div class="checkbox-dropdown">
                                            <button type="button" class="form-control dropdown-btn">
                                                <span style="margin-right:230px;">Phân loại vị trí</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg>
                                            </button>
                                            <div class="checkbox-list">
                                                <?php
                                                $brand = getAll("brand");
                                                $selected_brands = mysqli_query($con, "SELECT brand_id FROM product_brands WHERE product_id='$id'");
                                                $selected_brand_ids = [];
                                                while ($row = mysqli_fetch_assoc($selected_brands)) {
                                                    $selected_brand_ids[] = $row['brand_id'];
                                                }

                                                if (mysqli_num_rows($brand) > 0) {
                                                    foreach ($brand as $item) {
                                                        $checked = in_array($item['id'], $selected_brand_ids) ? 'checked' : '';
                                                        echo "<label><input type='checkbox' name='brandid[]' value='{$item['id']}' {$checked}> {$item['name']}</label><br>";
                                                    }
                                                } else {
                                                    echo "<label><input type='checkbox' disabled> Thương hiệu trống</label>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="">Giảm giá (%)</label>
                                        <input type="number" min="0" max="100" value="<?= $data['sale'] ?>" class="form-control mb-2" required name="sale">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">Hình ảnh cũ</label>
                                        <input type="hidden" name="old_image" value="<?= $data['image'] ?>">
                                        <br>
                                        <img src="../uploads/<?= $data['image'] ?>" alt="" height="50px" width="50px" class="mb-2">
                                        <br>
                                        <label for="">Hình ảnh mới</label>
                                        <input type="file" class="form-control mb-2" name="image">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Giá (VNĐ)</label>
                                        <input type="number" value="<?= $data['price'] ?>" class="form-control mb-2" required name="price">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Số lượng</label>
                                        <input type="number" value="<?= $data['quantity'] ?>" class="form-control mb-2" required name="quantity">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Ưu tiên</label>
                                        <input type="checkbox" id="uutienCheckbox" name="trending" <?= $data['trending'] == '1' ? 'checked' : '' ?>>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" name="update_product_btn">Cập nhật</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                } else {
                    echo "Không tìm thấy sản phẩm!";
                }
            } else {
                echo "ID sản phẩm không hợp lệ!";
            }
            ?>

        </div>
    </div>
</div>
<?php
include('includes/footer.php');
?>
<script>
document.querySelector('.dropdown-btn').addEventListener('click', function() {
    const dropdown = document.querySelector('.checkbox-dropdown');
    dropdown.classList.toggle('active');
});

// Đóng dropdown khi người dùng nhấn ra ngoài
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.checkbox-dropdown');
    if (!dropdown.contains(event.target)) {
        dropdown.classList.remove('active');
    }
});

</script>