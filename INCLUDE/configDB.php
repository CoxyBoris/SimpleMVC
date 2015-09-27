<?php 
if ($_SERVER['HTTP_HOST'] == "www.yourdomain.com") 
{
    define('SQL_DSN', 'mysql:dbname=yourdatabasename;host=localhost');
    define('SQL_USERNAME', 'username');
    define('SQL_PASSWORD', 'password');
}

if ($_SERVER['HTTP_HOST'] == "dev.simplemvc.dev") 
{
    define('SQL_DSN', 'mysql:dbname=SimpleMVC;host=localhost');
    define('SQL_USERNAME', 'root');
    define('SQL_PASSWORD', 'root');
}