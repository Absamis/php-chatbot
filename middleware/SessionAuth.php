<?php

function isRootUser()
{
    if (!isset($_SESSION["adminuser"]) && !isset($_SESSION["adminpassword"]))
        return false;
    return true;
}
