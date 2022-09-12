<?php
error_reporting(1);
include "../../includes/BaseFunction.php";
include "../../model/Models.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senderID = null;
    $messageID = null;
    $mesage = null;

    if (isset($_COOKIE["sender"]))
        $senderID = $_COOKIE["sender"];
    if (isset($_POST["messageid"]))
        $messageID = $_POST["messageid"];
    if (isset($_POST["message"]))
        $message = $_POST["message"];

    if (!$senderID || !$messageID) {
        echo processResponse("99", "Sender not recognized");
        return;
    }
    if (!$message) {
        echo processResponse("33", "Message is required");
        return;
    }

    $message = htmlentities($message);
    $message = str_replace("\r\n", " ", trim($message));

    $body = formatMessageBody($message, $messageID, "user");
    $response = updateOrInsert(
        "user_chats",
        ["user_id" => $senderID],
        ["chats" => $body, "status" => "new", "request_status" => 1, "date_updated" => date("Y-m-d H:i:s")],
        "chats"
    );

    // $response = getNewMessageAlert();
    if ($response == "00") {
        echo processResponse("00", "Message sent", ["messageID" => $messageID]);
    } else {
        echo processResponse("99", "Message failed", ["messageID" => $messageID]);
    }
}
