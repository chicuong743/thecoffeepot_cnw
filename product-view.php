<?php
// session_start();
include('function/userfunctions.php');
include('includes/header.php');

if (isset($_SESSION['auth_user'])) {
    $user_id = $_SESSION['auth_user']['user_id'];
    // Assuming getByUID is a function that fetches user data by user_id
    $user_data = getByUID('users', $user_id);
    
    if (mysqli_num_rows($user_data) > 0) {
        $user = mysqli_fetch_assoc($user_data); // Fetch the user data as an associative array
    } else {
        // Handle case where user data is not found
        $user = [];
    }
}

if (isset($_GET['productid'])) {

    $product_id = $_GET['productid'];
    $product_data = getID('product', $product_id);
    $product = mysqli_fetch_array($product_data);
    if ($product) {
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
                    <?= $product['productName'] ?>



                </h6>
            </div>
        </div>
        <div id="message" class="message"></div>
        <div class="py-4 bg-light">
            <div class="container product_data mt-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="shadow">
                            <img src="uploads/<?= $product['image'] ?>" alt="" class="w-100">
                        </div>

                    </div>
                    <div class="col-md-8 ">
                        <h4 style="font-weight: bold; position: relative; ">
                            <?= $product['productName'] ?>

                            <span class=" col float-end text-danger"
                                style=" font-weight: normal; position: absolute; top: -10px; font-style: italic; font-size: 16px;">
                                <?php if ($product['trending'] == 1) {
                                    echo "Nổi bật";
                                } ?>          
                            </span>
                            <span class=" col float-end text-danger"
                                style=" font-weight: normal; position: absolute; top: 10px; font-style: italic; font-size: 16px;">
                                <?php if ($product['sale'] > 0) {
                                    echo "Sale ".$product['sale']."%";
                                } ?>          
                            </span>
                        </h4>
                        <?php if ($product['sale'] > 0) {  ?>
                            <h5 class=" text-danger mt-3" style="font-weight: bold; "></h5>
                                <span class="text-danger fw-bold fs-5">
                                    <?= number_format($product['price']-$product['price']*$product['sale']/100, 0, ',', '.') ?>VNĐ
                                </span>
                                <span class="text-muted text-decoration-line-through opacity"><?= number_format($product['price'], 0, ',', '.') ?>VNĐ</span>
                            </h5>
                        <?php }else{?>
                            <h5 class=" text-danger mt-3" style="font-weight: bold; ">
                                <?= number_format($product['price'], 0, ',', '.') ?>VNĐ
                            </h5>
                        <?php } ?>    
                        
                        <?php if ($product['quantity'] == 0) {  ?>
                            <h6 class="text-danger text-decoration-underline fw-bold">Hết hàng</h6>
                        <?php }?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group mb-3 " style="width:130px">
                                    <button class="input-group-text tru_btn">-</button>

                                    <input type="text" id="cong" class="form-control text-center input-qty bg-white" value="1" data-value="<?= $product['quantity'] ?>" disabled>

                                    <button class="input-group-text cong_btn">+</button>
                                </div>
                            </div>
                        </div>
                        <?php $average_rating = getProductRating($product_id);?>
                        <div class="d-flex align-items-center mb-2">
                            <div class="stars-container ms-3">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    $star_class = $i <= $average_rating ? 'text-warning' : 'text-muted';
                                    echo "<i class='fas fa-star $star_class mr-1 fs-5'></i>";
                                }
                                ?>
                            </div>
                            <span class="ms-3"><?= number_format($average_rating, 1) ?> / 5</span> 
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <button class="btn btn-primary px-4 AddtoBuybtn" value="<?= $product['id'] ?>">
                                    <i class="fa fa-shopping-cart me-2">Mua ngay</i>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary px-4 AddtoCartbtn" value="<?= $product['id'] ?>"> <i
                                        class="fa fa-shopping-cart me-2"> Thêm giỏ hàng</i></button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-danger px-4 AddtoWishlist" value="<?= $product['id'] ?>"> <i
                                        class="fa fa-heart me-2"> Sản phẩm yêu thích</i></button>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <h4 class="fw-bold">Chi tiết sản phẩm</h4>
                            <h6 style="word-wrap: break-word; overflow-wrap: break-word;width: 60ch; ">
                                <?= $product['product_desc'] ?>
                            </h6>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="reviews-container py-4">
                            <h4 class="fw-bold">Đánh giá sản phẩm</h4>
                            <!-- Nút điều khiển -->
                            <div class="d-flex justify-content-start mb-3">
                                <button id="toggle-reviews" class="btn btn-primary me-3">Xem đánh giá sản phẩm</button>
                                <button id="toggle-feedback-form" class="btn btn-secondary">Đánh giá sản phẩm</button>
                            </div>

                            <!-- Danh sách đánh giá -->
                            <div id="reviews-list" class="reviews-list" style="max-height: 400px; overflow-y: auto; display: none;">
                                <?php
                                $reviews = getProductReviews($product_id); 
                                if (mysqli_num_rows($reviews) > 0) {
                                    foreach ($reviews as $review) {
                                        ?>
                                        <div class="review-item mb-3 p-3 border rounded shadow">
                                            <div class="d-flex align-items-center mb-2">
                                                <h5 class="fw-bold"><?= $review['name'] ?></h5>
                                                <div class="stars-container ms-3">
                                                    <?php
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        $star_class = $i <= $review['rating'] ? 'text-warning' : 'text-muted';
                                                        echo "<i class='fas fa-star $star_class mr-1 fs-5'></i>";
                                                    }
                                                    ?>
                                                </div>
                                                <span class="ms-3"><?= date("d-m-Y H:i", strtotime($review['created_at'])) ?></span>
                                            </div>
                                            <p><strong>Nhận xét:</strong> <?= $review['note'] ?></p>
                                            <?php if ($review['image']) { ?>
                                                <img src="<?= $review['image'] ?>" alt="Review Image" class="img-fluid" style="max-width: 150px;">
                                            <?php } ?>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "<p>Chưa có đánh giá cho sản phẩm này.</p>";
                                }
                                ?>
                            </div>

                            <!-- Form đánh giá sản phẩm -->
                            <div id="feedback-form" style="display: none;">
                                <form action="function/userhelp.php" method="POST" enctype="multipart/form-data" class="p-4 mt-4 border rounded shadow">
                                    <h4 class="text-center mb-4 text-primary font-weight-bold">Đánh giá sản phẩm</h4>
                                    <input type="hidden" value="<?= $product_id ?>" name="id_product">
                                    <div class="form-group">
                                        <input type="hidden" value="<?= $user['id_user'] ?>" name="id_user">
                                        <label for="name" class="font-weight-bold">Tên</label>
                                        <input type="text" class="form-control" value="<?= isset($user['name']) ? $user['name'] : '' ?>" name="name" placeholder="Nhập tên của bạn..." required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="font-weight-bold">Số điện thoại</label>
                                        <input type="text" class="form-control" value="<?= isset($user['phone']) ? $user['phone'] : '' ?>" name="phone" placeholder="Nhập số điện thoại của bạn..." required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="font-weight-bold">Email</label>
                                        <input type="email" class="form-control" value="<?= isset($user['email']) ? $user['email'] : '' ?>" name="email" placeholder="Nhập email của bạn..." required>
                                    </div>
                                    <div class="form-group">
                                        <label for="rating" class="font-weight-bold">Đánh giá</label>
                                        <div class="rating d-flex align-items-center">
                                            <input type="hidden" id="ratingInput" name="rating" value="5">
                                            <i class="fas fa-star text-muted star-icon mr-1 fs-3" data-value="1"></i>
                                            <i class="fas fa-star text-muted star-icon mr-1 fs-3" data-value="2"></i>
                                            <i class="fas fa-star text-muted star-icon mr-1 fs-3" data-value="3"></i>
                                            <i class="fas fa-star text-muted star-icon mr-1 fs-3" data-value="4"></i>
                                            <i class="fas fa-star text-muted star-icon mr-1 fs-3" data-value="5"></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="note" class="font-weight-bold">Nhận xét</label>
                                        <textarea name="note" class="form-control" rows="4" placeholder="Nhập nhận xét của bạn..." required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Hình ảnh</label>
                                        <input type="file" class="form-control mb-2" name="image">
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="submit" name="add-feedback" class="btn btn-primary w-100">Gửi đánh giá</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
        </div>

        <?php
    } else {
        ?>
        <div class="card card-body text-center shadow" style=" height: 100vh;">
            <h4 class="py-3 text-danger fs-1 fw-bold" >Sản phẩm không tồn tại!</h4>
        </div>
        <?php

    }
    ?>
   
    <?php
} else {
    echo "Bị lỗi rồi!";
}?>

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

<?php include('includes/footer.php'); ?>
<script>
    //feedback
    const stars = document.querySelectorAll('.star-icon');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach((star) => {
        star.addEventListener('click', function () {
            const selectedRating = this.getAttribute('data-value');
            ratingInput.value = selectedRating;

            // Reset all stars to muted
            stars.forEach((s) => s.classList.remove('text-warning', 'text-muted'));
            stars.forEach((s) => s.classList.add('text-muted'));

            // Highlight stars up to the selected one
            for (let i = 0; i < selectedRating; i++) {
                stars[i].classList.remove('text-muted');
                stars[i].classList.add('text-warning');
            }
        });
    });
</script>
<script>//ẩn hiện feedback 
    document.getElementById('toggle-reviews').addEventListener('click', function () {
        var reviewsList = document.getElementById('reviews-list');
        var feedbackForm = document.getElementById('feedback-form');
        feedbackForm.style.display = 'none';

        if (reviewsList.style.display === 'none') {
            reviewsList.style.display = 'block';
            reviewsList.scrollIntoView({ behavior: 'smooth' });
            this.textContent = 'Ẩn đánh giá sản phẩm';
        } else {
            reviewsList.style.display = 'none';
            this.textContent = 'Xem đánh giá sản phẩm';
        }
    });

    document.getElementById('toggle-feedback-form').addEventListener('click', function () {
        var feedbackForm = document.getElementById('feedback-form');
        var reviewsList = document.getElementById('reviews-list');
        reviewsList.style.display = 'none';

        if (feedbackForm.style.display === 'none') {
            feedbackForm.style.display = 'block';
            feedbackForm.scrollIntoView({ behavior: 'smooth' }); 
            this.textContent = 'Ẩn form đánh giá';
        } else {
            feedbackForm.style.display = 'none';
            this.textContent = 'Đánh giá sản phẩm';
        }
    });
</script>
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
                            <img src="uploads/${item.image}" alt="" class="card-img-top img-fluid w-100" style="z-index: 10; height: 180px; object-fit: cover;">
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

