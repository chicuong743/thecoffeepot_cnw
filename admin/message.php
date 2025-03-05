<?php
// session_start();
include('includes/header.php');
include('../middleware/adminMiddleware.php');


$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Câu truy vấn SQL theo trạng thái
if ($status_filter == 'all') {
    $query = "SELECT * FROM helpper ORDER BY created_at DESC";
} elseif ($status_filter == '0') {
    $query = "SELECT * FROM helpper WHERE status = 0 ORDER BY created_at DESC";
} elseif ($status_filter == '1') {
    $query = "SELECT * FROM helpper WHERE status = 1 ORDER BY created_at DESC";
}

// Lấy dữ liệu tin nhắn
$message = mysqli_query($con, $query);
 ?>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white fs-3">Tin nhắn trợ giúp   </h4>
                    <a href="message.php?status=all" class="btn btn-info float-end ms-2">Tất cả</a>
                    <a href="message.php?status=1" class="btn btn-success float-end ms-2">Đã phản hồi</a>
                    <a href="message.php?status=0" class="btn btn-warning float-end">Chưa phản hồi</a>
                 
                </div>
                <div class="card-body">
                    <table class="table  table-hover ">
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Ngày</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php
                            if (mysqli_num_rows($message) > 0) {
                                foreach ($message as $item) {
                                    ?>
                                    <tr>
                                        <td><?= $item['name'] ?></td>
                                        <td><?= $item['email'] ?></td>
                                        <td><?= $item['phone'] ?></td>
                                        <td><?= date('H:i - d/m/Y', strtotime($item['created_at'])) ?></td>
                                        <td>
                                            <?php if ($item['status'] == 0) { ?>
                                                <span class="badge bg-warning text-white">Chưa phản hồi</span>
                                            <?php } elseif ($item['status'] == 1) { ?>
                                                <span class="badge bg-success">Đã phản hồi</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="view-message.php?id=<?= $item['id'] ?>" class="btn btn-primary">Xem</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">Không có tin nhắn nào</td>
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