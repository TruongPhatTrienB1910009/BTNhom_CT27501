<?php
    include 'config.php';
    session_start();

    if (isset($_GET['logout'])) {
        unset($user_id);
        session_destroy();
        header('location:index.php');
    }

    if(isset($_SESSION['user_id']))
        $user_id = $_SESSION['user_id'];

    if (isset($_POST['add_to_cart']) && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];

        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name ='$product_name' AND user_id = '$user_id'") or die('query failed');

        if (mysqli_num_rows($select_cart) > 0) {
            $fetch_cart = mysqli_fetch_assoc($select_cart);
            $totalQuantity = $fetch_cart['quantity'] + $product_quantity;
            mysqli_query($conn, "UPDATE `cart` SET quantity = '$totalQuantity' WHERE user_id = '$user_id' AND name ='$product_name'") or die('query failed');
            $message[] = 'product already added to cart (Click vào đây để ẩn thông báo!)';
        } else {
            mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, image, quantity) VALUES ('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
            $message[] = 'product added to cart! (Click vào đây để ẩn thông báo!)';
        }
    }else if(isset($_POST['add_to_cart']) && !isset($_SESSION['user_id'])){
        header('location:login.php');
    };

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="./css/navbar.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="/css/base.css">
    <script defer src="./js/jquery.validate.js"></script>
    <script defer src="./js/jquery.js"></script>
    <title>TRANG CHỦ</title>
</head>

<body>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>
    <?php include('navbar.php') ?>

    <div class="hero-image">
        <div class="hero-text">
            <h1 class="mt-2" style="font-size:50px">CHÀO MỪNG BẠN ĐẾN VỚI</h1>
            <img style="width: 400px;" src="./images/img-background.png" alt="">
            <h2 style="font-size:35px;" class="mt-1">Luôn tạo ra những sản phẩm tốt nhất và an toàn nhất đến với khách hàng</h2>
        </div>
    </div>

    <div class="container main-index">
        <div class="product-container">
            <hr>
            <h2 style="font-family: Comic Sans;">CÁC SẢN PHẨM ĐANG BÁN</h2>
            <div class="row">
                <?php
                    $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                    if (mysqli_num_rows($select_product) > 0) {
                        while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                ?>
                    <div class="col-sm-3 col-12 dashboard-panel-6">
                        <form class="frm" action="" method="POST">
                            <div class="overlay">
                                <img style="height: 350px;" class="img-overlay" src="<?php echo $fetch_product['image']; ?>" alt="Card image cap">
                                <span id="price">
                                    <?php 
                                        echo $fetch_product['price'];
                                    ?>
                                    đ
                                </span>
                                <div class="middle w-75">
                                    <p class="text-white mb-2">
                                        <?php
                                            echo $fetch_product['name'];
                                        ?>
                                    </p>

                                    <div class="input-group d-flex justify-content-center">
                                        <input class="w-50" type="number" min="1" name="product_quantity" value="1">
                                        <div class="input-group-append w-50">
                                            <input type="submit" value="Mua" name="add_to_cart" class="btn btn-info w-100">
                                        </div>
                                    </div>
                                    <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                <?php
                    };
                };
                ?>
            </div>
            <hr>
            <h2 style="font-family: Comic Sans;" class="m-3">NHỮNG ĐIỀU CẦN BIẾT VỀ CAKESHOP</h2>
            <div class="row">
                <div class="col">
                    <h3 style="font-family: Comic Sans;">
                        Những cam kết của cakeshop
                    </h3>
                    <ol>
                        <li><p>Bánh được làm 100% không hóa chất</p></li>
                        <li><p>Sử dụng máy hút chân không nhằm tăng thời gian sử dụng bánh</p></li>
                        <li><p>Bánh được làm theo từng mẻ đặt hàng, tuyệt đối không có bánh cũ, bánh quá hạn</p></li>
                        <li><p>Hoàn tiền 100% nếu phát hiện bánh lỗi khi mua về</p></li>
                    </ol>
                </div>
                <div class="col">
                    <h3 style="font-family: Comic Sans;">
                        Chính sách dành cho khách hàng mới
                    </h3>
                    <ol>
                        <li><p>Được tặng voucher giảm giá 20%</p></li>
                        <li><p>Miễn phí giao hàng trong khu vực nội thành</p></li>
                        <li><p>Đặt hàng từ 1.000.000 vnd được tặng thêm combo cupcakes đặc biệt</p></li>
                        <li><p>Hoàn tiền 100% nếu phát hiện bánh lỗi khi mua về</p></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php') ?>
</body>

</html>