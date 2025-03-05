<?php
// session_start();
include('includes/header.php');
include('../middleware/adminMiddleware.php');

function getFilteredOrders($filters) {
    global $con; 

    $query = "SELECT * FROM orders WHERE 1=1";
    $params = [];
    if (!empty($filters['phone'])) {
        $query .= " AND phone LIKE ?";
        $params[] = '%' . $filters['phone'] . '%';
    }
    if (!empty($filters['email'])) {
        $query .= " AND email LIKE ?";
        $params[] = '%' . $filters['email'] . '%';
    }
    if (!empty($filters['tracking_no'])) {
        $query .= " AND tracking_no LIKE ?";
        $params[] = '%' . $filters['tracking_no'] . '%';
    }
    $stmt = $con->prepare($query);
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
$filters = [
    'phone' => isset($_GET['phone']) ? $_GET['phone'] : '',
    'email' => isset($_GET['email']) ? $_GET['email'] : '',
    'tracking_no' => isset($_GET['tracking_no']) ? $_GET['tracking_no'] : '',
];
$orders = getFilteredOrders($filters);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- Form lọc đơn hàng -->
            <form method="GET" action="" class="mb-2 mt2">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="phone" class="form-control" placeholder="Tìm theo số điện thoại" 
                                value="<?= htmlspecialchars($filters['phone']) ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="email" class="form-control" placeholder="Tìm theo email" 
                                value="<?= htmlspecialchars($filters['email']) ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="tracking_no" class="form-control" placeholder="Tìm theo mã đơn hàng" 
                                value="<?= htmlspecialchars($filters['tracking_no']) ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">Lọc</button>
                        <a href="orders.php" class="btn btn-secondary">Xóa bộ lọc</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white fs-3">Tất cả đơn hàng</h4>
                    <a href="orders.php" class="btn btn-danger float-end mx-2">Tất cả đơn hàng</a>
                    <a href="order-history1.php" class="btn btn-warning float-end mx-2">Đơn hàng đã hủy</a>
                    <a href="order-history.php" class="btn btn-info float-end">Đơn hàng đã hoàn thành</a>
                    <a href="order-history2.php" class="btn btn-success float-end mx-2">Đơn mới</a>
                </div>
                <div class="card-body">
                    <table class="table  table-hover ">
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Người dùng</th>
                                <th>Mã đơn hàng</th>
                                <th>Giá (VNĐ)</th>
                                <th>Ngày đặt</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                        <?php
                            if ($orders && mysqli_num_rows($orders) > 0) {
                                foreach ($orders as $item) {
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= htmlspecialchars($item['tracking_no']) ?></td>
                                        <td><?= number_format($item['total_price'], 0, ',', '.') ?></td>
                                        <td><?= date('H:i - d/m/Y', strtotime($item['created_at'])) ?></td>
                                        <td>
                                            <a href="view-orders.php?t=<?= htmlspecialchars($item['tracking_no']) ?>" 
                                               class="btn btn-primary">Xem chi tiết</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center">Không tìm thấy đơn hàng nào.</td>
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