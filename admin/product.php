<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

// Get category ID from the URL
$cat_id = isset($_GET['catid']) ? $_GET['catid'] : null;

// Retrieve products based on the category ID
if ($cat_id !== null) {
    $products = getAll('product WHERE catid = ' . $cat_id);
} else {
    $products = getAll('product');
}

// Retrieve all categories for filter buttons
$categories = getAll('category');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Sản phẩm</h4>
                    <a href="add-product.php" class="btn btn-info float-end">+ Thêm sản phẩm</a>

                    <!-- Display category filter buttons -->
                    <?php if (mysqli_num_rows($categories) > 0): ?>
                        <?php foreach ($categories as $cat): ?>
                            <a href="product.php?catid=<?= $cat['id'] ?>" style="margin-right:5px;" class="btn btn-info float-end">
                                <?= $cat['name'] ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="card-body" id="product_table">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Giá (VNĐ)</th>
                                <th>Số lượng</th>
                                <th>Trạng thái</th>
                                <th>Nhận xét</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($products) > 0): ?>
                                <?php foreach ($products as $item): ?>
                                    <tr>
                                        <td><?= $item['productName'] ?></td>
                                        <td>
                                            <img src="../uploads/<?= $item['image'] ?>" width="50px" height="50px" alt="<?= $item['productName'] ?>">
                                        </td>
                                        <td><?= number_format($item['price'], 0, ',', '.') ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td><?= $item['trending'] == '1' ? "Nổi bật" : "Không nổi bật" ?></td>
                                        <td>
                                            <a href="list-feedback.php?id=<?= $item['id']; ?>" class="btn btn-primary">Xem</a>
                                        </td>
                                        <td>
                                            <a href="edit-product.php?id=<?= $item['id']; ?>" class="btn btn-primary">Sửa</a>
                                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                            <button type="button" class="btn btn-danger delete_product_btn" value="<?= $item['id'] ?>">Xóa</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Danh mục không có sản phẩm</td>
                                </tr>
                            <?php endif; ?>
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
