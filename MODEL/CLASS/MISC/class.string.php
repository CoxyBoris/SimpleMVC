<?php
/**
 * File : class.string.php
 *
 * PHP version 5
 */

/**
 * INCLUDES
 */

/**
 * CONSTANTS
 */

if (!defined("_CLASS_CSTRING_LOADED"))
{
    define("_CLASS_CSTRING_LOADED", true);

    /**
     * Cstring
     *
     * Class to make operations on strings
     *
     * @author     Boris Troja
     * @copyright  2012 COXYWEB
     * @version    0.1
     */
    class Cstring
    {
        /***********************************************************************
         * VARIABLES
         **********************************************************************/
                
        /***********************************************************************
         * FUNCTIONS
         **********************************************************************/

        /**
         *
         * Cstring constructor
         *
         */
        function __construct() 
        {
            
        }
        
        /**
         *
         * Getters / Setters
         *
         */       
          
        
        /**
         *
         * Cut string
         *
         * @param  mixed $str : string to transform
         * @param  string $nb_word : number of word
         * @param  string $delim : end of str
         * @return string string formated
         *
         */  
        function cut_str($str, $nb_word, $delim='...')
        {
            $arrDelimiters = array(" ", ",", ";", "\n");
            $uniformText = str_replace($arrDelimiters, "-|-", $str);
            $stringTab = explode("-|-", $uniformText);
            
            for($i=0;$i<$nb_word;$i++)
            {
                $txt.=" ".$stringTab[$i] ;
            }
            if (count($stringTab) > $nb_word) $txt.= $delim ;
            return $txt ;
        }

        /**
         *
         * Prepare string for DB insertion
         *
         * @param  mixed $str : string to transform
         * @param  string $allowable_tags : string of allowed tags
         * @return string string formated
         *
         */        
        public function format_DBinsert($str, $allowable_tags=null)
        {    
            //var_dump($str);
            $str = stripslashes(strip_tags(strval($str), $allowable_tags));
            $str = str_replace("'", "\'", $str);
            
            return $str;
        }
        
        /**
         *
         * clean string
         *
         * @param  string $str : string to be cleaned
         * @return string string cleaned
         *
         */        
        public function clean($str)
        {
           $str_cleaned = strtr($str,
                          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜİàáâãäåçè&eacute;êëìíîïğòóôõöùúûüıÿ',
                          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
           $str_cleaned = preg_replace('/([^.a-z0-9]+)/i', '-', $str_cleaned);
           $str_cleaned = strtolower($str_cleaned);
           return $str_cleaned;
        }
        
        /**
         *
         * clean url
         *
         * @param  string $str : string to be cleaned
         * @return string string cleaned
         *
         */
        function Clean_url($str)
        {
           $str_cleaned = strtr($str,
                          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜİàáâãäåçè&eacute;êëìíîïğòóôõöùúûüıÿ',
                          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
           $str_cleaned = str_replace(".", "_", $str_cleaned);
           $str_cleaned = preg_replace('/([^.a-z0-9\/]+)/i', '-', $str_cleaned);
           $str_cleaned = strtolower($str_cleaned);
           return $str_cleaned;
        }
        
        /**
         *
         * Check if the variable is empty and add the error to the array error
         *
         * @param string $str : The string to check
         * @param array $arr_error : array of current errors
         * @param string $error : error to add in the array
         * @param string $inputname the input name
         * 
         */        
        public function check_empty($str, &$arr_error, $error, $inputname)
        {
            global $GLOBAL_ARR_INPUT_ERROR;            
            
            if(empty($str))
            {
                $arr_error[] = $error;
                $GLOBAL_ARR_INPUT_ERROR[] = $inputname;
                
                return 1;
            }
            
            return 0;
        }
        
        /**
         *
         * Check if the variable is equal to zero and add the error to the array error
         *
         * @param string $str : The string to check
         * @param array $arr_error : array of current errors
         * @param string $error : error to add in the array
         * @param string $inputname the input name
         * 
         */        
        public function check_zero($str, &$arr_error, $error, $inputname)
        {
            global $GLOBAL_ARR_INPUT_ERROR;            
           
            if(is_int($str) && $str==0)
            {
                $arr_error[] = $error;
                $GLOBAL_ARR_INPUT_ERROR[] = $inputname;
                
                return 1;
            }
            
            return 0;
        }
        
        /**
         *
         * Check if the variable is an email and add the error to the array error
         *
         * @param string $email : The string to check
         * @param array $arr_error : array of current errors
         * @param string $arr_error : error to add in the array
         * 
         */        
        public function check_email($email, &$arr_error)
        {
            $email = strtolower($email);

            if (strlen($email) < 6)
            {
                $arr_error[] = 'ERROR_INV_0001';
                return 1;
            }
            else if (strlen($email) > 255) 
            {
                $arr_error[] = 'ERROR_INV_0002';
                return 1;
            }
            else if (!@ereg("@",$email))
            { 
                $arr_error[] = 'ERROR_INV_0003';
                return 1;
            }
            else if (!preg_match("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}\$/i", $email))
            {
                $arr_error[] = 'ERROR_INV_0004';
                return 1;
            }
            
            return 0;
        }

        
        /**
         *
         * Check if the variable is a float and add the error to the array error
         *
         * @param string $float : The float to check
         * @param array $arr_error : array of current errors
         * @param string $error : error to add in the array
         * @param string $inputname the input name
         * 
         */        
        public function check_float($float, &$arr_error, $error, $inputname)
        {
            global $GLOBAL_ARR_INPUT_ERROR;            
            
            if(!is_numeric($float))
            {
                $arr_error[] = $error;
                $GLOBAL_ARR_INPUT_ERROR[] = $inputname;
                
                return 1;
            }
            
            return 0;
        }
        
        /**
         *
         * Check presence in $arr for each $arr_check
         *
         * @param array $arr : The array to search in
         * @param array $arr_check : array of elements to search for
         * @param array $arr_error : array of current errors
         * @param string $error : error to add in the array
         * @param string $inputname the input name
         * 
         */        
        public function check_pos($arr, $arr_check, &$arr_error, $error, $inputname)
        {
            global $GLOBAL_ARR_INPUT_ERROR;            
            
            foreach($arr_check as $check)
            {
                if(strpos($arr, $check)!==false)
                {
                    return 0;
                }
            }
            
            $arr_error[] = $error;
            $GLOBAL_ARR_INPUT_ERROR[] = $inputname;
            return 1;            
        }
        
        /**
         *
         * Check login
         *
         * @param string $str : The string to check
         * @param array $arr_error : array of current errors
         * @param string $error : error to add in the array
         * @param string $inputname the input name
         * 
         */        
        public function check_login($str, &$arr_error, $error, $inputname)
        {
            global $GLOBAL_ARR_INPUT_ERROR;            
            
            if(strlen($str)<6)
            {
                $arr_error[] = $error;
                $GLOBAL_ARR_INPUT_ERROR[] = $inputname;
                return 1;                      
            }
            
            return 0;                  
        }
        
        /**
         *
         * Check password
         *
         * @param string $str : The string to check
         * @param array $arr_error : array of current errors
         * @param string $error : error to add in the array
         * @param string $inputname the input name
         * 
         */        
        public function check_password($str, &$arr_error, $error, $inputname)
        {
            global $GLOBAL_ARR_INPUT_ERROR;            
            
            if(strlen($str)<6)
            {
                $arr_error[] = $error;
                $GLOBAL_ARR_INPUT_ERROR[] = $inputname;
                return 1;                      
            }
            
            return 0;          
        }
        
        /**
         *
         * Generate a random password
         *
         * @param string $nbchars : Number of caracters in password
         * 
         */     
        function random_password($nbchars = 8) 
        {
            $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            return substr(str_shuffle($letters), 0, $nbchars);
        }
    }
}