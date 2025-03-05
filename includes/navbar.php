<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow" style="background-color:rgb(109, 83, 64);">
  <div class="container">
    <a class="navbar-brand py-0" href="index.php"><img style="padding:0px; width: 70px;" src="assets/images/logos.png" alt="The Coffee Pot"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
      aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <!-- search start -->
      <form action="search.php" method="GET" class="search d-flex ms-auto">
        <input class=" form-control mr-sm-2" type="text" name="query" placeholder="Tìm kiếm sản phẩm..."
          aria-label="Search" >
        <button class="btn btn-outline-light bg-secondary ms-1" type="submit"><p>Tìm kiếm</p><i class="fas fa-search"></i></button>
      </form>
      <!-- search end -->
      <ul class="menu  navbar-nav ms-auto">

        <li class="nav-item ">
          <a class="nav-link text-white active" aria-current="page" href="index.php">Trang chủ</a>
        </li>
        <li class="nav-item">
          <a class=" nav-link" href="category.php">Tất cả sản phẩm </a>
        </li>

        <li class="nav-item">
          <a class=" nav-link" href="about.php">Giới thiệu</a>
        </li>


        <?php
        if (isset($_SESSION['auth'])) {
          ?>
          <li class="nav-item dropdown ml-5">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <?= $_SESSION['auth_user']['name']; ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="my-orders.php">Đơn hàng của bạn</a></li>
              <li><a class="dropdown-item" href="wishlist.php">Danh sách yêu thích</a></li>
              <li><a class="dropdown-item" href="account.php">Thông tin cá nhân</a></li>
              <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
            </ul>
          </li>

          <?php
          }         
        else {
          ?>
          <li class="nav-item">
            <a class="nav-link " href="register.php">Đăng Ký</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Đăng Nhập</a>
          </li>
          <?php
        }
        ?>
        <li class="nav-item">
          <a class="nav-link " href="cart.php"><i class="fas fa-shopping-cart "></i> <span id="cart-item"
              class="badge badge-danger"></span></a>
        </li>

      </ul>
    </div>
  </div>
</nav>
