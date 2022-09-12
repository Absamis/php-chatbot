<?php
include "../../includes/BaseFunction.php";
include "../../model/Models.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senderID = null;

    if (isset($_COOKIE["sender"]))
        $senderID = $_COOKIE["sender"];

    if (!$senderID) {
        echo processResponse("99", "Sender not recognized");
        return;
    }

    $response = getNewResponse($senderID);

    if ($response) {
        echo processResponse("00", "Response fetched", $response);
    } else {
        echo processResponse("99", "No response", []);
    }
}
