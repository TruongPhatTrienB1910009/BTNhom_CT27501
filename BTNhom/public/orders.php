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

    if (isset($_GET['remove'])) {
        $remove_id = $_GET['remove'];
        mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$remove_id'") or die('query failed');
    }

    if (isset($_GET['delete_all'])) {
        mysqli_query($conn, "DELETE FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
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
    <title>ĐƠN HÀNG</title>
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
            <h1 style="margin-top: 90px;">ĐƠN HÀNG</h1>

            <table class="table table-hover table-light mt-3">
                <thead>
                    <th class="col-2">Ảnh</th>
                    <th class="col-2">Tên sản phẩm</th>
                    <th class="col-1">Giá</th>
                    <th class="col-1">Số lượng</th>
                    <th class="col-2">Tổng tiền sản phẩm</th>
                    <th class="col-2">Ngày đặt</th>
                    <th class="col-2">Hành động</th>
                </thead>

                <tbody>

                    <?php
                        $grand_total = 0;
                        $orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
                        if (mysqli_num_rows($orders) > 0) {
                            while ($fetch_orders = mysqli_fetch_assoc($orders)) {

                    ?>
                            <tr>
                                <td><img src="<?php echo $fetch_orders['image']; ?>" height="100" width="100" alt=""></td>
                                <td><?php echo $fetch_orders['name']; ?></td>
                                <td><?php echo $fetch_orders['price']; ?> đ</td>
                                <td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="orders_id" value="<?php echo $fetch_orders['id']; ?>">
                                        <span><?php echo $fetch_orders['quantity'] ?></span>
                                        <td><?php echo $sub_total = ($fetch_orders['price'] * $fetch_orders['quantity']) ?> đ</td>
                                    </form>
                                </td>
                                <td>
                                    <?php echo $fetch_orders['date']; ?>
                                </td>
                                <td><a href="orders.php?remove=<?php echo $fetch_orders['id']; ?>" class="btn btn-warning" onclick="return confirm('Bạn muốn hủy đơn hàng này?')">Xóa đơn hàng</a></td>
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
                        <td colspan="2">bạn sẽ nhận được hàng trong 24h</td>
                    </tr>
                </tbody>
            </table>

            <div>

                <?php
                    if ($grand_total < 1) {
                        echo '<a href="#" class="btn btn-info disabled">Hủy đơn hàng</a>';
                    } else {
                        echo '
                                <a href="orders.php?delete_all" onclick="return confirm(\'Hủy toàn bộ đơn hàng?\');" class="btn btn-danger">Hủy toàn bộ đơn hàng</a>
                            ';
                    }
                ?>


            </div>
            

        </div>

    </div>

    <?php include('footer.php') ?>
</body>

</html>