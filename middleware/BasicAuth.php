<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $ownerusername = "root";
        $ownerpassword = "root@12$!user.password";
        if ($_POST["username"] != $ownerusername && $_POST["password"] != $ownerpassword) {
            echo "Unautorized";
        } else {
            $_SESSION["adminuser"] = $_POST["username"];
            $_SESSION["adminpassword"] = $_POST["password"];
        }
    } else
        echo "Error try again later";
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <form class="" method="POST">
            <label>Username</label><br />
            <input type="text" name="username" />
            <br />
            <label>Password</label>
            <br />
            <input type="password" name="password" />
            <br />
            <input type="submit" value="submit" />
        </form>
    </body>

    </html>
<?php }
?>