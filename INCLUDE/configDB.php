<?php 
if ($_SERVER['HTTP_HOST'] == "www.yourdomain.com") 
{
    define('SQL_DSN', 'mysql:dbname=SimpleMVC;host=localhost');
    define('SQL_USERNAME', 'username');
    define('SQL_PASSWORD', 'password');
}
