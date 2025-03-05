<?php
//session_start();

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
?>

<div class="py-3 bg-primary">
    <div class="container">
        <h6 class="text-white ">
            <a class="text-white" href="index.php">
                Home /
            </a>
            <a class="text-white" href="cart.php">
                Thanh Toán
            </a>
        </h6>
    </div>
</div>



<div class="py-5">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="function/placeorder.php" method="POST">
                    <div id="message"></div>
                    <div class="row">
                        <div class="col-md-7">
                            <h5>Vận chuyển</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="fw-bold">Họ và tên</label>
                                    <input type="text" name="name" id="name" value="<?= isset($user['name']) ? $user['name'] : '' ?>" required placeholder="Nhập tên..."
                                        class="form-control">
                                    <small class="text-danger name"></small>
                                </div>
                                <div class="col-md-7 mb-3">
                                    <label class="fw-bold">Email</label>
                                    <input type="email" name="email" id="email" value="<?= isset($user['email']) ? $user['email'] : '' ?>" required placeholder="Nhập email..."
                                        class="form-control">
                                    <small class="text-danger email"></small>
                                </div> 
                                <div class="col-md-5 mb-3">
                                    <label class="fw-bold">Số điện thoại</label>
                                    <input type="number" name="phone" id="phone" value="<?= isset($user['phone']) ? $user['phone'] : '' ?>" required placeholder="Nhập SĐT..."
                                        class="form-control">
                                    <small class="text-danger phone"></small>
                                </div>
                                <div class="col-md-12  mb-3">
                                    <label class="fw-bold">Địa chỉ</label>
                                    <div class="car-section-right-select">
                                        <div class="col-md-4 ">
                                            <select name="city" id="city">
                                            <option value="" >Tỉnh/Tp</option>
                                            </select>
                                            <small class="text-danger city"></small>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="district" id="district">
                                                <option value="">Quận/Huyện</option>
                                            </select>
                                            <small class="text-danger district"></small>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="ward" id="ward">
                                                <option value="">Phường/Xã</option>
                                            </select>
                                            <small class="text-danger ward"></small>
                                        </div>
                                    </div>
                                    <textarea type="text" name="address" id="address" rows="6" required
                                        placeholder="Nhập địa chỉ..." class="form-control"></textarea>
                                    <small class="text-danger address"></small>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="fw-bold">Hình thức vận chuyển:</label>
                                    <select class="form-select" name="shipping" id="shippingSelect">
                                        <option selected disabled data-price="<?= $defaultShippingPrice ?>" >Chọn đơn vị vận chuyển </option>
                                        <?php
                                        $shipping = getAll("shipping_unit");
                                        if (mysqli_num_rows($shipping) > 0) {
                                            foreach ($shipping as $item) {
                                                ?>
                                                <option value="<?= $item['id']; ?>" data-name-ship="<?= $item['name_ship']; ?>" data-price="<?= $item['price']; ?>">
                                                    <?= $item['name_ship']; ?>
                                                </option>
                                               
                                                <?php
                                            }
                                        } 
                                        ?>
                                    </select>
                                    <small class="text-danger shipping"></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h5>Sản phẩm mua</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-2">
                                    <label style="font-weight: bold;">Ảnh</label>
                                </div>
                                <div class="col-md-5">
                                    <label style="font-weight: bold;">Tên</label>
                                </div>
                                <div class="col-md-2">
                                    <label style="font-weight: bold;">SL</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="font-weight: bold;">Giá</label>
                                </div>
                            </div>
                            <?php
                            $items = getCartItems();
                            $totalPrice = 0;
                            foreach ($items as $citem) {
                                ?>
                                <div class="card product_data shadow mb-2">
                                    <div class="row align-items-center mt-3">
                                        <div class="col-md-2">
                                            <img class="mb-3 " src="uploads/<?= $citem['image'] ?>" alt="image" width="40px"
                                                height="50px" style="margin-left : 1rem;">
                                        </div>
                                        <div class="col-md-5">
                                            <label for="">
                                                <?= $citem['productName'] ?>
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">X
                                                <?= $citem['prod_qty'] ?>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">
                                                <?= number_format(($citem['price']-($citem['price']*$citem['sale']/100))*$citem['prod_qty'], 0, ',', '.') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php

                                $totalPrice += ($citem['price']-($citem['price']*$citem['sale']/100))*$citem['prod_qty'];

                            }
                            ?>
                            <!-- <input type="text" id="totalPriceInput" value="<?= $totalPrice; ?>" readonly hidden> -->
                            <div id="shippingInfo">
                                <h5 style="font-weight: bold;"> Vận chuyển: <span class="float-end" id="selectedShipping">Mặc Định</span></h5>
                            </div>
                            <div id="shippingPrice">
                                <h5 style="font-weight: bold;"> Phí vận chuyển: <span class="float-end" id="selectedShippingPrice">
                                    <?= number_format(150000, 0, ',', '.') ?> VNĐ
                                </span></h5>
                            </div>
                            <div>
                                <h5 style="font-weight: bold;"> Tổng thanh toán: <span  id="Toatal_Price_Ship" class="float-end">
                                    <?= number_format($totalPrice + 150000, 0, ',', '.') ?> VNĐ
                                </span></h5>
                            </div>
                            <div>
                                <div id="cod-button-container" class="mt-2">
                                    <input type="hidden" id="payment-mode" name="payment_mode" value="">
                                    <button type="submit" id="COD" class="btn btn-outline-info w-100 fw-bold">
                                        Thanh toán khi nhận hàng
                                    </button>
                                </div>
                                <!--div id="momo-button-container" class="mt-2">
                                    <button type="submit" id="MOMO"  name="payUrl" class="btn btn-outline-info w-100 fw-bold">
                                        Thanh toán qua MoMo<img src="assets/images/momo.png" style="width:25px;">
                                    </button> 
                                </div-->     
                                <div id="paypal-button-container" class="mt-2"></div>                                                      
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const shippingSelect = document.getElementById("shippingSelect");
        const selectedShipping = document.getElementById("selectedShipping");
        const shippingPrice = document.getElementById("selectedShippingPrice");
        const totalPriceShip = document.getElementById("Toatal_Price_Ship");

        // Giá trị mặc định ban đầu (nếu cần thiết)
        let defaultShipping = {
            name: "Mặc Định",
            price: 150000 // Giá mặc định
        };

        function updateShippingInfo() {
            const selectedOption = shippingSelect.options[shippingSelect.selectedIndex];
            const shippingName = selectedOption.getAttribute("data-name-ship");  // Lấy tên phương thức vận chuyển
            const shippingCost = parseInt(selectedOption.getAttribute("data-price")) || defaultShipping.price; // Lấy giá phương thức vận chuyển, nếu không có sẽ dùng giá mặc định

            // Cập nhật tên phương thức vận chuyển
            selectedShipping.innerText = shippingName ? shippingName : defaultShipping.name;

            // Cập nhật phí vận chuyển
            shippingPrice.innerText = new Intl.NumberFormat('vi-VN').format(shippingCost) + ' VNĐ';

            // Cập nhật tổng giá
            const basePrice = <?= $totalPrice; ?>;
            const totalCost = basePrice + shippingCost;
            totalPriceShip.innerText = new Intl.NumberFormat('vi-VN').format(totalCost) + ' VNĐ';
        }

        // Thêm event listener cho dropdown thay đổi
        shippingSelect.addEventListener("change", updateShippingInfo);

        // Chạy hàm để cập nhật hiển thị mặc định
        updateShippingInfo();
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>

<?php include('includes/footer.php'); ?>
<!--PAY_PAl-->
<script src="https://www.paypal.com/sdk/js?client-id=ATWP73Ng6Fydj8-L_YRWrQ1nQFQx2p5xLWFMdH0t9_6LTwdLhp-M-_yERwmmk8YQ6T2jCoKKUJ8oPOLy&currency=USD"></script>

<!--API ADDRESS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="assets/js/apiprovince.js"></script>
<script>
    // Hiển thị loader khi bắt đầu xử lý thanh toán
    function showLoader() {
        $('.loader').removeClass('loader-hidden');
    }
    $(document).ready(function() {
    // Khi nhấn vào nút Thanh toán khi nhận hàng
    $('#COD').click(function() {
        $('#payment-mode').val('Thanh toán khi nhận hàng');
        showLoader();
    });
    // Khi nhấn vào nút Thanh toán qua MoMo
    $('#MOMO').click(function() {
        $('#payment-mode').val('Thanh toán qua MoMo');
        showLoader();
    });
    // Khi nhấn vào nút Thanh toán qua VNPay
    $('#paypal-button-container').click(function() {
        $('#payment-mode').val('Thanh toán qua PayPal');
        showLoader();
    });
});
</script>
<script>
//<!--API ADDRESS-->
var citis = document.getElementById("city");
var districts = document.getElementById("district");
var wards = document.getElementById("ward");
var Parameter = {
  url: "https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json",
  method: "GET",
  responseType: "application/json",
};
var promise = axios(Parameter);
promise.then(function (result) {
  renderCity(result.data);
});

function renderCity(data) {
  for (const x of data) {
	var opt = document.createElement('option');
	 opt.value = x.Name;
	 opt.text = x.Name;
	 opt.setAttribute('data-id', x.Id);
	 citis.options.add(opt);
  }
  citis.onchange = function () {
    district.length = 1;
    ward.length = 1;
    if(this.options[this.selectedIndex].dataset.id != ""){
      const result = data.filter(n => n.Id === this.options[this.selectedIndex].dataset.id);

      for (const k of result[0].Districts) {
		var opt = document.createElement('option');
		 opt.value = k.Name;
		 opt.text = k.Name;
		 opt.setAttribute('data-id', k.Id);
		 district.options.add(opt);
      }
    }
  };
  district.onchange = function () {
    ward.length = 1;
    const dataCity = data.filter((n) => n.Id === citis.options[citis.selectedIndex].dataset.id);
    if (this.options[this.selectedIndex].dataset.id != "") {
      const dataWards = dataCity[0].Districts.filter(n => n.Id === this.options[this.selectedIndex].dataset.id)[0].Wards;

      for (const w of dataWards) {
		var opt = document.createElement('option');
		 opt.value = w.Name;
		 opt.text = w.Name;
		 opt.setAttribute('data-id', w.Id);
		 wards.options.add(opt);
      }
    }
  };
}
//PAY_PAl
// PAY_PAL
paypal.Buttons({
    style: {
        layout: 'vertical',
        color: 'blue',
        shape: 'rect',
        label: 'paypal',
        height: 40,
    },
    onClick() {
        showLoader();
        // Xử lý sự kiện khi nhấn nút thanh toán
        var name = $('#name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var ward = $('#ward').val();
        var address = $('#address').val();
        
        // Kiểm tra các trường thông tin có bị trống hay không
        if (name.length == 0) {
            $('.name').text("Tên không được bỏ trống!");
        } else {
            $('.name').text("");
        }
        if (email.length == 0) {
            $('.email').text("Email không được bỏ trống!");
        } else {
            $('.email').text("");
        }
        if (phone.length == 0) {
            $('.phone').text("SĐT không được bỏ trống!");
        } else {
            $('.phone').text("");
        }
        if (city.length == 0) {
            $('.city').text("Tỉnh/Thành phố không được bỏ trống!");
        } else {
            $('.city').text("");
        }
        if (district.length == 0) {
            $('.district').text("Quận/Huyện không được bỏ trống!");
        } else {
            $('.district').text("");
        }
        if (ward.length == 0) {
            $('.ward').text("Xã/Phường không được bỏ trống!");
        } else {
            $('.ward').text("");
        }
        if (address.length == 0) {
            $('.address').text("Địa chỉ không được bỏ trống!");
        } else {
            $('.address').text("");
        }

        if (name.length == 0 || email.length == 0 || phone.length == 0 || city.length == 0 || district.length == 0 || ward.length == 0 || address.length == 0) {
            return false; // Ngăn không cho tiếp tục nếu có trường bị trống
        }
    },

    createOrder: function (data, actions) {
        // Tạo đơn hàng với PayPal
            var totalPriceVND = <?= $totalPrice ?>; 
            var shippingCost = parseInt($('#shippingSelect option:selected').attr('data-price')) || 150000; 
            var totalPriceWithShipping = totalPriceVND + shippingCost;  

            var exchangeRate = 24000; // Tỷ giá VND sang USD
            var totalPriceUSD = (totalPriceWithShipping / exchangeRate).toFixed(2); 

        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: totalPriceUSD
                }
            }]
        });
    },
    
    onApprove: function (data, actions) {
        // Khi thanh toán thành công
        return actions.order.capture().then(function (orderData) {
            console.log('Kết quả thanh toán', orderData, JSON.stringify(orderData, null, 2));
            var transaction = orderData.purchase_units[0].payments.captures[0];
            //alert('Giao dịch ' + transaction.status + ': ' + transaction.id + '\n\nXem dữ liệu');
            var data = {
                'name': $('#name').val(),
                'email': $('#email').val(),
                'phone': $('#phone').val(),
                'city': $('#city').val(),
                'district': $('#district').val(),
                'ward': $('#ward').val(),
                'address': $('#address').val(),
                'shipping': $('#shippingSelect').val()|| 12,
                'payment_mode': "Thanh toán qua PayPal",
                'payment_id': transaction.id,
                'placeOrderBtn': true
            };
            console.log(data);
            var userType = '<?php echo isset($_SESSION["auth"]) ? "auth" : "guest"; ?>';
            if (userType === "auth") {
                window.location.href = "my-orders.php?payment_id=" + transaction.id;
            } else {
                window.location.href = "thank-you.php?payment_id=" + transaction.id;
            }
            $.ajax({
                type: "POST",
                url: "function/placeorder.php",
                data: data,
                success: function (response) {
                    console.log("Phản hồi:", response);
                    // Xử lý sau khi đơn hàng được lưu thành công nếu cần
                },
                error: function (xhr, status, error) {
                    alert("Lỗi AJAX: " + error);
                }
            });
        });
    },
    
    onCancel: function (data, actions) {
        window.location.href='checkout.php';
    }

    
}).render('#paypal-button-container');

function showLoader() {
    $('').removeClass('loader-hidden');
    }
    

</script>