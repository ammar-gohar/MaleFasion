<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7">
                    <div class="header__top__left">
                        <p>Free shipping, 30-day return or refund guarantee.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5">
                    <div class="header__top__right">
                        <?php if(!isset($user)): ?>
                            <div class="header__top__links">
                                <a href="./signin.php">Sign in</a>
                                <a href="#">FAQs</a>
                            </div>
                        <?php else: ?>
                            <div class="header__top__links">
                                <span class="text-light">Welcome, <a href="#"><?= $user->first_name ?></a></span>
                                <a href="./signin.php" class="ml-4">Log out</a>
                            </div>
                        <?php endif; ?>
                        <div class="header__top__hover">
                            <span>Usd <i class="arrow_carrot-down"></i></span>
                            <ul>
                                <li>USD</li>
                                <li>EUR</li>
                                <li>USD</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="header__logo">
                    <a href="./index.php"><img src="img/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li><a href="./index.php">Home</a></li>
                        <li><a href="./shop.php">Shop</a></li>
                        <li><a href="#">Pages</a>
                            <ul class="dropdown">
                                <li><a href="./about.php">About Us</a></li>
                                <li><a href="./shop-details.php">Shop Details</a></li>
                                <li><a href="./shopping-cart.php">Shopping Cart</a></li>
                                <li><a href="./checkout.php">Check Out</a></li>
                                <li><a href="./blog-details.php">Blog Details</a></li>
                            </ul>
                        </li>
                        <li><a href="./blog.php">Blog</a></li>
                        <li><a href="./contact.php">Contacts</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="header__nav__option">
                    <a href="#" class="search-switch"><img src="img/icon/search.png" alt=""></a>
                    <?php if(isset($user)): ?>
                        <a href="#"><img src="img/icon/heart.png" alt=""></a>
                        <a href="./cart.php"><img src="img/icon/cart.png" alt=""><span><?= count($_SESSION['cart']['products']) ?></span></a>
                        <div class="price">$<?= $_SESSION['cart']['total_price'] ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="canvas__open"><i class="fa fa-bars"></i></div>
    </div>
</header>