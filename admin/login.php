<?php
session_start();
$error = null;
// echo sha1("absam@admin");
include "../config/Connections.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"], $_POST["password"])) {
        $username = $_POST["username"];
        $password = sha1($_POST["password"]);
        $query = "SELECT * FROM admin WHERE email = '$username' AND password='$password'";
        $connection = connectToMysql();
        $result = $connection->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_array();
            $_SESSION["adminusername"] = $row["username"];
            $_SESSION["adminname"] = $row["name"];
            $_SESSION["adminlogin"] = "administrative";
            $_SESSION["adminpost"] = $row["post"];
            header("location: index.php");
        } else
            $error = "Invalid login details";
    } else {
        $error = "Invalid request";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <meta name="description" content="My admin cpanel">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a5ce96b386.js" crossorigin="anonymous"></script>
    <title>Admin....Login</title>
</head>

<body class="">
    <div class="bg-dark position-absolute w-100 p-3 h-100 d-flex justify-content-center align-items-center">
        <div class="card col-md-6 lbl-responsive">
            <div class="card-header">
                <h4 class="">Admin Login</h4>
            </div>
            <div class="card-body">
                <?php if ($error != null) {
                ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php
                } ?>
                <form method="post" action="">
                    <div class="form-group">
                        <input type="text" value="" class="form-control" placeholder="Username" name="username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-success" value="Login" name="login">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>