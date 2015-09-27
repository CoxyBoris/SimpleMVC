<?php
$GLOBAL_ARR_URL = explode(".",$_SERVER['HTTP_HOST']);
$ext = end($GLOBAL_ARR_URL);

//DEFINE YOUR MAIN SOURCES PATH
define('MAIN_PATH',"");

require_once MAIN_PATH.'INCLUDE/global_constant.php';
require_once MAIN_PATH.'INCLUDE/configDB.php';
require_once MAIN_PATH.'INCLUDE/define.db_table.php';
require_once MAIN_PATH.'INCLUDE/global_var.php';
require_once MAIN_PATH.'INCLUDE/define.path.php';
require_once MAIN_PATH.'INCLUDE/include_class.php';