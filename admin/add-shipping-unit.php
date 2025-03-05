<?php
include('includes/header.php');
include('../middleware/adminMiddleware.php');

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Thêm đơn vị vận chuyển
                    <a href="shipping-unit.php" class="btn btn-warning float-end"><i class="fa fa-reply"></i> Trở
                            về</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="process.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Đơn vị vận chuyển</label>
                                <input type="text" class="form-control mb-2" name="name_ship"
                                    placeholder="Nhập vào đơn vị vận chuyển...">
                            </div>
                            <div class="col-md-12">
                                <label for="">Giá tiền</label>
                                <input type="number" class="form-control mb-2" name="price"
                                    placeholder="Nhập vào giá...">
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" name="add_shipping_btn"> Lưu</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include('includes/footer.php');
?>
<script>
    document.querySelector("form").addEventListener("submit", function (e) {
        const nameShip = document.querySelector("[name='name_ship']").value.trim();
        const price = document.querySelector("[name='price']").value.trim();
        if (!nameShip) {
            e.preventDefault(); 
            alert("Tên đơn vị vận chuyển không được để trống.");
            return;
        }
        if (!price) {
            e.preventDefault(); 
            alert("Giá tiền không được để trống.");
            return; 
        }
    });
</script>