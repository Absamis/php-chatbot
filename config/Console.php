<?php
// echo sha1('absam@admin');
include "../database/Database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["command"])) {
        $commandTemplate = [
            "app migrate database" => "migrateDatabase",
            "app seed adminlogin" => "seedAdminLogin"
        ];
        $command = $_POST["command"];
        if (isset($commandTemplate[$command])) {
            $action = $commandTemplate[$command];
            call_user_func($action);
        } else
            echo '<p style="color:red;">Command is not recognized</p>';
    } else {
        echo '<p style="color:red;">Error: Try again later</p>';
    }
}
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
    <form class="" action="" method="post">
        <label>Console Interface</label>
        <br />
        <input type="text" name="command" width="200" placeholder="Enter command here" />
        <br />
        <input type="submit" value="submit" />
    </form>
</body>

</html>