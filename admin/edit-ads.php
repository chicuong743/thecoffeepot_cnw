<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch ad data based on the provided ID
    $ad_query = "SELECT * FROM ads WHERE id = '$id'";
    $ad_result = mysqli_query($con, $ad_query);
    
    if (mysqli_num_rows($ad_result) > 0) {
        $ad_data = mysqli_fetch_assoc($ad_result);
    } else {
        echo "Không tìm thấy quảng cáo!";
        exit;
    }
} else {
    echo "ID quảng cáo không hợp lệ!";
    exit;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Sửa ADS
                    <a href="ads.php" class="btn btn-warning float-end"><i class="fa fa-reply"></i> Trở về</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <input type="hidden" name="ad_id" value="<?= $ad_data['id'] ?>">
                            
                            <div class="col-md-12">
                                <label for="">Tên Quảng Cáo</label>
                                <input type="text" class="form-control mb-2" required name="name_ads" value="<?= htmlspecialchars($ad_data['name_ads']) ?>" placeholder="Nhập vào tên quảng cáo...">
                            </div>

                            <div class="col-md-12">
                                <label for="">Mô Tả Quảng Cáo</label>
                                <?php
                                // Check if 'description' key exists in the $ad_data array before using it
                                if (isset($ad_data['content_ads'])) {
                                    $description = $ad_data['content_ads'];
                                } else {
                                    $description = ''; // Default to empty string if 'description' key is not found
                                }
                                ?>
                                <textarea name="content_ads" class="form-control mb-2" required placeholder="Mô tả nội dung ADS..." rows="5"><?= htmlspecialchars($description) ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="type_ads">Loại ADS</label>
                                <select name="type_ads" class="form-select mb-2" required>
                                    <option value="Bài Viết" <?= isset($ad_data['type_ads']) && $ad_data['type_ads'] == 'Bài Viết' ? 'selected' : '' ?>>Bài Viết</option>
                                    <option value="Banner Đầu Trang" <?= isset($ad_data['type_ads']) && $ad_data['type_ads'] == 'Banner Đầu Trang' ? 'selected' : '' ?>>Banner Đầu Trang</option>
                                    <option value="Banner Sale" <?= isset($ad_data['type_ads']) && $ad_data['type_ads'] == 'Banner Sale' ? 'selected' : '' ?>>Banner Sale</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="">Hình ảnh</label>
                                <input type="file" class="form-control mb-2" name="images">
                                <?php if (!empty($ad_data['images'])): ?>
                                    <img src="../uploads/<?= $ad_data['images'] ?>" alt="" height="50px" width="50px" class="mb-2">
                                <?php else: ?>
                                    <p>Không có hình ảnh cũ</p>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="">Trạng thái</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="statusCheckbox" name="status" value="1" <?= isset($ad_data['status']) && $ad_data['status'] == '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusCheckbox">Hiển thị</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" name="update_ads_btn">Cập nhật</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
