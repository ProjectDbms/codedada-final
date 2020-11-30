<!DOCTYPE html>
<html>
<head>
    <title>Codedada - Login</title>
    <?php
        include("includes/header.php");
        include("includes/db_connection.php");
        session_start();
        $errors  = "";
        $value = "";
        $password = "";
        if(isset($_SESSION["username"])) {
            header("location:index.php");
        }
        if(isset($_POST["login"])) {
            $value = htmlspecialchars($_POST["username"]);
            $password = htmlentities($_POST["password"]);
            if(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value)) {
                $value = mysqli_real_escape_string($conn, $value);
                $sql = "SELECT * FROM accounts WHERE email_id='$value'";
            } else {
                $value = mysqli_real_escape_string($conn, $value);
                $sql = "SELECT * FROM accounts WHERE username='$value'";
            }
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if(password_verify($password, $row["password"])) {
                    $errors  = "";
                    $value = "";
                    $password = "";
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["password"] = $row["password"];
                    $_SESSION["email"] = $row["email_id"];
                    header("location:index_profile.php");
                } else {
                    $errors = "Invalid username or email or password";
                }
            } else {
                $errors = "Invalid username or email or password";
            }

            mysqli_free_result($result);
            mysqli_close($conn);
        }
    ?>
    <link rel="stylesheet" href="assets/css/login.css?q=<?php echo time(); ?>" type="text/css">
</head>
<body>
    <div class="container">
        <div class="login-card-wrapper">
            <div class="card login-card">
                <div class="card-body">
                    <h2 class="login-card-title">Login</h2>
                    <p class="login-card-description">Sign in to your account to continue.</p>
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <span class="text-danger"><?php if($errors) echo $errors; ?></span>
                            <label for="username">Email <span style="font-size: 12px;">(or)</span> Username</label>
                            <input type="text" name="username" value="<?php echo($value); ?>" id="username" class="form-control" placeholder="Email or Username" required>
                        </div>
                        <div class="form-group mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="password">Password</label>
                                <a href="#" class="forgot-password-link">Forgot Password?</a>
                            </div>
                            <input type="password" value="<?php echo($password); ?>" name="password" id="password" class="form-control" placeholder="Password" required>
                        </div>
                        <input name="login" id="login" class="btn login-btn" type="submit" value="Login">
                    </form>
                    <p class="login-card-footer-text" style="color: red;">Don't have an account? <a href="index.php?#section3" class="text-reset" style="color:blue !important;">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>