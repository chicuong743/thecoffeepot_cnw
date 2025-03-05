<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

// Lấy tham số loại quảng cáo từ URL (nếu có)
$type_ads_filter = isset($_GET['type_ads']) ? $_GET['type_ads'] : '';

// Tạo câu truy vấn để lọc quảng cáo theo loại
if ($type_ads_filter == '') {
    // Nếu không có loại nào được chọn, lấy tất cả các quảng cáo
    $ads_query = "SELECT * FROM ads";
} else {
    // Nếu có loại được chọn, lọc theo loại đó
    $ads_query = "SELECT * FROM ads WHERE type_ads = '$type_ads_filter'";
}

// Thực thi truy vấn
$ads = mysqli_query($con, $ads_query);

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Quản lý bài đăng</h4>
                    <!-- Các nút điều hướng để lọc theo loại quảng cáo -->
                    <a href="add-ads.php" class="btn btn-info float-end ms-3">Thêm Bài ADS</a>
                    <a href="ads.php?type_ads=Banner%20Đầu%20Trang" class="btn btn-dark float-end ms-3">Danh sách Banner Đầu Trang</a>
                    <a href="ads.php?type_ads=Banner%20Sale" class="btn btn-danger float-end ms-3">Danh sách Banner Sale</a>
                    <a href="ads.php?type_ads=Bài%20Viết" class="btn btn-warning float-end ms-3">Danh sách Bài Viết</a>
                    <a href="ads.php" class="btn btn-success float-end ms-3">Tất Cả</a>
                </div>
                <div class="card-body" id="ads_table">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Tên ADS</th>
                                <th style="width: 10%;">Hình ảnh</th>
                                <th style="width: 30%;">Nội Dung</th>
                                <th style="width: 15%;">Thể loại</th>
                                <th style="width: 5%;">Trạng thái</th>
                                <th style="width: 20%;">Tùy chỉnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $product = getAll("ads");
                            if (mysqli_num_rows($ads) > 0) {
                                // Lặp qua các quảng cáo và hiển thị thông tin
                                foreach($ads as $item) {
                                    ?>
                                    <tr>
                                        <td style="width: 20%;"><?= substr($item['name_ads'], 0, 40)?></td>
                                        <td style="width: 10%;">
                                            <img src="../uploads/<?= $item['images'] ?>" width="50px" height="50px" alt="<?= $item['name_ads'] ?>">
                                        </td>
                                        <td style="width: 30%;"><?= substr($item['content_ads'], 0, 60)?>...</td>
                                        <td style="width: 15%;"><?= $item['type_ads'] ?></td>
                                        <td style="width: 5%;">
                                            <!-- Kiểm tra trạng thái -->
                                            <?php if ($item['status'] == '1') { ?>
                                                <span class="badge bg-success">Hiển thị</span>
                                            <?php } else { ?>
                                                <span class="badge bg-secondary">Ẩn</span>
                                            <?php } ?>
                                        </td>
                                        <td style="width: 20%;">
                                            <a href="edit-ads.php?id=<?= $item['id']; ?>" class="btn btn-primary">Sửa</a>
                                            <input type="hidden" name = ads_id value=<?= $item['id'] ?>>
                                            <button type="button" class="btn btn-danger delete_ads_btn" value="<?= $item['id'] ?>">Xóa</button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "Không có quảng cáo nào!";
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
<script>
$(document).ready(function () {
    // Xử lý nút xóa ads
    $(document).on('click', '.delete_ads_btn', function (e) {
        e.preventDefault();
        var id = $(this).val(); // Lấy ID của ads từ nút xóa

        // Hiển thị hộp thoại xác nhận xóa
        swal({
            title: "Bạn có chắc chắn muốn xóa?",
            text: "Khi xóa, sẽ không thể khôi phục dữ liệu!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                // Gửi yêu cầu AJAX để xóa ads
                $.ajax({
                    method: "POST",
                    url: "code.php",
                    data: {
                        'ads_id': id,
                        'delete_ads_btn': true
                    },
                    success: function (response) {
                        console.log(response);
                        if (response == '500') { 
                            swal("Thất bại!", "Xóa ADS thất bại", "error");
                        } else {
                            swal("Thành công!", "Xóa ADS thành công", "success").then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function () {
                        swal("Lỗi!", "Đã xảy ra lỗi trong quá trình xử lý", "error");
                    }
                });
            }
        });
    });
});

</script>