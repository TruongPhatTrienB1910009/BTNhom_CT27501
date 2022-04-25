<?php
    include 'config.php';
    session_start();

    $user_id = $_SESSION['user_id'];
    if (!isset($user_id)){
        header('location:login.php');
    }

    if (isset($_GET['logout'])) {
        unset($user_id);
        session_destroy();
        header('location:index.php');
    }

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
            $message[] = 'Cập Nhật thành công! (Click vào đây để ẩn thông báo!)';
        }
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
    <title>HỒ SƠ</title>
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

    <div class="form-container">
        <form action="" method="post">
            <h2 class="text-uppercase">Thông tin người dùng</h2>
            <?php
                $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($select_user) > 0) {
                    $fetch_user = mysqli_fetch_assoc($select_user);
                };
            ?>

            <table class="table">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <h4>
                                Họ tên:
                            </h4>
                        </td>
                        <td>
                            <h4>
                                <?php echo $fetch_user['name']; ?>
                            </h4>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>
                                Địa chỉ: 
                            </h4>
                        </td>
                        <td>
                            <h4>
                                <?php echo $fetch_user['address']; ?>
                            </h4>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>
                                Điện thoại: 
                            </h4>
                        </td>
                        <td>
                            <h4>
                                <?php echo $fetch_user['phone']; ?>
                            </h4>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                Chỉnh Sửa
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>


            <!-- Modal -->
            <form action="" method="POST">
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title" id="exampleModalLongTitle">Chỉnh sửa thông tin</h2>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="save_id" value="<?php echo $fetch_user['id']; ?>">

                                <div class="form-group row">
                                    <h4 class="col-sm-3 col-form-label mt-3">Tên:</h4>
                                    <div class="col-sm-9">
                                        <input id="name" value="<?php echo $fetch_user['name']; ?>" type="text" id="username" name="name" placeholder="Nhập vào username" class="box">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <h4 class="col-sm-3 col-form-label mt-3">Địa chỉ:</h4>
                                    <div class="col-sm-9">
                                        <input id="address" value="<?php echo $fetch_user['address']; ?>" type="text" name="address" placeholder="Nhập vào địa chỉ của bạn" class="box">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <h4 class="col-sm-3 col-form-label mt-3">SĐT:</h4>
                                    <div class="col-sm-9">
                                        <input id="phone" value="<?php echo $fetch_user['phone']; ?>" type="text" name="phone" placeholder="Nhập vào số điện thoại của bạn" class="box">
                                    </div>
                                </div>

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
    </form>
    </div>

    <?php include('footer.php') ?>
</body>

</html>