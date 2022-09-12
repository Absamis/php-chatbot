<?php
$sessionchat = true;
include "../model/Models.php";
if (!isset($_COOKIE["sender"])) {
    $sender = "8fe2907c0cbf9990e7f36bd58caf02";
    //$_SESSION["sender"] = $sender;
    setcookie("sender", $sender, time() + (86400 * 30), "/chatbotapp/", "", true,true);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="style.css">
    <title>Home</title>
</head>

<body>

    <div class="cht-container">
        <div class="cht-card" id="chat-card">
            <div class="cht-card-header bg-color">
                <div class="d-flex align-items-center text-white">
                    <div class="cht-profile d-flex align-items-center">
                        <div class="">
                            <!-- <img src="profile.png" width="50" height="50" /> -->
                            <span class="">
                                <i class="material-icons" style="font-size: 35px">account_circle</i>
                            </span>
                        </div>
                        <h6 class="ml-2">Absam Adexdips</h6>
                    </div>
                </div>
            </div>
            <script>
            </script>
            <div class="cht-card-body" id="chat-card-body">
                <section id="msg-block">
                    <?php if ($sessionchat == true) {
                        $chat = getChatsData($_COOKIE["sender"]);
                        if ($chat) {
                            $userid = $chat["user_id"];
                            $chats = json_decode($chat["chats"], true);
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
                                ?>
                                <div class="cht-message-block" id="<?= $value["message_id"] ?>">
                                    <?php if ($value["sender"] == "csu") {
                                    ?>
                                        <span class="text-color">
                                            <i class="material-icons" style="font-size: 30px">account_circle</i>
                                        </span>
                                    <?php
                                    } ?>
                                    <div class="<?php $sd = ($value["sender"] == "csu") ? "response bg-gray-outline" : "message bg-color-light";
                                                echo $sd; ?>">
                                        <p class="mb-0 content text-left"><?= $value["message"] ?></p>
                                        <small class="d-block cht-time mt-0 text-right">
                                            <span class="text-color"><?= $value["time"] ?></span>
                                            <?php if ($value["sender"] == "user") {
                                            ?>
                                                <span class="state material-icons">done_all</span>
                                            <?php
                                            } ?>
                                        </small>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <section>
                                <div class="cht-date">
                                    <?= date("M d") ?>
                                </div>
                            </section>
                            <div class="cht-message-block" id="">
                                <span class="text-color">
                                    <i class="material-icons" style="font-size: 30px">account_circle</i>
                                </span>
                                <div class="response bg-gray-outline">
                                    <p class="mb-0 content text-left">Welcome to AbsamTech chatbot, please how may we help you?</p>
                                    <small class="d-block cht-time mt-0 text-right">
                                        <span class="text-color"><?= date("H:i") ?></span>
                                    </small>
                                </div>
                            </div>
                    <?php
                        }
                    } ?>

                </section>
            </div>
            <div class="cht-card-footer">
                <form class="cht-form d-flex align-items-center" id="message-form">
                    <div class="w-75">
                        <p class="mb-0 d-block cht-form-input" id="message-box" placeholder="Type message" contenteditable="">
                        </p>
                    </div>
                    <div class="ml-auto">
                        <button type="submit" class="cht-btn">
                            <i class="material-icons" style="font-size: 20px">send</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="cht-bottom-nav mt-auto">
            <div class="cht-block p-1 text-right">
                <span class="chat-btn bg-color" id="chat-card-toggle">
                    <i class="material-icons icon mt-2" style="font-size: 30px">chat</i>
                </span>
            </div>
        </div>
    </div>

    <script src="index.js"></script>
</body>

</html>