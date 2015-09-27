<?php
$GLOBAL_ARR_ACTION = array();
$GLOBAL_ARR_FORM = array();
$GLOBAL_ARR_INPUT_ERROR = array();
$GLOBAL_ARR_VALUES = array();

$GLOBAL_MONTHS = array(
    1=>array('fr'=>'Janvier','en'=>'January'),
    2=>array('fr'=>'F&eacute;vrier','en'=>'February'),
    3=>array('fr'=>'Mars','en'=>'March'),
    4=>array('fr'=>'Avril','en'=>'April'),
    5=>array('fr'=>'Mai','en'=>'May'),
    6=>array('fr'=>'Juin','en'=>'June'),
    7=>array('fr'=>'Juillet','en'=>'July'),
    8=>array('fr'=>'Aout','en'=>'August'),
    9=>array('fr'=>'Septembre','en'=>'September'),
    10=>array('fr'=>'Octobre','en'=>'October'),
    11=>array('fr'=>'Novembre','en'=>'November'),
    12=>array('fr'=>'D&eacute;cembre','en'=>'December')
);

$GLOBAL_DAYS = array(
    1=>array('fr'=>'Lundi','en'=>'Monday'),
    2=>array('fr'=>'Mardi','en'=>'Tuesday'),
    3=>array('fr'=>'Mercredi','en'=>'Wednesday'),
    4=>array('fr'=>'Jeudi','en'=>'Thursday'),
    5=>array('fr'=>'Vendredi','en'=>'Friday'),
    6=>array('fr'=>'Samedi','en'=>'Saturday'),
    7=>array('fr'=>'Dimanche','en'=>'Sunday')
);


date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR');
$timeZone = new DateTimeZone('Europe/Paris');
   
$GLOBAL_DATE = date('Y-m-d');
$GLOBAL_DATE_HOUR = date('Y-m-d H:i:s');