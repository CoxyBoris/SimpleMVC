<?php
/**
 * File : class.controller_example.php
 *
 * PHP version 5
 */

/**
 * INCLUDES
 */

/**
 * CONSTANTS
 */


if (!defined("_CLASS_CCONTROLLER_EXAMPLE_LOADED"))
{
    define("_CLASS_CCONTROLLER_EXAMPLE_LOADED", true);

    /**
    * CController_example
    *
    * Example controller class
    *
    * @author     Boris Troja
    * @copyright  2015 LEOMINOR
    * @version    0.1
    */
    class CController_example extends Cbase
    {
       
        function __construct() 
        {
            parent::__construct(null);
        }
        
  
        function action_list_example() 
        {
            global $GLOBAL_ARR_VALUES;
            
            $arr_fields = array();
            $arr_fields['EXP_AGE']['TYPE'] = 'AND';
            $arr_fields['EXP_AGE']['OPERATOR'] = '>';
            $arr_fields['EXP_AGE']['VALUE'] = 20;
        
            $GLOBAL_ARR_VALUES["EXAMPLES"] = $this->DB_select_object("Cexample", TABLE_EXAMPLE, $arr_fields);
        }
    }
}