<?php
/**
 * File : class.controller_main.php
 *
 * PHP version 5
 */

/**
 * INCLUDES
 */

/**
 * CONSTANTS
 */

if (!defined("_CLASS_CCONTROLLER_MAIN_LOADED"))
{
    define("_CLASS_CCONTROLLER_MAIN_LOADED", true);

    /**
    * CController_main
    *
    * Main controller class
    *
    * @author     Boris Troja
    * @copyright  
    * @version    0.1
    */
    class CController_main extends Cbase
    {
        /***********************************************************************
         * VARIABLES
         **********************************************************************/
        private $m_arr_action;
        public  $m_arr_selected_action;        
        
        private $m_arr_error;
        private $m_arr_warning;
        private $m_title_error;
        private $m_title_warning;
        private $m_title_success;
        private $m_title_info;
        
        private $m_b_lock;
        
        /***********************************************************************
         * FUNCTIONS
         **********************************************************************/

        /**
         *
         * CController_main constructor
         *
         */
        function __construct() 
        {
            global $GLOBAL_ARR_URL;
            
            parent::__construct(null);
            
            $this->m_action = "";
            $this->m_arr_error = $this->m_arr_warning = $this->m_arr_info = $this->m_arr_success = array();
            $this->m_title_error = $this->m_title_warning = $this->m_title_success = $this->m_title_info = "";
            
            $_SESSION['debug'] = false;
            
            $this->init();
           
            $this->m_b_lock = false;
        }
        
        /**
         *
         * Getters / Setters
         *
         */        
        public function get_m_arr_action() {
            return $this->m_arr_action;
        }

        public function get_m_arr_selected_action() {
            return $this->m_arr_selected_action;
        }

        public function get_m_arr_error() {
            return $this->m_arr_error;
        }

        public function set_m_arr_error($m_error) {
            $this->m_arr_error[] = $m_error;
        }

        public function get_m_arr_warning() {
            return $this->m_arr_warning;
        }

        public function set_m_arr_warning($m_warning) {
            $this->m_arr_warning[] = $m_warning;
        }  
        
        public function get_m_arr_info() {
            return $this->m_arr_info;
        }

        public function set_m_arr_info($m_info) {
            $this->m_arr_info[] = $m_info;
        } 
        
        public function get_m_arr_success() {
            return $this->m_arr_success;
        }

        public function set_m_arr_success($m_success) {
            $this->m_arr_success[] = $m_success;
        } 
        
        public function get_m_title_error() {
            return $this->m_title_error;
        }

        public function set_m_title_error($m_title_error) {
            $this->m_title_error = $m_title_error;
        }

        public function get_m_title_warning() {
            return $this->m_title_warning;
        }

        public function set_m_title_warning($m_title_warning) {
            $this->m_title_warning = $m_title_warning;
        }

        public function get_m_title_success() {
            return $this->m_title_success;
        }

        public function set_m_title_success($m_title_success) {
            $this->m_title_success = $m_title_success;
        }

        public function get_m_title_info() {
            return $this->m_title_info;
        }

        public function set_m_title_info($m_title_info) {
            $this->m_title_info = $m_title_info;
        }
        
        public function get_m_b_lock() {
            return $this->m_b_lock;
        }

        public function set_m_b_lock($m_b_lock) {
            $this->m_b_lock = $m_b_lock;
        }
        
        /**
         *
         * Initialyse controller
         *
         */ 
        public function init()
        {
            global $GLOBAL_MODE;
            global $GLOBAL_FOLDER;
            global $GLOBAL_ARR_VALUES;
            global $GLOBAL_PLANNING_MODE;
            
            $arr_files_to_include = array();
            
            
            $arr_files_to_include[] = CONTROLLER_PATH.'define.error.php';
            $arr_files_to_include[] = CONTROLLER_PATH.'define.warning.php';
            $arr_files_to_include[] = CONTROLLER_PATH.'define.success.php';
            $arr_files_to_include[] = CONTROLLER_PATH.'define.info.php';
            
            $arr_files_to_include[] = CONTROLLER_PATH.'define.action.php'; 
            
            foreach($arr_files_to_include as $file)
            {
                if(is_file($file))
                { 
                    include $file;
                }
            }
            
            $this->m_arr_action = $GLOBAL_ARR_ACTION;            
            
            $this->init_global_arr_values();
            $this->init_selected_action(); 
        }
        
        /**
         *
         * Initialyse the selected action
         *
         */ 
        public function init_selected_action()
        {
            global $GLOBAL_MODE;
            
            $_SESSION['new_action_token'] = "";
            if(isset($_POST["action"]))
            {
                $str_action = explode(";",$_POST["action"]);
                $_SESSION['new_action_token'] = isset($str_action['1'])?$str_action['1']:'';
                
                foreach($this->m_arr_action as $action)
                {
                    if($action["NAME"]==strtoupper($str_action[0]))
                    {
                        $this->m_arr_selected_action = $action;
                        break;
                    }
                }
            }
            else if(isset($_GET["rub"]))
            {
                
                foreach($this->m_arr_action as $action)
                {
                    if($action["NAME"]==strtoupper($_GET["rub"]))
                    {
                        $this->m_arr_selected_action = $action;
                        break;
                    }
                }
            }
            else
            {
                $this->m_arr_selected_action = $this->get_arr_action_by_name("HOME");                
            }
        }
        
        
        public function init_global_arr_values() 
        {
            global $GLOBAL_ARR_VALUES;   
            
            // SESSION
            if (isset($_SESSION['user']['id'])) 
            {             
                //IF YOU WANT TO CHECK FOR AN EXISTING SESSION
            }
            
            // FORM TOKEN
            $GLOBAL_ARR_VALUES['hashForm'] = md5('leominor' . microtime());
        }
        
        /**
         *
         * 
         */ 
        public function get_arr_action_by_name($name)
        {
            foreach($this->m_arr_action as $action)
            {
                if($action["NAME"]==$name)
                {
                    return $action;
                }
            }
        }
        
        /**
         *
         * Check if action can be executed
         *
         * @return bool true if action can be executed, false if not
         */ 
        public function check_allowed_action()
        {
            $arr_action = $this->get_m_arr_selected_action();
            
            //IF YOU WANT TO CHECK THE ACTION REQUESTED IS ALLOWED FOR THIS USER
            //UNCOMMENT THE FOLLOWING LINES
            
            /*
            //IF ACTION LOGIN
            if(isset($arr_action["NAME"]) && $arr_action["NAME"]=="LOGIN")
            {
                return true;
            }
            //ELSE IF USER IS LOGGED
            else if( isset($_SESSION["user"]["id"]) && $_SESSION["user"]["id"]>0 )
            {
                return true;
            }
            else
            {
                return false;
            }*/
            
            //COMMENT IF YOU UNCOMMENT PREVIOUS LINES
            return true;
        }
        
        /**
         *
         * Execute action
         *
         */ 
        public function execute_action($ajaxprint=null)
        {                   
            $arr_ret = array();
            $arr_action = $this->get_m_arr_selected_action();
            if(isset($arr_action["NAME"]))
            {
                if(!empty($arr_action["OBJ"]))
                {  
                    //F5 Control
                    if(isset($_SESSION['last_action_token']) && isset($_POST["action"]) && $_SESSION['last_action_token']==$_SESSION['new_action_token'])
                    {   
                        $arr_ret[] = 'ERROR_REFRESH';
                        
                        foreach($this->m_arr_action as $action)
                        {
                            if($action["NAME"]=="INDEX")
                            {
                                $this->m_arr_selected_action = $action;
                                break;
                            }
                        }
                    }
                    else
                    {      
                        if($arr_action["OBJ"]=="Ccontroller_main")
                        {
                            $obj = $this;
                        }
                        else
                        {
                            $obj = new $arr_action["OBJ"];
                        }
                        
                        $fct = $arr_action["FCT"];
                        
                        $_SESSION["done_action"] = $arr_action["NAME"];
                        $arr_ret = $obj->$fct($arr_action["LOG"]);      
                        
                        $_SESSION['last_action_token']=$_SESSION['new_action_token'];
                       
                        //Return for ajax printing
                        if(isset($ajaxprint))
                        {                                                        
                            return $arr_ret;
                        }
                    }
                    
                    $this->set_return_code($arr_ret);                    
                }
            }  
        }
        
        
        /**
         *
         * Print view
         *
         */
        public function print_view($default = false, $ajaxprint = null)
        {
            //Ajax printing
            if(isset($ajaxprint) && $ajaxprint==1)
            {
                echo $default;
                return true;
            }
            
            global $twig;
            
            global $GLOBAL_ARR_VALUES;
            global $GLOBAL_ALERT;
            
          
                
            if(!$default)
            {
                $arr_action = $this->get_m_arr_selected_action();
                if(isset($arr_action["NAME"]))
                {
                    if(isset($arr_action["LOCK"]))
                    {
                        if(!$this->get_m_b_lock())
                        {
                            echo $twig->render($arr_action["VIEW"], array("values" => $GLOBAL_ARR_VALUES,"alerts" => $GLOBAL_ALERT,"nav" => $arr_action["TAB"]));
                        }
                        else
                        {
                            echo $twig->render($arr_action["LOCK"], array("values" => $GLOBAL_ARR_VALUES,"alerts" => $GLOBAL_ALERT,"nav" => $arr_action["TAB"]));
                        }
                    }
                    else
                    {                
                        if(count($this->get_m_arr_error())>0)
                        {    
                            echo $twig->render($arr_action["ERROR"], array("values" => $GLOBAL_ARR_VALUES,"alerts" => $GLOBAL_ALERT,"nav" => $arr_action["TAB"]));
                        }
                        else if(count($this->get_m_arr_warning())>0)
                        {
                            echo $twig->render($arr_action["WARNING"], array("values" => $GLOBAL_ARR_VALUES,"alerts" => $GLOBAL_ALERT,"nav" => $arr_action["TAB"]));
                        }
                        else
                        {
                            if($arr_action["VIEW"]!='')
                            {
                                echo $twig->render($arr_action["VIEW"], array("values" => $GLOBAL_ARR_VALUES,"alerts" => $GLOBAL_ALERT,"nav" => $arr_action["TAB"]));
                            }
                            
                        }
                    }
                }
                else
                {
                    if(count($this->get_m_arr_warning())>0)
                    {
                        echo $twig->render($arr_action["WARNING"], array("values" => $GLOBAL_ARR_VALUES,"alerts" => $GLOBAL_ALERT,"nav" => $arr_action["TAB"]));
                    }
                    else
                    {
                        echo $twig->render($arr_action["ERROR"], array("values" => $GLOBAL_ARR_VALUES,"nav" => $arr_action["TAB"]));
                    
                    }
                }
            }
            else
            {
                echo $twig->render("home.html.twig", array("values" => $GLOBAL_ARR_VALUES));
            }
        }
        
        /**
         *
         * Set the m_error or m_warning
         *
         * @param array $arr_ret : array with all errors and warnings and infos or succes
         */
        public function set_return_code($arr_ret)
        {
            global $GLOBAL_ALERT;
            $GLOBAL_ALERT['error'] = $GLOBAL_ALERT['warning'] = $GLOBAL_ALERT['info'] = $GLOBAL_ALERT['success'] = "";
            
            //if $arr_ret is not an array, we format it 
            if(!is_array($arr_ret))
            {
                $arr = array();
                $arr[] = $arr_ret;
                $arr_ret = $arr;
            }
            
            foreach($arr_ret as $str)
            {
                if(is_string($str))
                {
                    if(stristr($str,'SUCCESS_TIT') && defined($str))
                    {
                        $this->set_m_title_success($str);
                    }
                    else if(stristr($str,'ERROR_TIT') && defined($str))
                    {
                        $this->set_m_title_error($str);
                    } 
                    else if(stristr($str,'WARNING_TIT') && defined($str))
                    {
                        $this->set_m_title_warning($str);
                    }
                    else if(stristr($str,'INFO_TIT') && defined($str))
                    {
                        $this->set_m_title_info($str);
                    }
                    else if(stristr($str,'ERROR') && defined($str))
                    {
                        $this->set_m_arr_error($str);
                        $GLOBAL_ALERT['b_alert_error'] = true;
                        $GLOBAL_ALERT['error'] .= constant($str)." \n";
                    } 
                    else if(stristr($str,'ERROR'))
                    {
                        $this->set_m_arr_error('ERROR_0000');
                    }
                    else if(stristr($str,'WARNING') && defined($str))
                    {
                        $this->set_m_arr_warning($str);
                        $GLOBAL_ALERT['b_alert_warning'] = true;
                        $GLOBAL_ALERT['warning'] .= constant($str)." \n";
                    }
                    else if(stristr($str,'WARNING'))
                    {
                        $this->set_m_arr_warning('ERROR_0000');
                    }
                    else if(stristr($str,'INFO') && defined($str))
                    {
                        $this->set_m_arr_info($str);
                        $GLOBAL_ALERT['b_alert_info'] = true;
                        $GLOBAL_ALERT['info'] .= constant($str)." \n";
                    }
                    else if(stristr($str,'SUCCESS') && defined($str))
                    {
                        $this->set_m_arr_success($str);
                        $GLOBAL_ALERT['b_alert_success'] = true;
                        $GLOBAL_ALERT['success'] .= constant($str)." \n";
                    }
                }
            }
        }
    }
}