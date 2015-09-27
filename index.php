<?php
session_start();

    /**
    * File : index.php
    *
    * PHP version 5
    */

   ini_set('display_errors',1);
   ini_set('memory_limit', '2048M');

   /**
    * INCLUDES
    */

   require_once './INCLUDE/include.php';
   require_once './VIEW/INCLUDES/twig/config.php';

   /**
    * CONSTANTS
    */


   /**
    * Description : Main index
    *
    * @author     Boris Troja
    * @copyright  
    * @version    0.1
    * @since      
    * @deprecated 
    */

   date_default_timezone_set('Europe/Paris');
   setlocale (LC_TIME, 'fr_FR.utf8','fra'); 

   $timeZone = new DateTimeZone('Europe/Paris');

   $mysql = new Connection();
   $conn = $mysql->getConnexion();
   $Ccontroller_main = new CController_main();

   if($Ccontroller_main->check_allowed_action())
   {
       $ajaxprint = filter_input(INPUT_POST, 'ajaxprint');
       $return = $Ccontroller_main->execute_action($ajaxprint);
       $Ccontroller_main->print_view($return, $ajaxprint);
   }
   else
   {
       $Ccontroller_main->print_view(true);
   }

   $mysql = null;
   $conn = null;