<?php
/**
 * File : class.example.php
 *
 * PHP version 5
 */

/**
 * INCLUDES
 */

/**
 * CONSTANTS
 */

if (!defined("_CLASS_CEXAMPLE_LOADED"))
{
    define("_CLASS_CEXAMPLE_LOADED", true);

    /**
     * Cexample
     *
     * Base class for example
     *
     * @author     Boris Troja
     * @copyright  
     * @version    0.1
     */
    class Cexample extends Cbase
    {
        /***********************************************************************
         * VARIABLES
         **********************************************************************/
                 
        public $m_firstname;
        public $m_name;
        public $m_age;   
        
        /***********************************************************************
         * FUNCTIONS
         **********************************************************************/

        /**
         *
         * Cexample constructor
         *
         */

        function __construct($id, $array=array()) 
        {
            parent::__construct(TABLE_EXAMPLE);
            $tab = $this->select_for_constructor($id, $array);
            $suffix = $this->get_m_suffixe();
            
            $this->set_m_id($tab[$suffix."_ID"]);
            $this->set_m_firstname($tab[$suffix."_FIRSTNAME"]);
            $this->set_m_name($tab[$suffix."_NAME"]);
            $this->set_m_age($tab[$suffix."_AGE"]);
        }

        /**
         *
         * Getters / Setters
         *
         */     

        function get_m_firstname() {
            return $this->m_firstname;
        }

        function get_m_name() {
            return $this->m_name;
        }

        function get_m_age() {
            return $this->m_age;
        }

        function set_m_firstname($m_firstname, $bupdateDB=false) {
            $this->m_firstname = $m_firstname;
            if($bupdateDB){$this->update_variable("m_firstname",$m_firstname);}
        }

        function set_m_name($m_name, $bupdateDB=false) {
            $this->m_name = $m_name;
            if($bupdateDB){$this->update_variable("m_name",$m_name);}
        }

        function set_m_age($m_age, $bupdateDB=false) {
            $this->m_age = $m_age;
            if($bupdateDB){$this->update_variable("m_age",$m_age);}
        }
        
        /**
         *
         * Functions
         *
         */
        
        
    }
}