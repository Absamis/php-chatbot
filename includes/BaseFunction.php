<?php

function processResponse($code, $description, $data = [])
{
    $response = [
        "responseCode" => $code,
        "responseDescription" => $description,
        "data" => $data
    ];
    return json_encode($response);
}

function formatMessageBody($msg, $msgid, $sender)
{
    $body = [
        "message" => $msg,
        "date" => date("d-m-Y"),
        "time" => date("H:i"),
        "message_id" => $msgid,
        "sender" => $sender
    ];
    return json_encode([$body]);
}
