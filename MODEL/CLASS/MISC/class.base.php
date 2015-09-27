<?php
/**
 * File : class.base.php
 *
 * PHP version 5
 */

/**
 * INCLUDES
 */

/**
 * CONSTANTS
 */

            
if (!defined("_CLASS_CBASE_LOADED"))
{
    define("_CLASS_CBASE_LOADED", true);

    /**
     * Cbase
     *
     * Base class with common variables and methods
     *
     * @author     Boris Troja
     * @copyright  2012 COXYWEB
     * @version    0.1
     */
    class Cbase
    {
        /***********************************************************************
         * VARIABLES
         **********************************************************************/
        private $m_obj_db_connexion;        
        private $m_db_table;
        private $m_suffixe;
        
        public $m_id;
        
        /***********************************************************************
         * FUNCTIONS
         **********************************************************************/

        /**
         *
         * Cbase constructor
         *
         */

        function __construct($table=null) 
        {
            global $mysql;  
            
            $this->set_m_obj_db_connexion($mysql);  
            
            if(isset($table))
            {
                $this->set_m_db_table($table);
                $this->set_m_suffixe(strtoupper(substr($this->m_db_table,-3)));
            }
        }
        
        /**
         *
         * Cbase destructor
         *
         */
        
        function __destruct() 
        {            
            
        }

        /**
         *
         * Getter for all variables
         *
         * @param string $var : str, the variable to return
         * @return mixed value of the variable
         *
         */
        
        public function get_variable($var)
        {
            return $this->$var;
        }
        
        
        /**
         *
         * Getters / Setters
         *
         */       

        public function get_m_id() {
            return $this->m_id;
        }
        
        public function set_m_id($m_id) {
            $this->m_id = $m_id;
        }
                
        public function get_m_obj_db_connexion() {
            return $this->m_obj_db_connexion;
        }

        public function set_m_obj_db_connexion($m_obj_db_connexion) {
            $this->m_obj_db_connexion = $m_obj_db_connexion;
        }

        public function get_m_db_table() {
            return $this->m_db_table;
        }

        public function set_m_db_table($m_db_table) {
            $this->m_db_table = $m_db_table;
        }
        
        public function get_m_suffixe() {
            return $this->m_suffixe;
        }

        public function set_m_suffixe($m_suffixe) {
            $this->m_suffixe = $m_suffixe;
        }   
        
        public function enable_debug() {
            $_SESSION["debug"] = true;
        }
        
        public function diseable_debug() {
            $_SESSION["debug"] = false;
        }
        
        /**
         *
         * Update for all variables
         *
         * @param string $var : str, the variable to update
         *
         */
        
        public function update_variable($var,$value,$balltags=false)
        {
            $Cstring = new Cstring();
            
            if($value === NULL || $value === 'null')
            {
                $q = "UPDATE ".$this->get_m_db_table()." SET
                             ".$this->get_m_suffixe()."_".strtoupper(substr($var, 2))." = NULL
                             WHERE ".$this->get_m_suffixe()."_ID = ".$this->get_m_id();
            }
            else if(!$balltags)
            {
                $q = "UPDATE ".$this->get_m_db_table()." SET
                             ".$this->get_m_suffixe()."_".strtoupper(substr($var, 2))." = '".$Cstring->format_DBinsert($value)."'
                             WHERE ".$this->get_m_suffixe()."_ID = ".$this->get_m_id();
            }
            else
            {
                $q = "UPDATE ".$this->get_m_db_table()." SET
                             ".$this->get_m_suffixe()."_".strtoupper(substr($var, 2))." = '".strval($value)."'
                             WHERE ".$this->get_m_suffixe()."_ID = ".$this->get_m_id();
            }
         
            if(isset($_SESSION['debug']) && $_SESSION['debug'])
            {   
                echo $q.'<br>';
            }
            
            $this->get_m_obj_db_connexion()->query($q);
        }
        
        /**
         *
         * Select for constructors
         *
         * @param integer $id : DB id
         * @param array $array : array values
         * @return array tab DB or array values
         *
         */
        
        public function select_for_constructor($id, $array=array())
        {
            $tab = array();
            
            
            if(is_string($id))
            {
                $qsel = "SELECT * FROM ".$this->get_m_db_table()."
                                 WHERE ".$this->get_m_suffixe()."_ID = '".$id."'";
                $tab = $this->get_m_obj_db_connexion()->queryRow($qsel,true);   
            }
            elseif($id>0)
            {
                $qsel = "SELECT * FROM ".$this->get_m_db_table()."
                                 WHERE ".$this->get_m_suffixe()."_ID = '".$id."'";
                $tab = $this->get_m_obj_db_connexion()->queryRow($qsel,true);
            }
            else
            {
                $tab = $array;
            }
            
            return $tab;
        }
        
        /**
         *
         * Get object list
         *
         * @param string $object_type : Type of objects in the list
         * @param constant $table_object : Database table of objects in the list
         * @param constant $table_link : Database table of links between the object calling the function and the objects in the list
         * @param constant $field_link : Database field referencing the objects in the list
         * @param constant $field_parent : Database field referencing the object calling the function  
         * @param interger $limit_start : limit start for DB request   
         * @param interger $limit_lenght : limit lenght for DB request 
         * @return array list of objects
         * 
         */
        
        public function get_object_list($object_type, $table_object, $field_parent=null, $limit_start=null, $limit_lenght=null, $table_link=null, $field_link=null)
        {
            $list = array();
            
            $limit = '';
            if(isset($limit_lenght))
            {
                if(isset($limit_start))
                {
                    $limit = ' LIMIT '.$limit_start.','.$limit_lenght;
                }
                else
                {
                    $limit = ' LIMIT '.$limit_lenght;
                }                    
            }
            
            //If join table exist
            if(isset($table_link))
            {
                $qsel = "SELECT ".$field_link." FROM ".$table_link."
                                     WHERE ".$field_parent." = ".$this->get_m_id().$limit;
                $ids = $this->get_m_obj_db_connexion()->queryTab($qsel,true);

                $obj_ids = array();
                foreach($ids as $id)
                {
                    array_push($obj_ids, $id[$field_link]);
                }

                $str_obj_ids = implode(',',$obj_ids);

                if($str_obj_ids!='')
                {
                    $qsel = "SELECT * FROM ".$table_object."
                                         WHERE ".strtoupper(substr($table_object,-3))."_ID IN (".$str_obj_ids.")".$limit;
                    $results = $this->get_m_obj_db_connexion()->queryTab($qsel,true);
                }
                else
                {
                    $results = array();
                }
            }
            else
            {
                $qsel = "SELECT * FROM ".$table_object;
                if(isset($field_parent))
                {
                    $qsel .= " WHERE ".$field_parent." = ".$this->get_m_id();
                }
                $qsel .= $limit;
                
                $results = $this->get_m_obj_db_connexion()->queryTab($qsel,true);
            }
            
            foreach($results as $result)
            {
                $Cobject = new $object_type(0,$result);
                array_push($list, $Cobject);
            }
            
            return $list;
        }
        
        
        /**
         *
         * Delete in table
         *
         */
        public function DB_delete_advanced($table, $fields='')
        {

            $qdel = "DELETE FROM ".$table;
            $where = "";
            
            if(!empty($fields))
            {
                foreach ($fields as $key => $value) 
                {
  
                        $arr_keys = array_keys($fields);
                        if($key!=reset($arr_keys))
                        {
                            $where .= " ".$value['TYPE']." ";
                        }
                        if(isset($value['OPERATOR']) && $value['OPERATOR']=='IN')
                        {
                            $where .= $key." ".$value['OPERATOR']." (".$value['VALUE'].")";
                        }
                        else if(isset($value['OPERATOR']) && $value['OPERATOR']=='BETWEEN')
                        {
                            $where .= $key." ".$value['OPERATOR']." ".$value['VALUE1']." AND ".$value['VALUE2']."";
                        }                            
                        else if(isset($value['OPERATOR']) && $value['OPERATOR']!='')
                        {
                            $where .= $key." ".$value['OPERATOR']." '".$value['VALUE']."'";
                        }
                        else                    
                            $where .= $key." like '%".$value['VALUE']."%'";
                }                
            }
            if(!empty($where))
            {
                $qdel .= " WHERE ";
            }
                
            $qdel .= $where;
            
            if(isset($_SESSION['debug']) && $_SESSION['debug'])
            {   
                echo $qdel.'<br>';
            }

            if($this->get_m_obj_db_connexion()->queryTab($qdel))
            {
                return true;
            }
        }
        
        
        /**
         *
         * Select in table
         *
         * @param string $object_type : Type of objects in the list
         * @param constant $table_object : Database table of objects in the list
         * @param constant $fields : Database field to search in
         * @param interger $limit_start : limit start for DB request   
         * @param interger $limit_lenght : limit lenght for DB request
         * @param bool $b_return_object : if set, return only one object
         * @return array list of objects
         * 
         */        
        public function DB_select_object($object_type, $table_object, $fields='', $limit_start=null, $limit_lenght=null, $b_return_object=false)
        {
            $list = array();
            $Cobject = null;
            
            $limit = '';
            if(isset($limit_lenght))
            {
                if(isset($limit_start))
                {
                    $limit = ' LIMIT '.$limit_start.','.$limit_lenght;
                }
                else
                {
                    $limit = ' LIMIT '.$limit_lenght;
                }                    
            }
            
            
            $qsel = "SELECT * FROM ".$table_object;
            $where = "";
            $order = " ";
            $groupby = " ";
            
            if(!empty($fields))
            {
                foreach ($fields as $key => $value) 
                {
                    if(isset($value['GROUP']))
                    {
                        if($groupby==" ")
                        {
                            $groupby .= "GROUP BY ".$key." ";
                        }
                        else
                        {
                            $groupby .= ", ".$key." ";
                        }
                    }
                    else if(isset($value['ORDER']) && $value['ORDER']!='' && !isset($value['TYPE']))
                    {
                        if($order==" ")
                        {
                            $order .= "ORDER BY ".$key." ".$value['ORDER']." ";
                        }
                        else
                        {
                            $order .= ", ".$key." ".$value['ORDER']." ";
                        }
                    }
                    else
                    {    
                        $arr_keys = array_keys($fields);
                        
                        if($key!=reset($arr_keys))
                        {
                            $where .= " ".$value['TYPE']." ";
                        }
                                               
                        
                        if(isset($value['OPERATOR']) && $value['OPERATOR']=='IN')
                        {
                            $where .= $key." ".$value['OPERATOR']." (".$value['VALUE'].")";
                        }
                        else if(isset($value['OPERATOR']) && $value['OPERATOR']=='BETWEEN')
                        {
                            $where .= "(".$key." ".$value['OPERATOR']." '".$value['VALUE1']."' AND '".$value['VALUE2']."')";
                        }                            
                        else if(isset($value['OPERATOR']) && $value['OPERATOR']!='')
                        {
                            if(!isset($value['VALUE']))
                            {
                               $where .= $key." IS NULL"; 
                            }
                            else
                            {
                               $where .= $key." ".$value['OPERATOR']." '".$value['VALUE']."'"; 
                            }
                            
                        }
                        else                    
                            $where .= $key." like '%".$value['VALUE']."%'";
                        
                        if(isset($value['ORDER']) && $value['ORDER']!='')
                        {
                            if($order==" ")
                            {
                                $order .= "ORDER BY ".$key." ".$value['ORDER']." ";
                            }
                            else
                            {
                                $order .= ", ".$key." ".$value['ORDER']." ";
                            }
                        }
                    }
                }                
            }
            if(!empty($where))
            {
                $qsel .= " WHERE ";
            }
                
            $qsel .= $where.$groupby.$order.$limit;
            
            
            if(isset($_SESSION['debug']) && $_SESSION['debug'])
            {   
                var_dump($qsel.'<br>');
            }

            if($b_return_object)
            {
                $result = $this->get_m_obj_db_connexion()->queryRow($qsel,true); 
                if(count($result)>1)
                {
                    $Cobject = new $object_type(0,$result);
                }
                return $Cobject;
            }
            else
            {
                $results = $this->get_m_obj_db_connexion()->queryTab($qsel,true);            

                foreach($results as $result)
                {
                    $Cobject = new $object_type(0,$result);
                    array_push($list, $Cobject);
                }
                return $list;
            }
        }
        
        
        /**
         *
         * Count in table
         *
         * @param constant $table : Database table
         * @param constant $fields : Database field to search in
         * @return array list of objects
         * 
         */        
        public function DB_count_object($table, $fields)
        {
            $qsel = "SELECT COUNT(".strtoupper(substr($table,-3))."_ID) FROM ".$table;
            
            $where = "";
            if(!empty($fields))
            {
                foreach ($fields as $key => $value) 
                {
                    if(isset($value['ORDER']) && $value['ORDER']!='')
                    {
                        
                    }
                    else
                    {     
                        $arr_keys = array_keys($fields);
                        if($key!=reset($arr_keys))
                        {
                            $where .= " ".$value['TYPE']." ";
                        }
                        if(isset($value['OPERATOR']) && $value['OPERATOR']=='IN')
                        {
                            $where .= $key." ".$value['OPERATOR']." (".$value['VALUE'].")";
                        }
                        else if(isset($value['OPERATOR']) && $value['OPERATOR']=='BETWEEN')
                        {
                            $where .= $key." ".$value['OPERATOR']." ".$value['VALUE1']." AND ".$value['VALUE2']."";
                        } 
                        else if(isset($value['OPERATOR']) && $value['OPERATOR']!='')
                        {
                            $where .= $key." ".$value['OPERATOR']." '".$value['VALUE']."'";
                        }
                        else                    
                            $where .= $key." like '%".$value['VALUE']."%'"; 
                    }
                }                
            }
            if(!empty($where))
            {
                $qsel .= " WHERE ";
            }
                
            $qsel .= $where;
            
            if(isset($_SESSION['debug']) && $_SESSION['debug'])
            {
                echo $qsel.'<br>';
            }

            $count = $this->get_m_obj_db_connexion()->queryItem($qsel);
            return $count[0];            
        }
        
        /**
         *
         * Database insert
         *
         * @param mixed $table : Database table
         * @param mixed $arr_value : array with field list and values
         * @param integer $id : id to update
         * @param mixed $arr_where : special where close
         * @return mixed id of inserted item
         * 
         */
        
        public function DB_update($table, $arr_value, $id, $arr_where, $arr_tags=array())
        {
            $Cstring = new Cstring();
            $allowable_tags=null;
            $values = $fields = array();
            
            foreach($arr_value as $field => $value)
            {
                array_push($fields, $field);
                if(isset($arr_tags[$field]))
                {
                    $allowable_tags = $arr_tags[$field];
                }
                
                array_push($values, "'".addslashes($Cstring->format_DBinsert($value,$allowable_tags))."'");
                
                $allowable_tags=null;
            }
            
            $qins = "UPDATE ".$table." SET ";
            foreach($fields as $keyfield => $field)
            {
                $qins .= $field."=".$values[$keyfield];
                $arr_keys = array_keys($fields);
                if($keyfield!=end($arr_keys))
                {
                    $qins .= ',';
                }
            }
            
            $bexecute = false;
            if($id>0)
            {
                $qins .= ' WHERE '.strtoupper(substr($table,-3)).'_ID = '.$id;
                $bexecute = true;
            }
            else if(!empty($arr_where))
            {
                $where = "";
                foreach ($arr_where as $key => $value) 
                {  
                    $arr_keys = array_keys($fields);
                    if($key!=reset($arr_keys))
                    {
                        $where .= " ".$value['TYPE']." ";
                    }
                    if(isset($value['OPERATOR']) && $value['OPERATOR']=='IN')
                    {
                        $where .= $key." ".$value['OPERATOR']." (".$value['VALUE'].")";
                    }
                    else if(isset($value['OPERATOR']) && $value['OPERATOR']=='BETWEEN')
                    {
                        $where .= $key." ".$value['OPERATOR']." ".$value['VALUE1']." AND ".$value['VALUE2']."";
                    }                            
                    else if(isset($value['OPERATOR']) && $value['OPERATOR']!='')
                    {
                        $where .= $key." ".$value['OPERATOR']." '".$value['VALUE']."'";
                    }
                    else                    
                        $where .= $key." like '%".$value['VALUE']."%'";
                }                
            }
            if(!empty($where))
            {
                $qins .= " WHERE ";
                $qins .= $where;
                $bexecute = true;
            }           
            
            if(isset($_SESSION['debug']) && $_SESSION['debug'])
            {
                echo $qins.'<br>';
            }
            
            if($bexecute == true)
            {
                $this->get_m_obj_db_connexion()->query($qins);

                $pdo = $this->get_m_obj_db_connexion()->getConnexion();
                $id = $pdo -> lastInsertId(); 

                return $id;
            }
        }
        
        /**
         *
         * Database update
         *
         * @param mixed $table : Database table
         * @param mixed $arr_value : array with field list and values
         * @return mixed id of inserted item
         * 
         */
        
        public function DB_insert($table, $arr_value, $arr_tags=array())
        {
            $Cstring = new Cstring();
            $allowable_tags=null;
            $values = $fields = array();
            
            foreach($arr_value as $field => $value)
            {
                array_push($fields, $field);
                if(isset($arr_tags[$field]))
                    $allowable_tags = $arr_tags[$field];
                
                array_push($values, "'".addslashes($Cstring->format_DBinsert($value,$allowable_tags))."'");
                
                $allowable_tags=null;
            }
            
            $str_fields = implode(",", $fields);
            $str_values = implode(",", $values);
            
            $qins = "INSERT INTO ".$table."(".$str_fields.") VALUES(
                         ".$str_values."    
                         )";
            

            if(isset($_SESSION['debug']) && $_SESSION['debug'])
            {
                echo $qins.'<br>';
            }
            
            $this->get_m_obj_db_connexion()->query($qins);

            $pdo = $this->get_m_obj_db_connexion()->getConnexion();
            $id = $pdo -> lastInsertId();               
            
            return intval($id);
        }
        
        /**
         *
         * Database delete
         *
         * @param $table : Database table
         * @param $id : id of element to delete
         * 
         */
        
        public function DB_delete($table, $id)
        {
            $qdel = "DELETE FROM ".$table." 
                        WHERE ".strtoupper(substr($table,-3))."_ID = ".$id;
            $this->get_m_obj_db_connexion()->query($qdel);
        }
        
        /**
         *
         * Check if data exist in database
         *
         * @param $table : Database table
         * @param $id : id of element to check
         * 
         */
        
        public function exist_data($table, $id, $id_field='')
        {
            if($id_field=='')
            {
                $field = strtoupper(substr($table,-3))."_ID";
            }
            else
            {
                $field = $id_field;
            }
            
            $qsel = "SELECT * FROM ".$table."
                                 WHERE ".$field." = '".$id."'";
            $data = $this->get_m_obj_db_connexion()->queryRow($qsel,true);
            
            if(isset($data[$field]))
            {
                return $data[strtoupper(substr($table,-3))."_ID"];
            }
            else
            {
                return false;
            }
        }
        
          /**
         *
         * last_id
         * 
         */
        public function last_insert_id()
        {
           $pdo = $this->get_m_obj_db_connexion()->getConnexion();
           return $pdo -> lastInsertId();     
        }
        
         /**
         *
         * test_empty
         * 
         */
        public function test_empty($var, &$arr_return, $error)
        {
            if(empty($var))
            {
                $arr_return[] = $error;
            }
        }
        
        /**
         *
         * test_isset
         * 
         */
        public function test_isset($var, &$arr_return, $error)
        {
            if(!isset($var))
            {
                $arr_return[] = $error;
            }
        }
        
        /**
         *
         * Delete all the files in the folder
         *
         * @param string $folderpath : folder path
         * 
         */
        public function delete_files($folderpath)
        {
            $arr_file = scandir($folderpath);
            foreach($arr_file as $file)
            {
                if(is_file($folderpath.$file) && $file!=".." && $file!=".")
                {
                    unlink($folderpath.$file);
                }
            }
        }
        
        /**
         *
         * format a date in text
         *
         * @param string $date : date
         * 
         */
        public function format_date_text($date,$b_date_only=false,$force_user_language='')
        {
            global $Ccontroller_main;
            global $GLOBAL_DAYS;
            global $GLOBAL_MONTHS;
            
            if($force_user_language!='')
            {
                $user_language = $force_user_language;
            }
            else
            {
                $user_language = $Ccontroller_main->get_m_user_language();
            }
            
            //YESTERDAY
            if(!$b_date_only && date("Y-m-d", strtotime("yesterday")) == date("Y-m-d",strtotime($date)))
            {
                
                switch($user_language)
                {
                    case 'FR': 
                        $date = 'Hier';
                        break;
                    case 'EN': 
                        $date = 'Yesterday';
                        break;
                }  
            }
            
            //TODAY
            else if(!$b_date_only && date("Y-m-d") == date("Y-m-d",strtotime($date)))
            {
                $Cdate_time = new Cmydatetime($date, new DateTimeZone('EUROPE/Paris'));
                $Cdate_time_now = new Cmydatetime("now", new DateTimeZone('EUROPE/Paris'));
                $interval = $Cdate_time->diff($Cdate_time_now);
                //echo $Cdate_time_now->format('H i s').':'.$Cdate_time->format('H i s').'<br>';
                switch($user_language)
                {
                    case 'FR':
                        if($interval->H>0){$date = $interval->format('Il y a %h heure(s), %i minute(s)');}
                        else if($interval->i>0){$date = $interval->format('Il y a %i minute(s)');}
                        else {$date = $interval->format('Il y a %s seconde(s)');}
                        break;
                    case 'EN': 
                        if($interval->H>0){$date = $interval->format('%h hour(s), %i minute(s) ago');}
                        else if($interval->i>0){$date = $interval->format('%i minute(s) ago');}
                        else {$date = $interval->format('%s second(s) ago');}                        
                        break;
                }  
            }
            
            //OTHER DATE
            else
            {
                $Cdate_time = new Cmydatetime($date, new DateTimeZone('EUROPE/Paris'));
                $date ='';

                switch($user_language)
                {
                    case 'FR': 
                        $date = $GLOBAL_DAYS[$Cdate_time->format('N')]['fr'].' '.$Cdate_time->format('d').' '.$GLOBAL_MONTHS[$Cdate_time->format('n')]['fr'].' '.$Cdate_time->format('Y');
                        break;
                    case 'EN': 
                        $date = $GLOBAL_DAYS[$Cdate_time->format('N')]['en'].', '.$GLOBAL_MONTHS[$Cdate_time->format('n')]['en'].' '.$Cdate_time->format('d').' '.$Cdate_time->format('Y');
                        break;
                }            
            }
            
            return $date;
        }
        
        /**
         *
         * Get the pages links
         * 
         * @param integer $total total number of elements
         * @param integer $current current page
         * @param integer $nb_per_page number of element per page
         * @param string $rub current module
         * @param array $array_submitted array of submitted criterias
         * @return html pages links
         *
         */ 
        public function get_pages($total,$current,$nb_per_page,$rub,$array_submitted=array())
        {
            $str_return = '';
            $arr_return = '';
            
            $get = '';
            
            foreach($array_submitted as $keysubmitted => $valuesubmitted)
            {
                if(!empty($valuesubmitted))
                {
                    $get .= strtolower('&'.$keysubmitted.'='.$valuesubmitted);
                }
            }
            
            if($total>$nb_per_page)
            {
                $nbpage = ceil($total/$nb_per_page);

                if($current>1)
                {
                    $prec = $current-1;
                    $href = "index.php?rub=".$rub."&p=".$prec.$get;

                    $arr_return[] = array('link' => $href, 'number' => 'fa fa-arrow-left', 'active' => 0, 'bnumber' => 0);
                }

                if($nbpage <10)
                {
                    $linkstart = 1;
                    $linkstop = $nbpage;
                }
                else if($current<=5)
                {                  
                    $linkstart = 1;
                    $linkstop = 10;
                }
                else if( $current>5 && $current<($nbpage-5) )
                {                   
                    $linkstart = $current-4;
                    $linkstop = $current+5;
                }
                else if( $current>=($nbpage-5) )
                {                    
                    $linkstart = $nbpage-9;
                    $linkstop = $nbpage;
                }
                

                for($ipage=$linkstart;$ipage<=$linkstop;$ipage++)
                {
                    $href = "index.php?rub=".$rub."&p=".$ipage.$get;              
                    if($current==$ipage)
                    {
                        $arr_return[] = array('link' => '#', 'number' => $ipage, 'active' => 1, 'bnumber' => 1);
                    }
                    else
                    {
                        $arr_return[] = array('link' => $href, 'number' => $ipage, 'active' => 0, 'bnumber' => 1);
                    }
                }

                if($current<$nbpage)
                {
                    $suiv = $current+1;
                    $href = "index.php?rub=".$rub."&p=".$suiv.$get;

                    $arr_return[] = array('link' => $href, 'number' => 'fa fa-arrow-right', 'active' => 0, 'bnumber' => 0);
                }
                
                
            }   
            return $arr_return;
        }
    }
}