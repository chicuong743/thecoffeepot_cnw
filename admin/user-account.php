<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

// Lấy tham số type từ URL
$type = isset($_GET['type']) ? $_GET['type'] : null; // Nếu không có tham số, mặc định là null

// Lọc người dùng theo type
if ($type !== null) {
    // Lọc theo type (0 cho khách hàng, 1 cho nhân viên)
    $user = getAll('users WHERE id_user != 1 and type = '.$type );
} else {
    // Lấy tất cả người dùng nếu không có tham số type
    $user = getAll('users WHERE id_user != 1');
}
?><?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
        <?= $_SESSION['message']; ?>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white fs-3">
                        Tài khoản người dùng
                    </h4>
                    <a href="user-account.php?type=2" class="btn btn-danger float-end ms-3">Tài khoản nhân viên</a>
                    <a href="user-account.php?type=0" class="btn btn-warning float-end ms-3">Tài khoản khách hàng</a>
                    <a href="user-account.php" class="btn btn-success float-end ms-3">Tất Cả</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php
                            if (mysqli_num_rows($user) > 0) {
                                foreach ($user as $item) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $item['name'] ?>
                                            <?php 
                                                if($item['type'] == 1) { ?>
                                                    <span class="ms-3 badge bg-light text-dark fw-bold">Admin</span> 
                                                <?php } elseif($item['type'] == 2) { ?>
                                                    <span class="ms-3 badge bg-light text-dark">Nhân viên</span> 
                                                <?php } ?>
                                        </td>
                                        <td><?= $item['phone'] ?></td>
                                        <td><?= $item['email'] ?></td>
                                        <td><?= date('H:i - d/m/Y', strtotime($item['created_at'])) ?></td>
                                        <td>
                                            <?php if ($item['status'] == 0) { ?>
                                                <span class="badge bg-success text-white">Hoạt động</span>
                                            <?php } else { ?>
                                                <span class="badge bg-warning">Bị khóa</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <form action="../function/authcode.php" method="POST">
                                                <input type="hidden" name="id_user" value="<?= $item['id_user'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" name="admin_disabled_account"
                                                    <?= $item['status'] == '0' ? '' : 'style="display:none;"' ?>>Khóa</button>
                                                <button type="submit" class="btn btn-primary btn-sm" name="admin_enabled_account"
                                                    <?= $item['status'] == '1' ? '' : 'style="display:none;"' ?>>Mở</button>
                                                <button type="submit" class="btn btn-light btn-sm" name="remove-nhanvien"
                                                    <?= $item['type'] == '2' ? '' : 'style="display:none;"' ?>>Thu Hồi</button>
                                                <button type="submit" class="btn btn-light btn-sm" name="add-nhanvien"
                                                    <?= $item['type'] == '0' ? '' : 'style="display:none;"' ?>>Nhân viên</button>
                                                <button type="submit" class="btn btn-danger float-end btn-sm" name="delete_user_account">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">Không có tài khoản nào</td>
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
