<?php
require_once './VIEW/INCLUDES/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./VIEW');
$twig = new Twig_Environment($loader, array(
    'debug' => true,
    'charset' => 'ISO-8859-1'
));
//$twig->addExtension(new Twig_Extensions_Extension_Text());
$twig->addExtension(new Twig_Extension_Debug());

function nice_number($n) {
        // first strip any formatting;
    $n = (0+str_replace(",", "", $n));

    // is this a number?
    if (!is_numeric($n)) return false;

    // now filter it;
    if ($n > 1000000000000) return round(($n/1000000000000), 2).' trillion';
    elseif ($n > 1000000000) return round(($n/1000000000), 2).' billion';
    elseif ($n > 1000000) return round(($n/1000000), 1).' M';
    elseif ($n > 1000) return round(($n/1000), 1).' K';

    return number_format($n);
    }
    
$filter = new Twig_SimpleFilter('nice_big_number', 'nice_number');
$twig->addFilter($filter);