<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Phân loại
                    <a href="add-brand.php" class="btn btn-info float-end">Thêm phân loại</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>

                                <!-- <th>ID</th> -->
                                <th>Tên phân loại</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $brand = getAll("brand");
                            if (mysqli_num_rows($brand) > 0) {
                                foreach ($brand as $item) {
                                    ?>
                                    <tr>
                                        <!-- <td>
                                            <?= $item['id'] ?>
                                        </td> -->
                                        <td>
                                            <?= $item['name'] ?>
                                        </td>
                                        <td>
                                            <a href="edit-brand.php?id=<?= $item['id']; ?>" class="btn btn-primary">Sửa</a>
                                           <form action="code.php" method="POST">
                                                <input type="hidden" name="brand_id" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn btn-danger" name="delete_brand">Xóa</button>
                                           </form>
                                        </td>

                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "Không có phân loại";
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
        document.querySelector("form").addEventListener("submit", function (e) {
        const name = document.querySelector("[name='name']").value.trim();

        if (!name) {
            e.preventDefault(); // Ngăn gửi biểu mẫu nếu tên trống
            alert("Tên phân loại không được để trống.");
        }
    });
</script>