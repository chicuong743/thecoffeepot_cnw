<?php
session_start();
include('admin/config/dbcon.php');
include('includes/header.php');

// Lấy dữ liệu từ URL
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
$categoryId = isset($_GET['category']) ? $_GET['category'] : '';
$brandId = isset($_GET['brand']) ? $_GET['brand'] : '';
$minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 100000000000000;
$isSale = isset($_GET['sale']) ? $_GET['sale'] : '';
$error = '';
if ($minPrice > $maxPrice && $maxPrice > 0) {
    $error = 'Giá thấp nhất không được lớn hơn giá cao nhất.';
    $minPrice = 0; // Đặt lại giá thấp nhất nếu muốn
}
// Tạo câu truy vấn SQL
$sql = "SELECT p.*, c.name AS category_name, b.name AS brand_name
        FROM product p
        LEFT JOIN category c ON p.catid = c.id
        LEFT JOIN product_brands pb ON p.id = pb.product_id
        LEFT JOIN brand b ON pb.brand_id = b.id
        WHERE p.productName LIKE '%$searchQuery%'";

if (!empty($categoryId)) {
    $sql .= " AND p.catid = '$categoryId'";
}

if (!empty($brandId)) {
    $sql .= " AND pb.brand_id = '$brandId'";
}

if ($minPrice > 0) {
    $sql .= " AND p.price >= $minPrice";
}

if ($maxPrice > 0) {
    $sql .= " AND p.price <= $maxPrice";
}

if ($isSale === '1') {
    $sql .= " AND p.sale > 0";
}

// Nhóm theo ID sản phẩm để loại bỏ trùng lặp
$sql .= " GROUP BY p.id";

$result = $con->query($sql);

// Lấy danh sách danh mục và thương hiệu để hiển thị bộ lọc
$categories = $con->query("SELECT * FROM category");
$brands = $con->query("SELECT * FROM brand");
?>

<body>
<div class="container">

    <!-- Bộ lọc -->
    <div class="card my-4">
        <div class="card-body">
            <form action="" method="GET">

                <div class="col-md-4 text-center mx-auto">
                    <div class="mb-3">
                        <label for="query" class="form-label d-block">Từ khóa tìm kiếm</label>
                        <input type="text" name="query" id="query" class="form-control mx-auto"
                               style="width: 100%;" value="<?php echo htmlspecialchars($searchQuery); ?>"
                               placeholder="Nhập từ khóa tìm kiếm">
                    </div>
                </div>

                <div class="row">
                    <!-- Danh mục -->
                    <div class="col-md-3">
                        <label for="category" class="form-label">Danh mục</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">Tất cả</option>
                            <?php while ($cat = $categories->fetch_assoc()) { ?>
                                <option value="<?= $cat['id']; ?>" <?= $categoryId == $cat['id'] ? 'selected' : ''; ?>>
                                    <?= $cat['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Thương hiệu -->
                    <div class="col-md-3">
                        <label for="brand" class="form-label">Vị trí</label>
                        <select name="brand" id="brand" class="form-select">
                            <option value="">Tất cả</option>
                            <?php while ($brand = $brands->fetch_assoc()) { ?>
                                <option value="<?= $brand['id']; ?>" <?= $brandId == $brand['id'] ? 'selected' : ''; ?>>
                                    <?= $brand['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Giá -->
                    <div class="col-md-2">
                        <label for="min_price" class="form-label">Giá thấp nhất</label>
                        <input type="number" name="min_price" id="min_price" class="form-control"
                               value="<?= htmlspecialchars($minPrice); ?>" min="0">
                    </div>
                    <div class="col-md-2">
                        <label for="max_price" class="form-label">Giá đến</label>
                        <input type="number" name="max_price" id="max_price" class="form-control"
                               value="<?= htmlspecialchars($maxPrice); ?>" min="0">
                    </div>

                    <!-- Sale -->
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="sale" value="1" id="sale" class="form-check-input"
                                <?= $isSale === '1' ? 'checked' : ''; ?>>
                            <label for="sale" class="form-check-label">Sản phẩm giảm giá</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        <a href="search.php" class="btn btn-secondary">Xóa bộ lọc</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Kết quả tìm kiếm -->
    <div class="row">
        <?php if (!empty($error)){ ?>
            <div class="alert alert-danger text-center">
                <?= $error; ?>
            </div>
        <?php }else if ($result->num_rows > 0) {?>
            <h3 class="mb-3">Kết quả tìm kiếm của bạn: </h3>
            <?php while ($item = $result->fetch_assoc()) {
                ?>
                
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 p-3 mb-2 product position-relative">
                    <a href="product-view.php?productid=<?= $item['id'] ?>" class="product-link w-100">
                        <div class="product-card card h-100 w-100 col border rounded shadow position-relative">
                            <?= $item['sale'] > 0 ? '<div class="sale-badge-product position-absolute"><p class="mb-0">' . $item['sale'] . '%</p></div>' : ''; ?>
                            <img src="uploads/<?= $item['image'] ?>" alt="<?= $item['productName']; ?>" class="card-img-top img-fluid w-100" style="height: 180px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $item['productName']; ?></h5>
                                <h6>
                                    <?= $item['quantity'] == 0 
                                        ? '<strong class="text-danger">Hết hàng</strong>' 
                                        : '<span class="text-success">Số lượng còn lại:</span> ' . $item['quantity']; ?>
                                </h6>
                                <h5 class="text-primary fw-bold">
                                    <?= $item['sale'] == 0 
                                        ? number_format($item['price'], 0, ',', '.') . ' VNĐ' 
                                        : '<span class="text-danger">' . number_format($item['price'] - ($item['price'] * $item['sale'] / 100), 0, ',', '.') . ' VNĐ</span>
                                        <span class="text-muted text-decoration-line-through opacity">' . number_format($item['price'], 0, ',', '.') . ' VNĐ</span>'; ?>
                                </h5>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
            }
        } else {
            echo "<p class='text-center'>Không tìm thấy sản phẩm nào phù hợp.</p>";
        }
        ?>
    </div>
</div>

<script src="path/to/bootstrap.bundle.min.js"></script>
</body>

<?php include('includes/footer.php'); ?>
<script> 
    function showLoader() {
        $('.loader').removeClass('loader-hidden');
        }
        $(document).ready(function() {
        $('.login-btn').click(function() {
            showLoader();});
        });
</script>