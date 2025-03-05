<?php
// session_start();
include('function/userfunctions.php');
include('includes/header.php');
 ?>

<div class="py-3 bg-primary">
    <div class="container">
        <h6 class="text-white ">
            <a class="text-white" href="index.php">
                Home /
            </a>
                Sản Phẩm
        </h6>
    </div>
</div>


<div class="py-5">
    <div class="container">
        <div class="col-md-12">
        <h4 class="text-danger fw-bold">Danh mục</h4>
        <div class="underline mb-3 "></div>


        <nav style=" background-color: #003874;" class="navbar navbar-expand-lg navbar-dark rounded-top">
                <div class="container-fluid">
                    <!-- Menu Title -->
                    <a class="navbar-brand text-white" href="#">
                        <i class="fas fa-map-marker-alt"></i> Phân Loại
                    </a>
                    <!-- Toggle Button for Small Screens -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#brandMenu" aria-controls="brandMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- Collapsible Menu -->
                    <div class="collapse navbar-collapse" id="brandMenu">
                        <ul class="navbar-nav ms-auto">
                            <?php
                            $brands = getAll('brand');
                            if (mysqli_num_rows($brands) > 0) {
                                foreach ($brands as $brand) { ?>
                                    <li id="brand-menu" class="nav-item">
                                        <a class="nav-link text-white" href="product.php?id=<?= $brand['id'] ?>">
                                            <?= $brand['name'] ?>
                                        </a>
                                    </li>
                                <?php }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <div class="category-container owl-carousel owl-theme ">
                <?php
                $category = getAll('category');
                if (mysqli_num_rows($category) > 1) {
                    foreach ($category as $item) {
                        ?>
                        <div class="item">
                            <a href="product.php?category=<?= $item['id'] ?>">
                                <div class="card shadow category-card">
                                    <div class="card-body text-center">
                                        <img src="uploads/<?= $item['image'] ?>" alt="" class="w-100" style='height: 160px; object-fit: cover;'>
                                        <h4 class="mt-2"><?= $item['name']; ?></h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>


<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-danger fw-bold">Tất cả sản phẩm</h4>
                <div class="underline mb-1 "></div>
                <div id="productCarousel" class="carousel slide  border-secondary p-4 mb-4 shadow-lg" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                        <div class="row justify-content-center g-3 row-cols-1 row-cols-md-2 row-cols-lg-5" id="product-list">
                            <!-- JavaScript sẽ tự động chèn danh sách sản phẩm vào đây -->
                        </div>
                        <!-- Đưa tất cả sản phẩm vào dạng JSON trong một div ẩn để JavaScript có thể sử dụng -->
                        <div id="all-products" style="display: none;">
                            <?php
                            // Lấy tất cả sản phẩm nổi bật từ cơ sở dữ liệu
                            $trendingProduct = getAll('product');
                            $productsArray = [];
                            if (mysqli_num_rows($trendingProduct) > 0) {
                                foreach ($trendingProduct as $item) {
                                    $item['average_rating'] = getProductRating($item['id']);
                                    $productsArray[] = $item; // Thêm sản phẩm vào mảng
                                }
                            }
                            echo json_encode($productsArray); // Chuyển mảng sản phẩm thành JSON
                            ?>
                        </div>
                        <!-- Nút Previous và Next để điều hướng giữa các trang -->
                        <div class="pagination-controls text-center my-4">
                            <button class="btn btn-primary me-2" id="prevBtn" onclick="prevPage()"><i class="ri-arrow-left-s-line"></i></button>
                            <div id="pageNumbers" class="d-inline-block"></div>
                            <button class="btn btn-primary" id="nextBtn" onclick="nextPage()"><i class="ri-arrow-right-s-line"></i></button>
                        </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script>
    $(document).ready(function(){
        $('.owl-carousel').owlCarousel({
            loop: true,             // Vòng lặp khi đến mục cuối cùng
            margin: 10,             // Khoảng cách giữa các mục
            nav: true,              // Hiển thị nút Previous và Next
            dots: false,            // Tắt chấm phân trang
            autoplay: true,         // Tự động chạy slide
            autoplayTimeout: 4000,  // Thời gian chuyển slide
            responsive: {           // Cấu hình số lượng item cho từng kích thước màn hình
                0: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            }
        });
    });
</script>
<script>
 const products = JSON.parse(document.getElementById('all-products').textContent);
    const productsPerPage = 8; // Số sản phẩm mỗi trang
    let currentPage = 1; // Bắt đầu từ trang đầu tiên
    const totalPages = Math.ceil(products.length / productsPerPage); // Tổng số trang

    // Hàm hiển thị sản phẩm trên trang hiện tại
    function displayProducts(page) {
        const startIndex = (page - 1) * productsPerPage;
        const endIndex = startIndex + productsPerPage;
        const paginatedProducts = products.slice(startIndex, endIndex);

        const productList = document.getElementById('product-list');
        productList.innerHTML = '';

        paginatedProducts.forEach(item => {
            const productsaleHTML = item.sale > 0
            ? `<div style="z-index:10;" class="sale-badge-product position-absolute"><p class="mb-0">${item.sale}%</p></div>` 
            : '';
            const productHTML = `
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 p-4 mb-4 product position-relative">
                    <a href="product-view.php?productid=${item.id}" class="product-link w-100">
                        <div class="product-card card h-100 w-100 col border rounded shadow position-relative">
                            ${productsaleHTML}
                            <img src="uploads/${item.image}" alt="" class="card-img-top img-fluid w-100" style="z-index:1; height: 180px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title">${item.productName}</h5>
                                <h6>${item.quantity == 0 
                                    ? '<strong class="text-danger">Hết hàng</strong>' 
                                    : `<span class="text-success">Số lượng còn lại:</span> ${item.quantity}`}</h6>
                                <h5 class="text-primary fw-bold">
                                    ${item.sale == 0 ? new Intl.NumberFormat().format(item.price) + ' VNĐ' :
                                        `<span class="text-danger">${new Intl.NumberFormat().format(item.price - (item.price * item.sale / 100))} VNĐ</span>
                                        <span class="text-muted text-decoration-line-through opacity">${new Intl.NumberFormat().format(item.price)} VNĐ</span>`}
                                </h5>
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="stars-container"> 
                                    ${getStarsHTML(item.average_rating)}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            `;
            function getStarsHTML(rating) {
                let starsHTML = '';
                for (let i = 1; i <= 5; i++) {
                    let starClass = i <= rating ? 'text-warning' : 'text-muted';
                    starsHTML += `<i class="fas fa-star ${starClass} mr-1 fs-5"></i>`;
                }
                return starsHTML;
            }
            productList.insertAdjacentHTML('beforeend', productHTML);
        });

        updatePagination();
    }

    // Hàm cập nhật thanh phân trang (hiển thị số trang)
    function updatePagination() {
        const pageNumbers = document.getElementById('pageNumbers');
        pageNumbers.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.classList.add('btn', 'btn-outline-primary', 'me-1');
            pageButton.textContent = i;
            if (i === currentPage) {
                pageButton.classList.add('active');
            }
            pageButton.onclick = () => {
                currentPage = i;
                displayProducts(currentPage);
            };
            pageNumbers.appendChild(pageButton);
        }

        // Cập nhật trạng thái nút Previous và Next
        document.getElementById('prevBtn').disabled = currentPage === 1;
        document.getElementById('nextBtn').disabled = currentPage === totalPages;
    }

    // Hàm chuyển sang trang kế tiếp
    function nextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            displayProducts(currentPage);
        }
    }

    // Hàm quay lại trang trước
    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            displayProducts(currentPage);
        }
    }

    // Hiển thị trang đầu tiên khi trang được tải
    displayProducts(currentPage);
</script>