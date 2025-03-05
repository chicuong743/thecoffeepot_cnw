<?php
// session_start();
include('function/userfunctions.php');
include('includes/header.php');
include('includes/slider.php');
?>


<div class="py-5">
    <div class="container">
        <div class="col-md-12">
            <h4 class="text-danger fw-bold">Danh mục sản phẩm</h4>
            <div class="underline mb-2"></div>

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

            </div>
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
                <h4 class="text-danger fw-bold">Sản phẩm nổi bật</h4>
                <div class="underline mb-2"></div>
                <div id="productCarousel" class="carousel slide  border-secondary mb-1 shadow-lg" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                        <div class="row justify-content-center g-3 row-cols-1 row-cols-md-2 row-cols-lg-5" id="product-list">
                         
                        </div>
                        <div id="all-products" style="display: none;">
                            <?php
                            // Lấy tất cả sản phẩm nổi bật từ cơ sở dữ liệu
                            $trendingProduct = getAllTrending();
                            $productsArray = [];
                            if (mysqli_num_rows($trendingProduct) > 0) {
                                foreach ($trendingProduct as $item) {
                                    $item['average_rating'] = getProductRating($item['id']);
                                    $productsArray[] = $item;
                                }
                            }
                            echo json_encode($productsArray); // Chuyển mảng sản phẩm thành JSON
                            ?>
                        </div>
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
</div>


<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-danger fw-bold">Ưu Đãi</h4>
                <div class="underline mb-2 " ></div>
                
                <div class="col-md-12 d-md-flex d-block align-items-stretch ">
                    <!-- Banner sale -->
                    <div class="col-md-3 p-0 d-flex">
                        <?php include('includes/slider_sale.php'); ?>
                    </div>
                    <!-- List product sale -->
                    <div class="col-md-9 p-0 d-flex position-relative">
                    <div class=" icon_sale position-absolute"><img src="./uploads/icon_sale.png" ></div>
                        <div id="productCarousel" class="rounded-right carousel slide border-secondary p-2 shadow-lg w-100" data-ride="carousel">
                            <div class="carousel-inner h-100">
                                <div class="carousel-item active">
                                    <div class="row justify-content-center g-3 row-cols-1 row-cols-md-3 h-100" id="product-list-sale"></div>
                                    <div id="all-products-sale" style="display: none;">
                                        <?php
                                        // Fetch all sale products from the database
                                        $SaleProduct = getProductByIdSale();
                                        $productsArray = [];

                                        if ($SaleProduct && mysqli_num_rows($SaleProduct) > 0) {
                                            while ($item = mysqli_fetch_assoc($SaleProduct)) {
                                                $item['average_rating'] = getProductRating($item['id']);
                                                $productsArray[] = $item; // Add each product to the array
                                            }
                                        }
                                        echo htmlspecialchars(json_encode($productsArray), ENT_QUOTES, 'UTF-8'); // Safely encode the array as JSON
                                        ?>
                                    </div>
                                    <div class="pagination-controls text-center my-4">
                                        <button class="btn btn-primary me-2" id="prevBtnSale" onclick="prevPageSale()"><i class="ri-arrow-left-s-line"></i></button>
                                        <div id="pageNumbersSale" class="d-inline-block"></div>
                                        <button class="btn btn-primary" id="nextBtnSale" onclick="nextPageSale()"><i class="ri-arrow-right-s-line"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                  
            </div>            
        </div>
    </div>
</div>


<div class="py-5 bg-f2f2f2">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-danger fw-bold">Bài Viết </h4>
                <div class="underline mb-2"></div>
                <div style="height: 300px; overflow-y: auto;">
                <?php 
                $content = getContent();
                if (mysqli_num_rows($content) > 0){
                    foreach ($content as $contents) {  ?>
                    <a href="content.php?id=<?=$contents['id']?>" class="text-decoration-none">
                        <div class="d-flex mb-3">
                            <!-- Hình ảnh -->
                            <div class="image-container" style="flex: 3;">
                                <img src="./uploads/<?=$contents['images']?>" alt="<?=$contents['name_ads']?>" class="img-fluid rounded-circle border-dotted">
                            </div>
                            <!-- Nội dung -->
                            <div class="content-container d-flex flex-column justify-content-center" style="flex: 9; padding-left: 15px;">
                                <h4><?=$contents['name_ads']?></h4>
                                <p><?=substr($contents['content_ads'], 0, 100) . '...'?></p>
                                <span class="text-primary">Xem thêm</span>
                            </div>
                        </div>
                    </a>
                    <?php }
                }?>
                </div>
            </div>
        </div>            
    </div>
</div>


<div class="py-5 bg-f2f2f2">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-danger fw-bold">Thông tin </h4>
                <div class="underline mb-2"></div>
                <p>Thành lập vào ngày 1/1/2025, The Coffee Pot chuyên cung cấp máy xay và máy pha cà phê chất lượng cao, đáp ứng nhu cầu từ cá nhân, quán cà phê đến doanh nghiệp. Với sự am hiểu sâu sắc về thị trường cà phê, chúng tôi mang đến những sản phẩm hiện đại, bền bỉ và dễ sử dụng, giúp khách hàng tạo ra những ly cà phê thơm ngon với chất lượng ổn định. Không chỉ cung cấp thiết bị, The Coffee Pot còn cam kết hỗ trợ khách hàng bằng dịch vụ tư vấn chuyên sâu, hướng dẫn sử dụng và bảo trì sản phẩm một cách tận tâm.</p>

                <p>Với tầm nhìn trở thành thương hiệu hàng đầu trong lĩnh vực thiết bị pha chế cà phê, The Coffee Pot luôn đặt chất lượng và sự đổi mới lên hàng đầu. Sứ mệnh của chúng tôi là mang đến giải pháp pha chế tối ưu, giúp khách hàng tiết kiệm thời gian, nâng cao hiệu suất và nâng tầm trải nghiệm cà phê. Chúng tôi tin rằng một tách cà phê ngon không chỉ đến từ nguyên liệu mà còn từ công nghệ pha chế tiên tiến, và The Coffee Pot chính là cầu nối giúp hiện thực hóa điều đó.</p>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>
<script>//sản phẩm nổi bật 
 const products = JSON.parse(document.getElementById('all-products').textContent);
    const productsPerPage = 4; // Số sản phẩm mỗi trang
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
            ? `<div class="sale-badge-product position-absolute"><p class="mb-0">${item.sale}%</p></div>` 
            : '';
            const productHTML = `
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 p-3 mb-2 product position-relative">
                    <a href="product-view.php?productid=${item.id}" class="product-link w-100">
                        <div class="product-card card h-100 w-100 col border rounded shadow position-relative">
                           ${productsaleHTML}
                            <img src="uploads/${item.image}" alt="" class="card-img-top img-fluid w-100" style="z-index:100; height: 180px; object-fit: cover;">
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
<script>// sản phẩm sale
    const products_sale = JSON.parse(document.getElementById('all-products-sale').textContent);
    const productsPerPageSale = 3; // Số sản phẩm mỗi trang
    let currentPageSale = 1; // Bắt đầu từ trang đầu tiên
    const totalPagesSale = Math.ceil(products_sale.length / productsPerPageSale); // Tổng số trang

    // Hàm hiển thị sản phẩm trên trang hiện tại
    function displayProductsSale(page) {
        const startIndexSale = (page - 1) * productsPerPageSale;
        const endIndexSale = startIndexSale + productsPerPageSale;
        const paginatedProductsSale = products_sale.slice(startIndexSale, endIndexSale);

        const productListSale = document.getElementById('product-list-sale');
        productListSale.innerHTML = ''; // Xóa danh sách sản phẩm cũ

        paginatedProductsSale.forEach(items => {
            const productsaleHTMLSale = items.sale > 0
                ? `<span  style="z-index:100;" class="badge bg-danger position-absolute top-0 start-0 m-2">${items.sale}% OFF</span>`
                : '';
            const productHTMLSale = `
                <div class="col-lg-4 col-md-6 col-sm-6 col-12 p-3 mb-1 product position-relative">
                    <a href="product-view.php?productid=${items.id}" class="product-link w-100">
                        <div class="product-card card h-100 w-100 col border rounded shadow position-relative">
                            ${productsaleHTMLSale}
                            <img src="uploads/${items.image}" alt="${items.productName}" class="card-img-top img-fluid w-100" style="z-index:1; height: 180px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title">${items.productName}</h5>
                                <h6>${items.quantity == 0 
                                    ? '<strong class="text-danger">Hết hàng</strong>' 
                                    : `<span class="text-success">Số lượng còn lại:</span> ${items.quantity}`}</h6>
                                <h5 class="text-primary fw-bold">
                                        ${items.sale == 0 
                                        ? `${new Intl.NumberFormat().format(items.price)} VNĐ` 
                                        : `<span class="text-danger">${new Intl.NumberFormat().format(items.price - (items.price * items.sale / 100))} VNĐ</span>
                                            <span class="text-muted text-decoration-line-through opacity">${new Intl.NumberFormat().format(items.price)} VNĐ</span>`}
                                </h5>
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="stars-container"> 
                                    ${getStarsHTML(items.average_rating)}
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
            productListSale.insertAdjacentHTML('beforeend', productHTMLSale);
        });

        updatePaginationSale();
    }

    // Hàm cập nhật thanh phân trang (hiển thị số trang)
    function updatePaginationSale() {
        const pageNumbersSale = document.getElementById('pageNumbersSale');
        pageNumbersSale.innerHTML = ''; // Xóa phân trang cũ

        for (let i = 1; i <= totalPagesSale; i++) {
            const pageButtonSale = document.createElement('button');
            pageButtonSale.classList.add('btn', 'btn-outline-primary', 'me-1');
            pageButtonSale.textContent = i; // Số trang
            if (i === currentPageSale) {
                pageButtonSale.classList.add('active');
            }
            pageButtonSale.onclick = () => {
                currentPageSale = i;
                displayProductsSale(currentPageSale);
            };
            pageNumbersSale.appendChild(pageButtonSale);
        }

        // Cập nhật trạng thái nút Previous và Next
        document.getElementById('prevBtnSale').disabled = currentPageSale === 1;
        document.getElementById('nextBtnSale').disabled = currentPageSale === totalPagesSale;
    }

    // Hàm chuyển sang trang kế tiếp
    function nextPageSale() {
        if (currentPageSale < totalPagesSale) {
            currentPageSale++;
            displayProductsSale(currentPageSale);
        }
    }

    // Hàm quay lại trang trước
    function prevPageSale() {
        if (currentPageSale > 1) {
            currentPageSale--;
            displayProductsSale(currentPageSale);
        }
    }

    // Hiển thị trang đầu tiên khi trang được tải
    displayProductsSale(currentPageSale);
</script>

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