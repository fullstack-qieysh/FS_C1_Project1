<?php
session_start();
$error = "";

if(isset($_POST['login'])){
    //get data username and password from input
    $username = $_POST['username'];
    $password = $_POST['password'];

    //check session
    if($username == "Nur Cahaya" && $password == "12345"){
        $_SESSION['username'] = $username;
        header("Location: membership.php");
    } else {
        $error = "Invalid username or password!";
    }
}

?>
<!DOCTYPE html>
<html>
    <!-- option for styling -->
<script src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js'></script>
<script src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js'></script>
 <link rel="stylesheet" href="style.css">
<body>
<div class="wrapper">
    <div id="login-form" class="login-box">
        <form method="post">
        <h2>Login</h2>
        <div class="input-box">
            <span class="icon">
                <ion-icon name="person"></ion-icon>
            </span>
            <input type="text" name="username" required>
            <label>Username</label>
        </div>
        <div class="input-box">
            <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
            </span>
            <input type="password" name="password" required>
            <label>Password</label>
        </div>
        <p style="color:red; text-align:center"> <?php echo $error; ?> </p>
        <button type="submit" name="login">Login</button>
        </form>
    </div>
</div>
</body>
</html>