<div id="carouselExampleFade1" class="carousel slide carousel-fade" data-bs-interval="3000">
  <div class="carousel-inner">
  <?php
      $slider = getSliderSale();  
      $firstItem = true;  // Biến để đánh dấu ảnh đầu tiên
      if (mysqli_num_rows($slider) > 0){
        foreach ($slider as $sliders) { 
          // Nếu là ảnh đầu tiên, gán lớp active
          $activeClass = $firstItem ? 'active' : '';
          $firstItem = false;   ?>
          <div class="carousel-item <?= $activeClass ?>">
            <img  style="height:550px; aspect-ratio: 3/4;" src="./uploads/<?=$sliders['images']?>"  class="d-block w-100" alt="...">
          </div>
      <?php }
          } ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade1" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade1" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
