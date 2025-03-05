<?php 
session_start();
include('includes/header.php'); 
include('function/userfunctions.php');
$id = $_GET['id']; ?>

<!-- Breadcrumb Section -->
<div class="py-3 bg-primary">
    <div class="container">
        <h6 class="text-white">
            <a class="text-white" href="index.php">
                Home /
            </a>
            Bài viết
        </h6>
    </div>
</div>

<!-- Content Section -->
<div class="py-5">
    <div class="row m-0 justify-content-center">
        <?php
        // Fetch content by ID
        $content = getId('ads', $id);
        if ($content && mysqli_num_rows($content) > 0) {
            foreach($content as $item) { ?>
                <div class="col-md-9">
                    <!-- Title -->
                    <h3 class="text-center mb-4 font-weight-bold text-primary"><?=$item['name_ads']?></h3>

                    
                    <!-- Image -->
                    <div class="text-center mb-4">
                        <img class="img-fluid rounded" src="./uploads/<?=$item['images']?>" alt="<?=$item['name_ads']?>">
                    </div>
                    
                    <!-- Content -->
                    <p class="text-justify mb-4"><?=$item['content_ads']?></p>
                </div>
            <?php }
        } else {
            echo "<p>No content found.</p>";
        }
        ?>
    </div>
</div>
<div class="py-5 mt-3">
    <div class="container ">
        <h6 class="text-dark mb-2">Bài viết liên quan:</h6>
        <div class="row d-block ">
            <?php 
            $header = getContent();
            if (mysqli_num_rows($header) > 0) {
                foreach ($header as $headers) {  ?>
                    <div class="col-md-9 mb-1  justify-content-center">
                        <span class="d-block" style="font-size: small;">
                            <a href="content.php?id=<?=$headers['id']?>" class="text-decoration-none text-muted text-primary">
                               # <?=$headers['name_ads']?>
                            </a>
                        </span>
                    </div>
            <?php } 
            } ?>
        </div>
    </div>
</div>

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