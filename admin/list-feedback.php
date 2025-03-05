<?php
// session_start();
include('includes/header.php');
include('../middleware/adminMiddleware.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = getByID("product", $id);
    if (mysqli_num_rows($product) > 0) {             
        $data = mysqli_fetch_array($product);
    } else {
        echo "<div class='alert alert-danger'>Không có sản phẩm mang id: $id</div>";
        exit;
    }

    // Truy vấn lấy danh sách đánh giá theo id_product, sắp xếp mới nhất
    $query = "SELECT * FROM feedback WHERE id_product = $id ORDER BY created_at DESC";
    $query_run = mysqli_query($con, $query);
} else {
    echo "<div class='alert alert-danger'>Không có Id</div>";
    exit;
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Đánh giá của sản phẩm: <?= htmlspecialchars($data['productName']) ?>
                        <a href="product.php" class="btn btn-warning btn-sm float-end">
                            <i class="fa fa-reply"></i> Trở về
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>Tên</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Nhận xét</th>
                                <th>Hình ảnh</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($feedback = mysqli_fetch_assoc($query_run)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($feedback['name']) ?>
                                            <div class="rating-stars">
                                                <?php
                                                $rating = $feedback['rating']; 
                                                if ($rating >= 1 && $rating <= 5) { 
                                                    echo str_repeat('<span style="color: gold;">&#9733;</span>', $rating);
                                                    echo str_repeat('<span style="color: #ccc;">&#9733;</span>', 5 - $rating);
                                                } else {
                                                    echo "<span class='text-muted'>Không có đánh giá sao</span>";
                                                }
                                                ?>
                                            </div>
                                        </td>


                                        <td><?= htmlspecialchars($feedback['phone']) ?></td>
                                        <td><?= htmlspecialchars($feedback['email']) ?></td>
                                        <td style="width: 100px; height: 50px; overflow-y: auto; overflow-x: hidden; white-space: nowrap;"><?= htmlspecialchars($feedback['note']) ?></td>
                                        <td class="text-center">
                                            <?php if ($feedback['image']) { ?>
                                                <img src=".<?= htmlspecialchars($feedback['image']) ?>" 
                                                     alt="Ảnh feedback" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px;">
                                            <?php } else { ?>
                                                <span class="text-muted">Không có hình ảnh</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <form method="POST" action="code.php" name="update_feedback" style="display: inline-block;">
                                                <input type="hidden" name="id" value="<?= $feedback['id'] ?>">
                                                <input type="hidden" name="update_feedback" value="1"> <!-- Thêm trường ẩn để nhận diện -->
                                                <input type="checkbox" 
                                                    name="status" 
                                                    value="1" 
                                                    class="status-toggle" 
                                                    onchange="this.form.submit()" 
                                                    <?= $feedback['status'] == 1 ? 'checked' : '' ?>>
                                            </form>
                                            <br>
                                            <span>
                                                <?= $feedback['status'] == 1 ? 'Hiển thị' : 'Ẩn' ?>
                                            </span>
                                        </td>

                                        <td><?= date('H:i - d/m/Y', strtotime($feedback['created_at'])) ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="8" class="text-center">Không có đánh giá nào cho sản phẩm này.</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>
