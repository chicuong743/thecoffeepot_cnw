<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

?>
    <?php if (isset($_SESSION['message'])): ?>
        <p class="success-message">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
        </p>
    <?php endif; ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Thêm sản phẩm
                    <a href="product.php" class="btn btn-warning float-end"><i class="fa fa-reply"></i> Trở
                            về</a>
                    </h4>
                </div>
                <div class="card-body">
                <form action="code.php" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            <label for="">Tên sản phẩm</label>
            <input type="text" class="form-control mb-2" required name="name" placeholder="Nhập vào tên sản phẩm...">
        </div>
        <div class="col-md-12">
            <div class="form-floating">
                <textarea required name="desc" class="form-control mb-2" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                <label for="floatingTextarea2">Mô tả sản phẩm</label>
            </div>
        </div>
        <div class="col-md-3">
            <label for="">Danh mục</label>
            <select  class="form-select" name="catid">
                <option class="ms-3" required selected>  Chọn danh mục  </option>
                <?php
                $category = getAll("category");
                if (mysqli_num_rows($category) > 0) {
                    foreach ($category as $item) {
                        echo "<option  value='{$item['id']}'>{$item['name']}</option>";
                    }
                } else {
                    echo "Danh mục trống";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="">Phân loại vị trí</label>
            <div class="checkbox-dropdown">
                <button type="button" class="form-control dropdown-btn"> <span style="margin-right:230px;">Phân loại vị trí</span>          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg></button>
                <div class="checkbox-list">
                    <?php
                    $brand = getAll("brand");
                    if (mysqli_num_rows($brand) > 0) {
                        foreach ($brand as $item) {
                            echo "<label><input type='checkbox' name='brandid[]' value='{$item['id']}'> {$item['name']}</label><br>";
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
                                <input type="number" min="0" max="100" value="0" class="form-control mb-2" required name="sale">
                            </div>
                            <div class="col-md-12">
                                <label for="">Hình ảnh</label>
                                <input type="file" class="form-control mb-2" required name="image">
                            </div>
                            <div class="col-md-6">
                                <label for="">Giá (VNĐ)</label>
                                <input type="number" value="0" class="form-control mb-2" required name="price">
                            </div>
                            <div class="col-md-6">
                                <label for="">Số lượng</label>
                                <input type="number" value="0" class="form-control mb-2" required name="quantity">
                            </div>
                            <div class="col-md-3">
                                <label for="">Ưu tiên</label>
                                <input type="checkbox" id="uutienCheckbox" name="trending">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" name="add_product_btn">Lưu</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- <script>
    const uutienCheckbox = document.getElementById("uutienCheckbox");
    const khonguutienCheckbox = document.getElementById("khonguutienCheckbox");

    uutienCheckbox.addEventListener("change", function () {
        if (uutienCheckbox.checked) {
            khonguutienCheckbox.checked = false;
        }
    });

    khonguutienCheckbox.addEventListener("change", function () {
        if (khonguutienCheckbox.checked) {
            uutienCheckbox.checked = false;
        }
    });
</script> -->

<?php
include('includes/footer.php');
?>
<script>
        document.querySelector("form").addEventListener("submit", function (e) {
        const name = document.querySelector("[name='name']").value.trim();
        const desc = document.querySelector("[name='desc']").value.trim();
        const price = document.querySelector("[name='price']").value.trim();
        const category = document.querySelector("[name='catid']").value;
        const image = document.querySelector("[name='image']").files.length;

        let errorMessage = "";

        if (!name) {
            errorMessage += "Tên sản phẩm không được để trống.\n";
        }
        if (!desc) {
            errorMessage += "Mô tả sản phẩm không được để trống.\n";
        }
        if (!price || parseFloat(price) <= 0) {
            errorMessage += "Giá sản phẩm phải lớn hơn 0.\n";
        }
        if (category === "Chọn danh mục") {
            errorMessage += "Vui lòng chọn danh mục sản phẩm.\n";
        }
        if (image === 0) {
            errorMessage += "Vui lòng chọn hình ảnh sản phẩm.\n";
        }

        if (errorMessage) {
            e.preventDefault(); // Ngăn gửi biểu mẫu
            alert(errorMessage);
        }
    });
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