<?php
// session_start();

include('function/userfunctions.php');
include('includes/header.php');

if (isset($_GET['category'])) {
    $category_id = $_GET['category'];
    // echo $category_id;
    // $product_get_cid = getID('product', $category_id);
    // $product_get_cid_run = mysqli_fetch_array($product_get_cid);
    // Lấy tên cho thằng dưới
    $category_name = getID('category', $category_id);
    $category_name_run = mysqli_fetch_array($category_name);

    if ($category_name_run) {
        ?>

        <div class="py-3 bg-primary">
            <div class="container">
                <h6 class="text-white">
                    <a class="text-white" href="index.php">
                        Home /
                    </a>
                    <a class="text-white" href="category.php">
                        Danh Mục /
                    </a>
                    <?= $category_name_run['name'] ?>
                </h6>
            </div>
        </div>

        <div class="py-5">
            <div class="container">
                <div class="col-md-12">
                    <h1> <?= $category_name_run['name'] ?> </h1>
                    <hr>
                    <div class="">
                        <div class="row justify-content-center g-3 row-cols-1 row-cols-md-2 row-cols-lg-5" id="product-list">

                        </div>
                        <div id="all-products" style="display: none;">
                            <?php
                            $productsArray = [];
                            $product = getProductbyCid('product', $category_id);
                            if (mysqli_num_rows($product) > 0) {
                                foreach ($product as $item) {
                                    $item['average_rating'] = getProductRating($item['id']);
                                    $productsArray[] = $item; 
                                }
                                echo json_encode($productsArray); // Chuyển mảng sản phẩm thành JSON  
                            } else {
                                echo json_encode([]); // Trả về mảng rỗng nếu không có sản phẩm
                            }
                            ?>
                        </div>
                        <!-- Nút Previous và Next để điều hướng giữa các trang -->
                         <?php $product = getProductbyCid('product', $category_id);
                            if (mysqli_num_rows($product) > 0) { ?>
                            <div class="pagination-controls text-center my-4">
                                <button class="btn btn-primary me-2" id="prevBtn" onclick="prevPage()"><i class="ri-arrow-left-s-line"></i></button>
                                <div id="pageNumbers" class="d-inline-block"></div>
                                <button class="btn btn-primary" id="nextBtn" onclick="nextPage()"><i class="ri-arrow-right-s-line"></i></button>
                            </div>
                        <?php  } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php

    } else {
        echo "Không thấy danh mục ";
    }
} elseif (isset($_GET['id'])) {
    $brand_id = $_GET['id'];
    // Lấy thông tin brand
    $brand_query = "SELECT * FROM brand WHERE id = '$brand_id'";
    $brand_result = mysqli_query($con, $brand_query);
    $brand_data = mysqli_fetch_array($brand_result);

    if ($brand_data) {
        ?>
        <div class="py-3 bg-primary">
            <div class="container">
                <h6 class="text-white">
                    <a class="text-white" href="index.php">Home /</a>
                    <a class="text-white" href="category.php">Danh Mục /</a>
                    <?= $brand_data['name'] ?>
                </h6>
            </div>
        </div>

        <div class="py-5">
            <div class="container">
                <div class="col-md-12">
                    <h1><?= $brand_data['name'] ?></h1>
                    <hr>

                    <div class="row justify-content-center g-3 row-cols-1 row-cols-md-2 row-cols-lg-5" id="product-list">

                    </div>
                    <div id="all-products" style="display: none;">
                       <?php
                        $productsArray = [];
                        $products = getProductByBrandId($brand_id);
                        if (mysqli_num_rows($products) > 0) {
                            foreach ($products as $item) {
                                $item['average_rating'] = getProductRating($item['id']);
                                $productsArray[] = $item; 
                                }
                                echo json_encode($productsArray); // Chuyển mảng sản phẩm thành JSON  
                            } else {
                                echo json_encode([]); // Trả về mảng rỗng nếu không có sản phẩm
                            }
                        ?>
                    </div>
                    <!-- Nút Previous và Next -->
                    <?php $product = getProductByBrandId( $brand_id);
                            if (mysqli_num_rows($product) > 0) { ?>
                            <div class="pagination-controls text-center my-4">
                                <button class="btn btn-primary me-2" id="prevBtn" onclick="prevPage()"><i class="ri-arrow-left-s-line"></i></button>
                                <div id="pageNumbers" class="d-inline-block"></div>
                                <button class="btn btn-primary" id="nextBtn" onclick="nextPage()"><i class="ri-arrow-right-s-line"></i></button>
                            </div>
                    <?php  } ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<p>Không tìm thấy thương hiệu!</p>";
    }
} else {
    echo "<p>Danh mục không có ID!</p>";
}

include('includes/footer.php'); ?>
<script>
  const products = JSON.parse(document.getElementById('all-products').textContent);
// Check if the products array is empty
if (products.length === 0) {
    document.getElementById('product-list').innerHTML = '<p class="text-center text-info fs-2 fw-bold">Danh mục rỗng!</p>';
} else {
    const productsPerPage = 8; // Number of products per page
    let currentPage = 1; // Start from the first page
    const totalPages = Math.ceil(products.length / productsPerPage); // Total number of pages

    // Function to display products on the current page
    function displayProducts(page) {
        const startIndex = (page - 1) * productsPerPage;
        const endIndex = startIndex + productsPerPage;
        const paginatedProducts = products.slice(startIndex, endIndex);

        const productList = document.getElementById('product-list');
        productList.innerHTML = '';

        paginatedProducts.forEach(item => {
            const productsaleHTML = item.sale > 0
            ? `<div style="z-index:10" class="sale-badge-product position-absolute"><p class="mb-0">${item.sale}%</p></div>` 
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

    // Function to update pagination
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

        // Update the status of Previous and Next buttons
        document.getElementById('prevBtn').disabled = currentPage === 1;
        document.getElementById('nextBtn').disabled = currentPage === totalPages;
    }

    // Function to go to the next page
    function nextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            displayProducts(currentPage);
        }
    }

    // Function to go to the previous page
    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            displayProducts(currentPage);
        }
    }

    // Display the first page when the page is loaded
    displayProducts(currentPage);
}
</script>
