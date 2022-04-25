<?php
    include 'config.php';
    session_start();

    if(isset($_POST['submit'])){
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

        $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

        if(mysqli_num_rows($select) > 0){
            $row = mysqli_fetch_assoc($select);
            $_SESSION['user_id'] = $row['id'];
            header(('location:index.php'));
        }else{
            $message[] = 'email hoặc mật khẩu không đúng! (Click vào đây để ẩn thông báo!)';
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
    <script defer src="./js/jquery.validate.js"></script>
    <script defer src="./js/jquery.js"></script>
    <title>ĐĂNG NHẬP</title>
</head>

<body id="background">
    <?php
        if(isset($message)){
            foreach($message as $message){
                echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
            }
        }
    ?>
    <?php include('navbar.php') ?>

    <div class="vh-100">
        <div class="form-container">
            <form id="signupForm" action="" method="post">
                <h3>Đăng Nhập Ngay</h3>
                <input type="email" id="email" name="email" placeholder="Nhập vào email của bạn" class="box">
                <input type="password" id="password" name="password" placeholder="Nhập vào password của bạn" class="box">
                <input type="submit" value="Đăng Nhập" name="submit" class="form-btn">
                <p>Bạn chưa có mật khẩu? <a href="register.php">Đăng Ký Ngay</a></p>
            </form>
        </div>
    </div>

    <?php include('footer.php') ?>
</body>

</html>