<?php
session_start();
if (!isset($_SESSION["adminlogin"]) || $_SESSION["adminlogin"] != "administrative")
    header("location: login.php");
include "../model/Models.php";
$view = false;
$messages = getNewMessageRequest();
// print_r($messages);
$chats = [];
$userid = "";
$status = "";
$error = null;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["sender"])) {
        $view = true;
        $chat = getChatsData($_GET["sender"]);
        if (!$chat)
            $chats = [];
        // print_r($chat);
        $userid = $chat["user_id"];
        $status = $chat["status"];
        $chats = json_decode($chat["chats"], true);
        if ($chat["response"])
            $chats = array_merge($chats, json_decode($chat["response"], true));
    } else {
    }
} else {
    if (isset($_POST["id"], $_POST["message"])) {
        if (!empty($_POST["id"]) && !empty($_POST["message"])) {
            include "../includes/BaseFunction.php";
            $message = htmlentities($_POST["message"]);
            $message = str_replace("\r\n", " ", trim($message));
            $user = $_POST["id"];
            $body = formatMessageBody($message, null, "csu");
            $status = (isset($_POST["status"])) ? $_POST["status"] : null;
            if ($status)
                $response = updateOrInsert(
                    "user_chats",
                    ["user_id" => $user],
                    ["response" => $body, "status" => $status, "response_status" => 1, "date_updated" => date("Y-m-d H:i:s")],
                    "response"
                );
            else
                $response = updateOrInsert(
                    "user_chats",
                    ["user_id" => $user],
                    ["response" => $body, "response_status" => 1, "date_updated" => date("Y-m-d H:i:s")],
                    "response"
                );
            if ($response != "00") {
                $error = "Operation failed";
            } else {
                header("location: $adminurl?sender=$user");
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/main-style.css" />
    <link rel="stylesheet" href="asset\fontawesome-free-5.14.0-web\css\all.min.css" />
    <link rel="stylesheet" href="asset\css\bootstrap.css" />
    <link href="https://cdn.quilljs.com/1.1.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.1.6/quill.js"></script>
    <title>Admin - Chat</title>
</head>

<body>

    <section class="">
        <nav class="abs-sidenav bg-color p-2" id="nav">
            <a href="<?= $adminurl ?>" class="d-block abs-navbrand bg-color sticky-top p-3">
                <p class="text-center mb-2 text-white">
                    <i class="fas fa-user-circle fa-4x"></i>
                </p>
                <p class="mb-0 text-center font-weight-bold text-white"><?= $_SESSION["adminname"] ?></p>
                <p class="mb-0 text-center text-white"><?= $_SESSION["adminpost"] ?></p>
                <hr class="bg-white" />
            </a>

            <div class="abs-navcontent">

                <?php
                foreach ($messages as $key => $value) {
                ?>
                    <a href="<?= $adminurl ?>?sender=<?php echo $value["user_id"]; ?>" class="card chat-box mb-2 d-flex align-items-center">
                        <p class="mb-0 card-body" id="<?= $value["user_id"] ?>">
                            <span class="">
                                <i class="fas fa-user fa-lg"></i>
                            </span>
                            <span class="font-weight-bold ml-2">USER - <?= substr($value["user_id"], 0, 10) ?></span>
                            <?php if ($value["status"] == "closed") { ?>
                                <span class="badge badge-danger">closed</span>
                            <?php } elseif ($value["request_status"] == 1) {
                            ?>
                                <span class="badge badge-success">new</span>
                            <?php
                            }
                            ?>
                        </p>
                    </a>
                <?php } ?>

            </div>
        </nav>
        <main class="main-container">
            <nav class="topnavbar bg-white sticky-top">
                <div class="" id="nav-toggle">
                    <button type="button" class="btn">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                </div>
                <div class="w-100">
                    <?php if ($error) {
                    ?>
                        <div class="alert alert-error"><?= $error ?></div>
                    <?php
                    } ?>
                </div>
                <div class="ml-auto">
                    <a href="logout.php" class="btn btn-outline-danger">
                        Logout <i class="fas f"></i>
                    </a>
                </div>
            </nav>

            <section class="">
                <div class="col-md-9 mx-auto">
                    <?php if ($view) { ?>
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <div class="">
                                    <span class="">
                                        <i class="fas fa-user fa-lg"></i>
                                    </span>
                                    <span class="font-weight-bold ml-2">USER - <?= $userid ?></span>
                                </div>
                                <?php if ($status != "closed") { ?>
                                    <div class="ml-auto">
                                        <form class="" method="post" onsubmit="return confirm('Are you sure you want to close this chat')">
                                            <input type="text" hidden name="id" value="<?= $userid ?>" />
                                            <input type="hidden" value="closed" name="status" />
                                            <input type="hidden" value="We realized you are inactive for the past minutes. Therefore, this chat has been closed" name="message" />
                                            <button type="submit" class="btn text-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="chats" style="max-height: 400px;overflow: auto;">
                                    <?php
                                    $prevdate = null;
                                    foreach ($chats as $key => $value) {
                                        if ($value["date"] != $prevdate) {
                                            $prevdate = $value["date"];
                                    ?>
                                            <section>
                                                <div class="cht-date">
                                                    <?= date("M d", strtotime($prevdate)); ?>
                                                </div>
                                            </section>
                                        <?php
                                        }
                                        if ($value["sender"] == "user") {
                                        ?>
                                            <div class="chat-block-user mb-2 text-white bg-color col-7">
                                                <?= $value["message"] ?>
                                                <p class="mb-0 d-block text-right" style="font-size: 12px"><b><?= $value["time"] ?></b></p>
                                            </div>
                                        <?php } else {
                                        ?>
                                            <div class="chat-block-csu ml-auto border-color mb-2 col-7">
                                                <?= $value["message"] ?>
                                                <p class="mb-0 d-block text-right" style="font-size: 12px"><b><?= $value["time"] ?></b></p>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php if ($status != "closed") {
                                ?>
                                    <form action="" method="post" class="">
                                        <input type="text" hidden name="id" value="<?= $userid ?>" />
                                        <textarea required class="form-control" name="message" placeholder="Reply...."></textarea>
                                        <p class="mb-0 mt-1">
                                            <button class="btn bg-color text-white">
                                                Send
                                            </button>
                                        </p>
                                    </form>
                                <?php
                                } ?>
                            </div>
                        </div>
                    <?php } else {
                    ?>
                        <h3 class="text-center p-3">Welcome onboard</h3>
                    <?php
                    } ?>
                </div>
            </section>
        </main>
    </section>
    <script>
        //Quill editor Rich Text Field
        var quill = new Quill('#editor', {
            theme: 'snow'
        });
        const NAV_TOGGLE = document.querySelector("#nav-toggle");
        const NAV = document.querySelector("#nav");
        NAV_TOGGLE.addEventListener("click", function() {
            if (nav.style.display == "block")
                nav.style.display = "none";
            else
                nav.style.display = "block";
        })
    </script>
</body>

</html>