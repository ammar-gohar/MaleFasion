<div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
  <div class="product__item">
      <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg">
          <?php if($time() - strtotime($product->created_at) < 60*60*24*14): ?>
            <span class="label">New</span>
          <?php elseif($product->sale_price !== null): ?>
            <span class="label">Sale</span>
          <?php endif; ?>
          <ul class="product__hover">
              <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
              <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
          </ul>
      </div>
      <div class="product__item__text">
          <h6>Piqué Biker Jacket</h6>
          <a class="add-cart">+ Add To Cart</a>
          <div class="rating">
              <i class="fa fa-star-o"></i>
              <i class="fa fa-star-o"></i>
              <i class="fa fa-star-o"></i>
              <i class="fa fa-star-o"></i>
              <i class="fa fa-star-o"></i>
          </div>
          <h5>$67.24</h5>
          <div class="product__color__select">
              <label for="pc-1">
                  <input type="radio" id="pc-1">
              </label>
              <label class="active black" for="pc-2">
                  <input type="radio" id="pc-2">
              </label>
              <label class="grey" for="pc-3">
                  <input type="radio" id="pc-3">
              </label>
          </div>
      </div>
  </div>
</div>