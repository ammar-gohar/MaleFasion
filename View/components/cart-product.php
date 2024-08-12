<tr>
    <td class="product__cart__item">
        <div class="product__cart__item__pic">
            <img src="img/<?= $product['default_image'] ?>" alt="Product image">
        </div>
        <div class="product__cart__item__text">
            <h6><?= $product['name'] ?></h6>
            <?php if($product['sale_price']): ?>
                <del><h5>$<?= $product['price'] ?></h5></del>
                <h5>$<?= $product['sale_price'] ?></h5>
            <?php else:?>
                <h5>$<?= $product['price'] ?></h5>
            <?php endif ?>
            <div>
                <p><span style="color: <?= $product['color']?> ;"><?= $product['color'] ?></span> - <?= $product['size'] ?></p>
            </div>
        </div>
    </td>
    <td class="quantity__item">
        <div class="quantity">
            <p><?= $product['quantity'] ?></p>
        </div>
    </td>
    <td class="cart__price">$<?= $product['total_price'] ?></td>
    <td class="cart__close">
        <form action="../routes/cart.php" method="post">
            <input type="hidden" name="product_cart_id" value="<?= $product['product_cart_id'] ?>">
            <input type="hidden" name="size-color" value="<?= $product['product_id'] ?>-<?= $product['variant_id']?>-<?= $product['size']?>-<?= $product['color'] ?>">
            <input type="hidden" name="delete" value="true">
            <button type="submit" style="border:none; border-radius: 999px; aspect-ratio:1;"><i class="fa fa-close"></i></button>
        </form>
    </td>
</tr>