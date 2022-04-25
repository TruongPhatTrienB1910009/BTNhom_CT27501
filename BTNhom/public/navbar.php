<nav id="nav" class="navbar navbar-default navbar-static-top">
    <div class="container">
        <img src="../images/img-background.png" alt="">
        <div>
            <a class="m-0 navbar-brand mr-4" href="index.php">
                <i class="fa-solid fa-house"></i>
                Trang Chủ
            </a>

            <?php
                    if (isset($user_id)) {
                        echo "
                            <a class=\"m-0 navbar-brand mr-4\" href=\"profile.php\">
                                <i class=\"fa-solid fa-user\"></i>
                                Hồ Sơ
                            </a>
                        ";
                    } else {
                        echo "
                            <a class=\"m-0 navbar-brand mr-4\" href=\"login.php\" onclick=\"return confirm('Bạn chưa đăng nhập nên chúng tôi sẽ chuyển bạn đên trang đăng nhập!');\">
                                <i class=\"fa-solid fa-user\"></i>
                                Hồ Sơ
                            </a>
                        ";
                    }
            ?>


            <?php
                    if (isset($user_id)) {
                        echo "
                            <a class=\"m-0 navbar-brand mr-3\" href=\"products.php\">
                                <i class=\"fa-solid fa-bag-shopping\"></i>
                                Sản phẩm
                            </a>
                        ";
                    } else {
                        echo "
                            <a class=\"m-0 navbar-brand mr-3\" href=\"login.php\" onclick=\"return confirm('Bạn chưa đăng nhập nên chúng tôi sẽ chuyển bạn đên trang đăng nhập!');\">
                                <i class=\"fa-solid fa-bag-shopping\"></i>
                                Sản phẩm
                            </a>
                        ";
                    }
            ?>

            <a class="m-0 navbar-brand mr-3" href="register.php">
                <i class="fa-regular fa-registered"></i>
                Đăng Ký
            </a>

            <?php
                if (isset($user_id)) {
                    echo "
                            <a class=\"m-0 navbar-brand mr-3\" href=\"#\" onclick=\"return confirm('Bạn đã đăng nhập!');\">
                                <i class=\"fa-solid fa-arrow-right-to-bracket\"></i>
                                Đăng Nhập
                            </a>
                        ";
                } else {
                    echo "
                            <a class=\"m-0 navbar-brand mr-3\" href=\"login.php\">
                                <i class=\"fa-solid fa-arrow-right-to-bracket\"></i>
                                Đăng Nhập
                            </a>
                        ";
                }
            ?>

            <?php
                if (isset($user_id)) {
                    echo "
                            <a class=\"m-0 navbar-brand mr-2\" href=\"index.php?logout=<?php echo $user_id; ?>\" onclick=\"return confirm('Ban co chac la muon thoat?');\">Đăng xuất
                                <i class=\"fa-solid fa-right-from-bracket\"></i>
                            </a>
                        ";
                } else {
                    echo "
                            <a class=\"m-0 navbar-brand mr-2\" href=\"#\" onclick=\"return confirm('Bạn chưa đăng nhập!');\">Đăng xuất
                                <i class=\"fa-solid fa-right-from-bracket\"></i>
                            </a>
                        ";
                }
            ?>
        </div>
    </div>
</nav>