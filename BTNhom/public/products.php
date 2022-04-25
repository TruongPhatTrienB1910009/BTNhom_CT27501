<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id))
    header('location:login.php');


if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:index.php');
}


if (isset($_POST['update_cart'])) {
    $update_quantity = $_POST['cart_quantity'];
    $update_id = $_POST['cart_id'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
    $message[] = 'Sản phẩm đã được cập nhật lại! (Click vào đây để ẩn thông báo!)';
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
}


if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
}


// thong tin giao hang

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name ='$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($select_cart) > 0) {
        $fetch_cart = mysqli_fetch_assoc($select_cart);
        $totalQuantity = $fetch_cart['quantity'] + $product_quantity;
        mysqli_query($conn, "UPDATE `cart` SET quantity = '$totalQuantity' WHERE user_id = '$user_id' AND name ='$product_name'") or die('query failed');
        $message[] = 'Sản phẩm đã được cập nhật vào giỏ hàng (Click vào đây để ẩn thông báo!)';
    } else {
        mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, image, quantity) VALUES ('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
        $message[] = 'Đã thêm sản phẩm vào giỏ hàng! (Click vào đây để ẩn thông báo!)';
    }
};

if (isset($_POST['submit'])) {
    $save_id = $_POST['save_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND name = '$name' AND phone = '$phone' AND address = '$address'") or die('query failed');
    if (mysqli_num_rows($select) > 0) {
    } else {
        mysqli_query($conn, "UPDATE `user_form` SET email = '$email', name = '$name', phone = '$phone', address = '$address' WHERE id = '$save_id'") or die('query failed');
        $message[] = 'Cập Nhật thông tin giao hàng thành công! (Click vào đây để ẩn thông báo!)';
    }
}

if(isset($_GET['order'])){
    mysqli_query($conn, "INSERT INTO `orders` (user_id, name, price, image, quantity) SELECT user_id, name, price, image, quantity FROM `cart`");
    mysqli_query($conn, "UPDATE `orders` SET date = now() where user_id = '$user_id'");
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
}

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
    <title>SẢN PHẨM</title>
</head>

<body id="background">
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>
    <?php include('navbar.php') ?>

    <div class="container main-index">
        <div>
            <h1 style="margin-top: 90px;">GIỎ HÀNG</h1>

            <table class="table table-hover table-light mt-3">
                <thead>
                    <th class="col-2">Ảnh</th>
                    <th class="col-2">Tên sản phẩm</th>
                    <th class="col-2">Giá</th>
                    <th class="col-2">Số lượng</th>
                    <th class="col-2">Tổng tiền sản phẩm</th>
                    <th class="col-2">Thao tác</th>
                </thead>

                <tbody>

                    <?php
                        $grand_total = 0;
                        $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                        if (mysqli_num_rows($cart_query) > 0) {
                            while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {

                    ?>
                            <tr>
                                <td><img src="<?php echo $fetch_cart['image']; ?>" height="100" width="100" alt=""></td>
                                <td><?php echo $fetch_cart['name']; ?></td>
                                <td><?php echo $fetch_cart['price']; ?> đ</td>
                                <td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">

                                        <div class="d-flex">
                                            <input class="w-50" type="number" min="1" id="cart_quantity" name="cart_quantity" value="<?php echo $fetch_cart['quantity'] ?>">
                                            <div class="input-group-append w-75">
                                                <input type="submit" name="update_cart" value="Cập nhật" class="btn btn-danger w-100">
                                            </div>
                                        </div>
                                        <td><?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']) ?> đ</td>
                                        <td><a href="products.php?remove=<?php echo $fetch_cart['id']; ?>" class="btn btn-warning" onclick="return confirm('remove item from cart?')">Xóa sản phẩm</a></td>
                                    </form>
                                </td>
                            </tr>
                    <?php
                            $grand_total += $sub_total;
                            };
                        };
                    ?>
                    <tr>
                        <td colspan="4">
                            <h4>Thành tiền: </h4>
                        </td>
                        <td><?php echo $grand_total; ?> đ</td>
                        <td><a href="products.php?delete_all" onclick="return confirm('delete all from cart');" class="btn btn-danger <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Xóa tất cả</a></td>

                    </tr>
                </tbody>
            </table>

            <div>

                <?php
                    if ($grand_total < 1) {
                        echo '
                            <a href="#" class="btn btn-info disabled">Cập nhật địa chỉ giao hàng</a>

                            <a href="orders.php">
                                <button class="btn btn-info">
                                    Đơn hàng
                                </button>
                            </a>

                            <button class="btn btn-info disabled">
                                Đặt hàng
                            </button>
                        
                        ';
                    } else {
                        echo '
                                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#exampleModalCenter">
                                    Cập nhật địa chỉ giao hàng
                                </button>

                                <a href="orders.php">
                                    <button class="btn btn-info">
                                        Đơn hàng
                                    </button>
                                </a>

                                <form style="display: inline-block;" action="#" method="post">
                                    <a href="products.php?order" onclick="return confirm(\'Xác nhận đặt hàng\');" class="btn btn-info">
                                        Đặt hàng
                                    </a>
                                </form>

                            ';
                    }
                ?>


                <?php
                    $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
                    if (mysqli_num_rows($select_user) > 0) {
                        $fetch_user = mysqli_fetch_assoc($select_user);
                    };
                ?>

                <!-- Modal -->
                <form action="" method="POST">
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title" id="exampleModalLongTitle">Thông tin người nhận hàng</h2>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="save_id" value="<?php echo $fetch_user['id']; ?>">

                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Họ tên ngườinhận:</td>
                                                <td>
                                                    <input id="name" value="<?php echo $fetch_user['name']; ?>" type="text" name="name" placeholder="Nhập vào username">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Địa chỉ nhận: </td>
                                                <td>
                                                    <input id="address" value="<?php echo $fetch_user['address']; ?>" type="text" name="address" placeholder="Nhập vào địa chỉ của bạn">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>SĐT người nhận: </td>
                                                <td>
                                                    <input id="phone" value="<?php echo $fetch_user['phone']; ?>" type="text" name="phone" placeholder="Nhập vào số điện thoại của bạn">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                    <input value="Cập Nhật" type="submit" name="submit" class="btn btn-primary"></input>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <h1 class="text-center" style="color: brown; ">CÁC SẢN PHẨM CỦA CAKESHOP</h1>
            <div class="row p-2">
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
                                        <div class="text-white mb-2">
                                            <?php
                                                echo $fetch_product['name'];
                                            ?>
                                        </div>
                                        <div class="d-flex justify-content-center">
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

        </div>

    </div>

    <?php include('footer.php') ?>
</body>

</html>