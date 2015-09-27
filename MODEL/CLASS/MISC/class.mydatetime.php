<?php
/**
 * File : class.mydatetime.php
 *
 * PHP version 5
 */

/**
 * INCLUDES
 */

/**
 * CONSTANTS
 */

if (!defined("_CLASS_CMYDATETIME_LOADED"))
{
    define("_CLASS_CMYDATETIME_LOADED", true);

    /**
     * Cmydatetime
     *
     * Extends of DateTime
     *
     * @author     Boris Troja
     * @copyright  2015 LEOMINOR
     * @version    0.1
     */
    class Cmydatetime extends DateTime
    {
        public static function createFromFormat($format, $time, $timezone = null)
        {
            if(!$timezone)
            {
                $timezone = new DateTimeZone(date_default_timezone_get());
            }
            $version = explode('.', phpversion());
                        
            if( ((int)$version[0] >= 5 && (int)$version[1] >= 3) )
            {
                return parent::createFromFormat($format, $time, $timezone);
            }
            
            $time = str_replace('/', '-', $time);
            return new DateTime(date('Y-m-d', strtotime($time)), $timezone);
        }
        
        public function addinterval($interval, $intervalphp52)
        {
            $version = explode('.', phpversion());
                        
            if( ((int)$version[0] >= 5 && (int)$version[1] >= 3) )
            {
                $this->add(new DateInterval($interval));
            }
            
            $this->modify($intervalphp52);
        }
    }
}