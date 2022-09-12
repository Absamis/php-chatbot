<?php
include "../config/Connections.php";
include "Migrations.php";
function migrateDatabase()
{
    $connection = connectToMysql();
    if (!$connection) die("Connection error");
    if ($connection->query(create_admin_table))
        echo "Admin table created successfully<br/>";
    else
        echo "Error creating admin table " . $connection->error;
    if ($connection->query(create_user_chats_table))
        echo "User chats table created successfully<br/>";
    else
        echo "Error creating user chats table " . $connection->error;
    $connection->close();
}

function seedAdminLogin()
{
    $connection = connectToMysql();
    if (!$connection) die("Connection error");
    if ($connection->query(seed_admin_login))
        echo "Admin login seeded to the database successfully<br/>";
    else
        echo "Error seedinging admin login " . $connection->error;
}
