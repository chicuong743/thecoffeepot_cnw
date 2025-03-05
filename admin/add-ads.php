<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Thêm ADS
                    <a href="ads.php" class="btn btn-warning float-end"><i class="fa fa-reply"></i> Trở về</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Tên ADS</label>
                                <input type="text" class="form-control mb-2" required name="name_ads" placeholder="Nhập vào tên ADS...">
                            </div>
                            <div class="col-md-12">
                                <label for="">Nội Dung</label>
                                <textarea name="content_ads" class="form-control mb-2" required placeholder="Mô tả nội dung ADS..." rows="5"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="type_ads">Loại ADS</label>
                                <select name="type_ads" class="form-select mb-2" required>
                                    <option value="Bài Viết">Bài Viết</option>
                                    <option value="Banner Đầu Trang">Banner Đầu Trang</option>
                                    <option value="Banner Sale">Banner Sale</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="">Hình ảnh</label>
                                <input type="file" class="form-control mb-2" required name="images">
                            </div>
                            <div class="col-md-6">
                                <label for="">Trạng thái</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="statusCheckbox" name="status" value="1">
                                    <label class="form-check-label" for="statusCheckbox">Hiển thị</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" name="add_ads_btn">Lưu</button>
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
