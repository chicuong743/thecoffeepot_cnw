$(document).ready(function () {
    $('.delete_product_btn').click(function (e) {
        $(document).on('click', '.delete_product_btn', function (e) {
            e.preventDefault();
            var id = $(this).val();
            swal({
                title: "Bạn có thực sự muốn xóa?",
                text: "Khi xóa, sẽ không thể phục hồi",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method: "POST",
                            url: "code.php",
                            data: {
                                'product_id': id,
                                'delete_product_btn': true
                            },
                            success: function (response) {
                                console.log(response);
                                if (response = 200) {

                                    swal("Thành công!", "Xóa sản phẩm thành công", "success");
                                    $("#product_table").load(location.href + " #product_table");
                                } else if (response = 500) {
                                    swal("Thất bại!", "Xóa sản phẩm thất bại", "error");
                                }

                            }
                        });
                    }
                });
        });

    });
});


