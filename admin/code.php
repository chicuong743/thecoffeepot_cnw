<!-- 
<?php
session_start();
include('config/dbcon.php');
include('../function/myfunctions.php');


if (isset($_POST['add_category_btn'])) {
    $name = $_POST['name'];
    $image = $_FILES['image']['name'];

    $path = "../uploads"; // Thư mục lưu trữ hình ảnh
    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext;

    // Trước tiên, kiểm tra xem tên danh mục đã tồn tại trong cơ sở dữ liệu chưa
    $check_query = "SELECT * FROM category WHERE name = '$name'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Tên danh mục đã tồn tại, xuất thông báo lỗi
        redirect("add-category.php", "Danh mục đã tồn tại");
    } else {
        // Tên danh mục chưa tồn tại, thêm vào cơ sở dữ liệu
        $category_query = "INSERT INTO category (name, image) VALUES ('$name', '$filename')";
        $category_query_run = mysqli_query($con, $category_query);

        if ($category_query_run) {
            move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);

            redirect("add-category.php", "Thêm danh mục thành công!");
        } else {
            redirect("add-category.php", "Thêm danh mục thất bại!");
        }
    }
}

else if (isset($_POST['edit_category_btn'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $image = $_FILES['image']['name'];
    $path = "../uploads";
    $old_image = $_POST['old_image'];

    // Kiểm tra xem tên danh mục có bị trùng không
    $check_query = "SELECT * FROM category WHERE name = '$name' AND id != '$category_id'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Tên danh mục đã tồn tại, xuất thông báo lỗi
        redirect("edit-category.php?id=$category_id", "Tên danh mục đã tồn tại");
    } else {
        $update_filename = ''; // Biến để lưu tên tệp ảnh mới

        // Kiểm tra xem có tải lên ảnh mới không
        if ($_FILES['image']['name'] != "") {
            $image_ext = pathinfo($image, PATHINFO_EXTENSION);
            $update_filename = time() . '.' . $image_ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $update_filename);
            if (file_exists("../uploads/" . $old_image)) {
                unlink("../uploads/" . $old_image);
            }
        } else {
            // Nếu không có ảnh mới, sử dụng ảnh cũ
            $update_filename = $old_image;
        }

        $update_category_query = "UPDATE category SET name = '$name', image = '$update_filename' WHERE id = '$category_id'";
        $update_category_query_run = mysqli_query($con, $update_category_query);

        if ($update_category_query_run) {
            redirect("category.php", "Cập nhật danh mục thành công!");
        } else {
            redirect("edit-category.php?id=$category_id", "Cập nhật danh mục thất bại");
        }
    }
}
else if (isset($_POST['delete_category'])) {
    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);

    // Lấy tên tệp ảnh từ cơ sở dữ liệu
    $image_query = "SELECT image FROM category WHERE id = '$category_id'";
    $image_result = mysqli_query($con, $image_query);
    $image_data = mysqli_fetch_assoc($image_result);
    $image_to_delete = $image_data['image'];

    // Xóa danh mục và ảnh cùng nhau
    $delete_query = "DELETE FROM category WHERE id = '$category_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        // Xóa ảnh từ thư mục uploads
        if (file_exists("../uploads/" . $image_to_delete)) {
            unlink("../uploads/" . $image_to_delete);
        }
        redirect("category.php", "Xóa danh mục thành công");
    } else {
        redirect("category.php", "Xóa danh mục  thất bại");
    }
}

//end xóa danh mục



// ---------------------------------------------------------------------------------

// Thêm thương hiệu
else if (isset($_POST['add_brand_btn'])) {
    $name = $_POST['name'];

    // Trước tiên, kiểm tra xem $name có rỗng không
    if (empty($name)) {
        redirect("add-brand.php", "Tên phân loại không được bỏ trống");
    } else {
        // Sau đó, kiểm tra xem tên đã tồn tại trong bảng brand chưa
        $check_query = "SELECT * FROM brand WHERE name = '$name'";
        $check_result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Tên đã tồn tại, xuất thông báo lỗi
            redirect("add-brand.php", "phân loại đã tồn tại");
        } else {
            // Tên chưa tồn tại, thêm vào cơ sở dữ liệu
            $brand_query = "INSERT INTO brand (name) VALUES ('$name')";
            $brand_query_run = mysqli_query($con, $brand_query);

            if ($brand_query_run) {
                redirect("add-brand.php", "Lưu phân loại thành công");
            } else {
                redirect("add-brand.php", "Lưu phân loại không thành công");
            }
        }
    }
}

// sửa thương hiệu
else if (isset($_POST['edit_brand_btn'])) {
    $name = $_POST['name'];
    $brand_id = $_POST['brand_id'];


    //kiem tra ten thương hiệu

    $check_query = "select * from brand where name = '$name'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        redirect("edit-brand.php?id=$brand_id", "Tên phân loại đã tồn tại");
    } else {
        $update_query = "update brand set name = '$name' where id = '$brand_id' ";
        $update_query_run = mysqli_query($con, $update_query);
        if ($update_query_run) {
            redirect("brand.php", "Cập nhật phân loại thành công!");

        } else {
            redirect("edit-brand.php?id=$brand_id", "Cập nhật phân loại thất bại");
        }

    }

}
//end sửa thuong hiệu
//xóa thương hiệu
else if (isset($_POST['delete_brand'])) {

    $brand_id = mysqli_real_escape_string($con, $_POST['brand_id']);

    $delete_query = "delete from brand where id = '$brand_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        redirect("brand.php", "Xóa phân loại thành công");
    } else {
        redirect("brand.php", "Xóa phân loại thất bại");
    }
}
//end xóa thương hiệu

// thêm sản phẩm
else if (isset($_POST['add_product_btn'])) {
    $name = $_POST['name'];
    $catid = $_POST['catid'];
    $desc = $_POST['desc'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $trending = isset($_POST['trending']) ? '1' : '0';
    $sale = $_POST['sale'];
    $brandid = $_POST['brandid'];  
    

    // Kiểm tra xem sale có hợp lệ không (từ 0 đến 100)
    if ($sale < 0 || $sale > 100) {
        redirect("add-product.php", "Sale phải nằm trong khoảng từ 0 đến 100.");
        exit;
    }

    // Xử lý hình ảnh
    $image = $_FILES['image']['name'];
    $path = "../uploads";
    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_ext;
    if (empty($name) || empty($catid) || empty($desc) || empty($price) ||empty($filename) ||empty($quantity)) {
        redirect("add-product.php", "Vui lòng nhập đầy đủ thông tin sản phẩm ");
        exit();
    }
    // Kiểm tra tên sản phẩm đã tồn tại trong cơ sở dữ liệu chưa
    $check_query = "SELECT * FROM product WHERE productName = '$name'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Tên sản phẩm đã tồn tại, xuất thông báo lỗi
        redirect("add-product.php", "Tên sản phẩm đã tồn tại");
    } else {
        // Thêm sản phẩm vào cơ sở dữ liệu
        $product_query = "INSERT INTO product (productName, catid, product_desc, image, quantity, trending, price, sale) 
                          VALUES ('$name', '$catid', '$desc', '$filename', '$quantity', '$trending', '$price', '$sale')";
        $product_query_run = mysqli_query($con, $product_query);

        if ($product_query_run) {
            // Lưu hình ảnh
            move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);

            // Lấy ID của sản phẩm vừa thêm
            $product_id = mysqli_insert_id($con); 

            // Xử lý mảng brandid (có thể chọn nhiều)
            if (!empty($brandid) && is_array($brandid)) {
                foreach ($brandid as $brand_id) {
                    $brand_query = "INSERT INTO product_brands (product_id, brand_id) VALUES ('$product_id', '$brand_id')";
                    mysqli_query($con, $brand_query);
                }
            }

            redirect("add-product.php", "Thêm sản phẩm thành công!");exit();
        } else {
            redirect("add-product.php", "Thêm sản phẩm thất bại!");exit();
        }
    }
}

//end thêm sản phẩm 


//xóa sản phẩm
else if (isset($_POST['delete_product_btn'])) {

    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);

    $product_query = "SELECT * FROM product WHERE id='$product_id'";
    $product_query_run = mysqli_query($con, $product_query);
    $product_data = mysqli_fetch_array($product_query_run);
    $image = $product_data['image'];


    $delete_query = "delete from product where id = '$product_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        if (file_exists("../uploads/" . $image)) {
            unlink("../uploads/" . $image);
        }
        // redirect("product.php", "Xóa sản phẩm thành công");
        echo 200;
    } else {
        // redirect("product.php", "Xóa sản phẩm thất bại");
        echo 500;
    }
}
//end xóa sản phẩm

else if (isset($_POST['update_product_btn'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $catid = $_POST['catid'];
    $desc = $_POST['desc'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $trending = isset($_POST['trending']) ? '1' : '0';
    $sale = $_POST['sale'];
    $brandid = $_POST['brandid']; // Mảng brand_id
    $old_image = $_POST['old_image'];
    $new_image = $_FILES['image']['name'];

    // Kiểm tra sale hợp lệ
    if ($sale < 0 || $sale > 100) {
        redirect("edit-product.php?id=$product_id", "Sale phải nằm trong khoảng từ 0 đến 100.");
        exit;
    }

    $filename = $old_image;
    if (!empty($new_image)) {
        $image_ext = pathinfo($new_image, PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $filename);
    }

    $update_query = "UPDATE product SET productName='$name', catid='$catid', product_desc='$desc', image='$filename', quantity='$quantity', trending='$trending', price='$price', sale='$sale' WHERE id='$product_id'";
    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        mysqli_query($con, "DELETE FROM product_brands WHERE product_id='$product_id'");
        if (!empty($brandid) && is_array($brandid)) {
            foreach ($brandid as $brand_id) {
                mysqli_query($con, "INSERT INTO product_brands (product_id, brand_id) VALUES ('$product_id', '$brand_id')");
            }
        }
        redirect("edit-product.php?id=$product_id", "Cập nhật sản phẩm thành công!");
    } else {
        redirect("edit-product.php?id=$product_id", "Cập nhật sản phẩm thất bại!");
    }
}



//ADS
else if(isset($_POST['add_ads_btn'])){
    $name_ads = $_POST['name_ads'];
    $content_ads = $_POST['content_ads'];
    $type_ads = $_POST['type_ads'];
    $status = $_POST['status']? $_POST['status'] : 0;

    // Xử lý file hình ảnh
    if(isset($_FILES['images']) && $_FILES['images']['error'] == 0){
        $image = $_FILES['images']['name'];
        $image_tmp = $_FILES['images']['tmp_name'];
        $image_folder = "../uploads/" . $image;

        // Di chuyển ảnh vào thư mục uploads
        move_uploaded_file($image_tmp, $image_folder);
    }

    // Lưu vào database
    $query = "INSERT INTO ads (name_ads, content_ads, type_ads, images, status) 
              VALUES ('$name_ads', '$content_ads', '$type_ads', '$image', '$status')";

    $result = mysqli_query($con, $query);

    if($result){
        $_SESSION['message'] = "Thêm ADS thành công";
        header("Location: ads.php");
    } else {
        $_SESSION['message'] = "Có lỗi khi thêm ADS";
        header("Location: add-ads.php");
    }
}
else if (isset($_POST['delete_ads_btn'])) {
    $ads_id = mysqli_real_escape_string($con, $_POST['ads_id']);
    // Truy vấn lấy thông tin ads từ cơ sở dữ liệu
    $ads_query = "SELECT * FROM ads WHERE id = '$ads_id'";
    $ads_query_run = mysqli_query($con, $ads_query);

    // Kiểm tra nếu ads tồn tại
    if ($ads_query_run && mysqli_num_rows($ads_query_run) > 0) {
        $ads_data = mysqli_fetch_array($ads_query_run);
        $images = $ads_data['images']; // Lấy tên file ảnh của ads

        // Xóa ads khỏi cơ sở dữ liệu
        $delete_query = "DELETE FROM ads WHERE id = '$ads_id'";
        $delete_query_run = mysqli_query($con, $delete_query);

        if ($delete_query_run) {
            // Nếu ads có file ảnh và file tồn tại, tiến hành xóa file
            if (file_exists("../uploads/" . $images)) {
                unlink("../uploads/" . $images);
            }
        } else {
            echo 200; // Thành công
        }
    } else {
        echo 500; // Không tìm thấy ads trong database
    }
    exit();
}


else if (isset($_POST['update_ads_btn'])) {
    $ad_id = $_POST['ad_id'];
    $name_ads = mysqli_real_escape_string($con, $_POST['name_ads']);
    $content_ads = mysqli_real_escape_string($con, $_POST['content_ads']);
    $type_ads = $_POST['type_ads'];
    $status = isset($_POST['status']) ? 1 : 0;

    // Xử lý ảnh nếu có thay đổi
    $image_name = '';
    if (isset($_FILES['images']) && $_FILES['images']['error'] == 0) {
        // Định nghĩa thư mục lưu ảnh
        $target_dir = "../uploads/";
        $image_name = basename($_FILES['images']['name']);
        $target_file = $target_dir . $image_name;

        // Kiểm tra nếu là ảnh hợp lệ
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_image_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_file_type, $valid_image_types)) {
            // Lấy tên ảnh cũ để xóa nếu có
            $ad_query = "SELECT images FROM ads WHERE id = '$ad_id'";
            $ad_result = mysqli_query($con, $ad_query);
            if (mysqli_num_rows($ad_result) > 0) {
                $ad_data = mysqli_fetch_assoc($ad_result);
                $old_image = $ad_data['images'];
                // Kiểm tra nếu ảnh cũ tồn tại và xóa nó
                $old_image_path = $target_dir . $old_image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

            // Di chuyển file ảnh vào thư mục uploads
            if (!move_uploaded_file($_FILES['images']['tmp_name'], $target_file)) {
                echo "Lỗi khi upload ảnh.";
                exit;
            }
        } else {
            echo "Định dạng ảnh không hợp lệ.";
            exit;
        }
    } else {
        // Nếu không thay đổi ảnh, giữ nguyên ảnh cũ
        $ad_query = "SELECT images FROM ads WHERE id = '$ad_id'";
        $ad_result = mysqli_query($con, $ad_query);
        if (mysqli_num_rows($ad_result) > 0) {
            $ad_data = mysqli_fetch_assoc($ad_result);
            $image_name = $ad_data['images'];
        }
    }

    // Cập nhật thông tin quảng cáo trong cơ sở dữ liệu
    $update_query = "UPDATE ads SET 
                        name_ads = '$name_ads',
                        content_ads = '$content_ads',
                        type_ads = '$type_ads',
                        images = '$image_name',
                        status = '$status'
                    WHERE id = '$ad_id'";

    if (mysqli_query($con, $update_query)) {
        echo "Cập nhật quảng cáo thành công!";
        header("Location: ads.php"); // Điều hướng về trang danh sách quảng cáo
        exit;
    } else {
        echo "Cập nhật quảng cáo thất bại. Lỗi: " . mysqli_error($con);
    }
}


//Cập nhật trạng thái đơn hàng
else if (isset($_POST['update_order_btn'])) {
    $track_no = $_POST['tracking_no'];
    $order_status = $_POST['order_status'];

    $updateOrder_query = "UPDATE orders SET status = '$order_status' WHERE tracking_no = '$track_no'";
    $updateOrder_query_run = mysqli_query($con, $updateOrder_query);

    redirect("view-orders.php?t=$track_no", "Cập nhật đơn hàng thành công!");

} else {
    header('Location: ../index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_feedback'])) {
    // Lấy ID từ POST
    $id = $_POST['id'] ?? null;

    // Xác định trạng thái từ checkbox
    $status = isset($_POST['status']) ? 1 : 0;

    if ($id !== null) {
        // Cập nhật trạng thái trong database
        $query = "UPDATE feedback SET status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ii", $status, $id);

        if ($stmt->execute()) {
            // Cập nhật thành công, quay lại trang trước
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            echo "Cập nhật trạng thái thất bại.";
        }
        $stmt->close();
    } else {
        echo "Thiếu ID hoặc dữ liệu không hợp lệ.";
    }
} else {
    echo "Yêu cầu không hợp lệ.";
}

?>