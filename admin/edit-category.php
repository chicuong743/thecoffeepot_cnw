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
                $category = getByID("category", $id);

                if (mysqli_num_rows($category) > 0) {
                  
                        $data = mysqli_fetch_array($category);
                        ?>
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h4 class="text-white">Sửa danh mục sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <form action="code.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="category_id" value="<?= $data['id'] ?>">
                                        <label for="">Tên danh mục</label>
                                        <input type="text" value="<?= $data['name'] ?>" class="form-control mb-2" name="name"
                                            placeholder="Nhập vào tên danh mục...">
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for="">Hình ảnh cũ</label>
                                        <input type="hidden" name="old_image" value="<?= $data['image']; ?>">
                                        <br>
                                        <img class="mb-2" src="../uploads/<?= $data['image']; ?> " alt="" height="50px"
                                            width="50px">
                                        <br>

                                        <label for="">Hình ảnh mới </label>
                                        <input type="file" class="form-control mb-2 " name="image">

                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" name="edit_category_btn"> Lưu</button>

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