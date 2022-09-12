<?php

define(
    'create_admin_table',
    "
    CREATE TABLE IF NOT EXISTS admin(
        id int not null primary key auto_increment,
        name varchar(50) not null,
        post varchar(50) not null,
        email varchar(150) not null unique,
        password text not null
    ) ENGINE=INNODB"
);

define(
    'create_user_chats_table',
    "
    CREATE TABLE IF NOT EXISTS user_chats(
        user_id varchar(50) primary key,
        chats json,
        request_status int default 0,
        response json,
        response_status int default 0,
        status varchar(7) not null default 'new',
        date_updated timestamp default CURRENT_TIMESTAMP
    ) ENGINE=INNODB"
);

define("seed_admin_login", "
    INSERT INTO admin(name,post,email,password)VALUES('Absam Alva', 'Customer Service', 'smallkid157@gmail.com', 'f0b7acffce1259635a1c24588a8ee64890f731c8')
");
