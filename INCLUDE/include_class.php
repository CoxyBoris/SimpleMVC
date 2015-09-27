<?php
$classesDirs = array (
    MAIN_PATH.'MODEL/CLASS/MISC/',
    MAIN_PATH.'MODEL/CLASS/CORE/',
    MAIN_PATH.'MODEL/CLASS/CONTROLLER/'
);

foreach ($classesDirs as $classesDir)
{
    foreach (glob($classesDir."class.*.php") as $filename)
    {
        require_once $filename;
    }
}

//EXTERNAL LIB
require_once MAIN_PATH.'MODEL/CLASS/MISC/EXTERNAL_LIB/PHPMailer/PHPMailerAutoload.php';