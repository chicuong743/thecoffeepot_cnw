<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Danh mục
                    <a href="add-category.php" class="btn btn-info float-end">Thêm danh mục</a>

                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>

                                <!-- <th>ID</th> -->
                                <th>Tên danh mục</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $category = getAll("category");
                            if (mysqli_num_rows($category) > 0) {
                                foreach ($category as $item) {
                                    ?>
                                    <tr>
                                        <!-- <td>
                                            <?= $item['id'] ?>
                                        </td> -->
                                        <td>
                                            <?= $item['name'] ?>
                                        </td>
                                        <td>
                                            <img src="../uploads/<?= $item['image'] ?>" width="50px" height="50px" alt="<?= $item['name'] ?>">
                                            
                                        </td>
                                        <td>
                                            <a href="edit-category.php?id=<?= $item['id']; ?>" class="btn btn-primary">Sửa</a>
                                           <form action="code.php" method="POST">
                                                <input type="hidden" name="category_id" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn btn-danger" name="delete_category">Xóa</button>
                                           </form>
                                        </td>

                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "Không có danh mục";
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