<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');
include('../function/statistical.php');

$countOrder = countOrder();
$countWait = countWait();
$countCan = countCanncel();
$countSucc = countSuccess();
$total = gettotal();
$totalWait = gettotalwait();
$totalCancel = gettotalCancel();
$totalSucc = gettotalSucc();
$countAcc = countAcc();

if(isset($_SESSION['auth'])){
    if ($_SESSION['auth_user']['type'] == 1 ) {
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-7 position-relative z-index-2">
                            <div class="card card-plain mb-4">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="d-flex flex-column h-100">
                                                <h2 class="font-weight-bolder mb-0">Thống kê</h2>
                                            </div>
        
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col-lg-5 col-sm-5">
                                    <div class="card  mb-2">
                                        <div class="card-header p-3 pt-2">
                                            <div
                                                class="icon icon-lg icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-xl mt-n4 position-absolute">
                                                <i class="material-icons opacity-10">weekend</i>
                                            </div>
                                            <div class="text-end pt-1">
                                                <p class="text-sm mb-0 text-capitalize">Tổng đơn hàng</p>
                                                <h4 class="mb-0">
                                                    <?php echo $countOrder ?>
                                                </h4>
                                            </div>
                                        </div>
        
                                        <hr class="dark horizontal my-0">
                                        <div class="card-footer p-3">
                                            <p class="mb-0"><span class="text-warning text-sm font-weight-bolder">
                                                    <?php echo $countWait ?>
                                                </span>đơn chờ xử lý</p>
                                            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">
                                                    <?php echo $countCan ?>
                                                </span>đơn đã hoàn thành!</p>
                                            <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">
                                                    <?php echo $countSucc ?>
                                                </span>đơn đã hủy</p>
                                        </div>
                                    </div>
        
        
                                </div>
                                <div class="col-lg-5 col-sm-5 mt-sm-0 mt-4">
                                    <div class="card  mb-2">
                                        <div class="card-header p-3 pt-2 bg-transparent">
                                            <div
                                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                                <i class="material-icons opacity-10">store</i>
                                            </div>
                                            <div class="text-end pt-1">
                                                <p class="text-sm mb-0 text-capitalize ">Money</p>
                                                <h4 class="mb-0 ">
                                                    <?php $formattedTotal = number_format($total, 0, ',', '.');
                                                    echo $formattedTotal ?>
                                                </h4>
                                            </div>
                                        </div>
                                        <hr class="horizontal my-0 dark">
                                        <div class="card-footer p-3">
                                            <p class="mb-0 "><span class="text-success text-sm font-weight-bolder">
                                                    <p class="mb-0"><span class="text-warning text-sm font-weight-bolder">
                                                            <?php $formattotalWait = number_format($totalWait, 0, ',', '.');
                                                            echo $formattotalWait ?>$
                                                        </span>đơn chờ xử lý</p>
                                                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">
                                                            <?php $formattotalCancel = number_format($totalCancel, 0, ',', '.');
                                                            echo $formattotalCancel ?>$
                                                        </span>đơn đã hoàn thành!</p>
                                                    <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">
                                                            <?php $formattotalSucc = number_format($totalSucc, 0, ',', '.');
                                                            echo $formattotalSucc ?>$
                                                        </span>đơn đã hủy</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-sm-5 mt-sm-0 mt-4">
        
                                    <div class="card mb-2">
                                        <div class="card-header p-3 pt-2 bg-transparent">
                                            <div
                                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                                <i class="material-icons opacity-10">account_circle</i>
                                            </div>
                                            <div class="text-end pt-1">
                                                <p class="text-sm mb-0 text-capitalize">Tài khoản</p>
                                                <!-- Thêm thông tin tài khoản ở đây -->
                                                <h4 class="mb-0">
                                                    <?php ?>
                                                </h4>
                                            </div>
                                        </div>
                                        <hr class="horizontal my-0 dark">
                                        <div class="card-footer p-3">
                                            <!-- Thêm thông tin tài khoản khác nếu cần -->
                                            <p class="mb-0 fs-5 fw-blod">Tổng số tài khoản KHÁCH HÀNG:
                                                <?php echo $countAcc; ?>
                                            </p>
                                            <!-- Các thông tin khác của tài khoản -->
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="dark horizontal my-0">
        
                        </div>
        
                    </div>
                    <br>
                </div>




            <?php $lowStockProducts = getLowStockProducts();?>

            <div class="container mt-4 mb-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="text-center mb-0">📦 Sản phẩm gần hết hàng</h2>
        </div>
        <div class="card-body">
            <?php if (count($lowStockProducts) > 0): ?>
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Hình ảnh</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStockProducts as $product): ?>
                            <tr>
                                <td><?= $product['id']; ?></td>
                                <td><?= htmlspecialchars($product['productName']); ?></td>
                                <td>
                                    <img src="../uploads/<?=$product['image']; ?>" alt="<?= htmlspecialchars($product['productName']); ?>" width="50" class="rounded">
                                </td>
                                <td class="text-danger font-weight-bold"><?= $product['quantity']; ?></td>
                                <td><?= number_format($product['price'], 0, ',', '.'); ?>₫</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-success text-center">🎉 Không có sản phẩm nào gần hết hàng.</p>
            <?php endif; ?>
        </div>
       
    </div>
</div>
        
        
                <hr class="dark horizontal my-0">
                <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h2 class="text-center mb-0">📊 Biểu đồ sản phẩm bán</h2>
                    </div>
                    <div class="card-body mb-3">
                        <canvas id="myChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        
                <script>
                    var data = <?php echo json_encode(getProductSales()); ?>;
        
                    var labels = data.map(item => item.product_name);
                    var values = data.map(item => item.total_sold);
        
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Đã bán',
                                data: values,
                                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                                borderColor: 'rgba(255, 0, 0, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
        
        
        <?php }elseif($_SESSION['auth_user']['type'] == 2){ ?>
            <div class="container vh-100 d-flex justify-content-center align-items-center">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1>Chào nhân viên đến với trang Admin</h1>
                        <h2 id="current-time"></h2> <!-- Phần hiển thị đồng hồ -->
                        <?php $newOrders = getNumberOrderHistory0();?>
                        <a href="orders.php" class="btn btn-dark mt-3">bạn có <?=$newOrders?> đơn hàng mới </a>
                    </div>
                </div>
            </div>

            <script>
                function updateClock() {
                    const now = new Date();
                    const day = now.toLocaleDateString(); // Lấy ngày hiện tại
                    const time = now.toLocaleTimeString(); // Lấy giờ hiện tại
                    document.getElementById('current-time').textContent = `Thời gian hiện tại: ${day} ${time}`;
                }

                // Cập nhật đồng hồ mỗi giây
                setInterval(updateClock, 1000);

                // Hiển thị ngay lập tức khi tải trang
                updateClock();
            </script>
        <?php } 
}?>

<?php include('includes/footer.php'); ?>