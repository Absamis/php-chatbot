<?php
include substr(__DIR__, 0, strpos(__DIR__, "model")) . "/config/Connections.php";
$adminurl = "http://localhost/chatbotapp/admin";
function insertRecord($table, $data = []): string
{
    $dbVal = null;
    if (!$data)
        return "33";
    $connection = connectToMysql();
    if (!$connection) return "99";

    $columns = array_keys($data);
    $columnValue = implode(",", $columns);
    $values = array_values($data);
    foreach ($values as $key => $value) {
        $dbVal .= "'" . $value . "',";
    }
    $dbVal = rtrim($dbVal, ",");
    // echo $Vals;
    $query = "INSERT INTO $table($columnValue) VALUES($dbVal)";
    if ($connection->query($query))
        return "00";
    return "99";
}

function updateChatRecord($data = [], $column)
{
    $dbVal = null;
    $connection = connectToMysql();
    if (!$connection) return "99";

    $userid = $data["user_id"];
    $query = "SELECT $column FROM user_chats WHERE user_id='$userid' ";
    $result  = $connection->query($query);
    if (!$result)
        return "99";

    //PPEND NEW CHAT TO THE PREVIOUS CHAT
    $chat = $result->fetch_array();
    if (!$chat[$column])
        $chat =  [$column => '[]'];
    $chats = json_decode($chat[$column], true);

    $values = json_decode($data[$column], true);
    $chats = array_merge($chats, $values);
    //END APPEND
    $data[$column] = json_encode($chats);

    $columns = array_keys($data);
    $values = array_values($data);
    foreach ($values as $key => $value) {
        $value = preg_replace('/[\'\"]+/', '\\\$0', $value);
        if ($value == null)
            $dbVal .= "$columns[$key]=null,";
        elseif ($value == 0)
            $dbVal .= "$columns[$key]='0',";
        else
            $dbVal .= "$columns[$key]='" . $value . "',";
    }
    $dbVal = rtrim($dbVal, ",");

    $query = "UPDATE user_chats SET $dbVal WHERE user_id='$userid'";
    // echo $query;
    if ($connection->query($query))
        return "00";
    return "99";
}

function isRecordExist($table, $data = [])
{
    $values = null;
    if (!$data)
        return false;
    $connection = connectToMysql();
    if (!$connection) return false;

    $columns = array_keys($data);
    foreach ($data as $key => $value) {
        $values .= "$key='" . $value . "',";
    }
    $values = rtrim($values, ",");
    $query = "SELECT * FROM $table WHERE $values";
    // echo $query;
    $result = $connection->query($query);
    if (!$result)
        return false;
    if ($result->num_rows > 0)
        return true;
    return false;
}
function updateOrInsert($table, $oldData = [], $newData = [], $column)
{
    if (!$oldData || !$newData)
        return "33";
    if (!isRecordExist($table, $oldData)) {
        return insertRecord($table, $oldData + $newData);
    } else {
        return updateChatRecord($oldData + $newData, $column);
    }
    return "00";
}

function getNewMessageAlert()
{
    $connection = connectToMysql();
    if (!$connection) return "99";
    $query = "SELECT user_id FROM user_chats WHERE request_status = '1'";
    $result = $connection->query($query);
    if ($result)
        return $result->fetch_assoc();
    return null;
}

function getNewResponse($userid)
{
    $connection = connectToMysql();
    if (!$connection) return "99";
    $query = "SELECT response,status FROM user_chats WHERE response_status = '1' AND user_id='$userid'";
    $result = $connection->query($query);
    if ($result) {
        $response = $result->fetch_array(MYSQLI_BOTH);
        if ($response) {
            // echo $response[0];
            $body = [
                "user_id" => $userid,
                "chats" => "$response[0]",
                "response_status" => 0,
                "response" => null
            ];
            if (updateChatRecord($body, "chats") == "99")
                return null;
        } else {
            return null;
        }
        return $response;
    }
    return null;
}

function getNewMessageRequest()
{
    $connection = connectToMysql();
    if (!$connection) return "99";
    $query = "SELECT user_id,request_status,response_status,status FROM user_chats ORDER BY date_updated DESC";
    $result = $connection->query($query);
    if (!$result)
        return [];
    //print_r($result->fetch_all(MYSQLI_ASSOC));
    return $result->fetch_all(MYSQLI_BOTH);
}

function getChatsData($userid)
{
    $connection = connectToMysql();
    if (!$connection) return [];
    $query = "SELECT user_id,chats,response,status FROM user_chats WHERE user_id='$userid'";
    $result = $connection->query($query);
    if (!$result)
        return [];
    $query = "UPDATE user_chats SET request_status='0' WHERE user_id='$userid'";
    $connection->query($query);
    return $result->fetch_array();
}
