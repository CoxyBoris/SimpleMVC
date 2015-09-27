<?php

/* * ****************************************************************************
 * Description : cette classe permet d'avoir un accès facilit&eacute; à une base de
 *               donn&eacute;es MySQL. Elle offre un certain nombre de fonctionnalit&eacute;s
 *               comme la cr&eacute;ation d'une connexion, l'ex&eacute;cution d'une requète
 *               devant retourner un champ, une ligne ou un ensemble de ligne ou
 *               encore l'affichage du temps d'ex&eacute;cution d'un requète, la r&eacute;cup&eacute;ration
 *               du dernier enregistrement ins&eacute;r&eacute; au cours de la session...
 *              en utilisant PDO
 * ***************************************************************************** */

 if (!defined("_DB_CLASS_LOADED")) {
  define("_DB_CLASS_LOADED", true); 

class Connection {

    public function __construct() {
        
    }
    
    public function __destruct() {
        self::$_instance = null;
    }

    /////////////////////////// VARIABLES PRIVEES /////////////////////////
    private $id;                // l'index de la connexion à utiliser pour effectuer les requètes        
    private $debug_time = false;    // indique si ont doit afficher le temps d'ex&eacute;cution des requètes SQL
    private $time_deb;              // variable contenant le temps avant ex&eacute;cution de la requète
    private $time_end;              // variable contenant le temps après ex&eacute;cution de la requète
    /////////////////////////// VARIABLES PUBLICS /////////////////////////
    public $result;           // index de r&eacute;sultat de la dernière requète ex&eacute;cut&eacute;e
    public $rows;             // nombre de lignes renvoy&eacute;es par la dernière requète
    public $fields;           // nombre de colonnes renvoy&eacute;s par la dernière requète
    public $data;             // tableau ou texte contenant le r&eacute;sultat de la dernière requète
    var $last_insert_id;   // nombre contenant l'id (champ de type AUTO_INCREMENTED) du dernier
    // enregistrement ins&eacute;r&eacute;
    public static $_instance;

    /////////////////////////// FONCTIONS MEMBRES /////////////////////////

    /*
     * Function Connexion return the connexion's instance with PDO
     */
    public static function getConnexion() {

        if (!isset(self::$_instance)) { //if ther's no active connexions
            try {
                
                self::$_instance = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
                //echo self::$_syntaxe ;
            } catch (PDOException $e) {
                echo $e;
            }
        }
        return self::$_instance; /* Connexion return */
    }

    //=====================================================================
    // Description : permet d'ex&eacute;cuter une requète. Aucun r&eacute;sultat n'est
    //  renvoy&eacute; par cette fonction. Elle doit être utilis&eacute; pour effectuer
    //  des insertions, des updates... Elle est de même utilis&eacute;e par les
    //  autres fonction de la classe comme queryItem() et queryTab().
    // Statut : PUBLIC
    // Entr&eacute;es :
    //  - $query : la requète à ex&eacute;cuter
    // Sorties :
    //  - true/false suivant si la requète c'est bien pass&eacute;e
    // Variables globales : -
    //=====================================================================

    public function query($query) {
        
        try {
            
            $pdo = self::getConnexion();

            // si on veut afficher le temps d'execution de la requete : initialisation
            if ($this->debug_time){
                $this->initTimeQuery();
            }
            if(preg_match("#SELECT#i", "'.$query.'")){
                
                // execution de la requète
                $this->result = $pdo->query($query);
                $prepare = $pdo->prepare($query);
                $prepare->execute();
            }else{
                $this->result = $prepare = $pdo->prepare($query);
                $prepare->execute();
            }
            

            // si on veut afficher le temps d'execution de la requete : affichage
            if ($this->debug_time){
                $this->displayTimeQuery($query);
                
            }
            // sauvegarde du nombre de champs renvoy&eacute;s par la requète
            $this->fields = $prepare->rowCount();

            // sauvegarde du nombre de lignes renvoy&eacute;s par la requète
            $arrayOfRows = $prepare->fetchAll();
            $this->rows = count($arrayOfRows);

            // si on a ex&eacute;cut&eacute; une requete de type INSERT, on recupere le dernier identifiant d'objet
            //$this->getLastOid($query);

            return true;
        } catch (PDOException $e) {
            //var_dump($e->__toString());exit;
            $this->error("<FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FF0000\" SIZE=\"2\"><B>" . $e->__toString() . "</B></FONT><BR> ");
            unset($this->last_insert_id);
            return false;
        }
    }

    //=====================================================================
    // Description : permet d'ex&eacute;cuter une requète devant renvoyer une valeur.
    // Statut : PUBLIC
    // Entr&eacute;es :
    //  - $query : la requète à ex&eacute;cuter
    //  - $onlyAsso : true : ne recupere que les enregistrement avec le nom de champs
    //                false (defaut) : recupere le nom du champ et sont numero
    // Sorties :
    //  - $tab_result[0] : le r&eacute;sultat de la requète sous forme de chaine.
    // Variables globales : -
    //=====================================================================
    function queryItem($query, $onlyAsso = false) {

        // suppression de l'ancien r&eacute;sultat
        unset($this->data);
        $this->data = "";

        // definition de la maniere dont le resultat de la requete doit etre renvoye
        if ($onlyAsso) {
            $mode = 'assoc';
        } else {
            $mode = 'both';
        }

        // execution de la requete
        if ($this->query($query) and $this->rows > 0) {

            if ($mode == 'assoc') {

                // PDO fetch_assoc equivalence mysql_fetch_assoc
                $tab_result = $this->result->fetch(PDO::FETCH_ASSOC);
            } elseif ($mode == 'both') {

                //  PDO fetch BOTH by default
                $tab_result = $this->result->fetch();
            }


            $this->data = $tab_result;
        }

        // renvoie du r&eacute;sultat
        return($this->data);
    }

    //=====================================================================
    // Description : permet d'ex&eacute;cuter une requète devant renvoyer une seule
    //  ligne de r&eacute;sultat.
    // Statut : PUBLIC
    // Entr&eacute;es :
    //  - $query : la requète à ex&eacute;cuter
    //  - $onlyAsso : true : ne recupere que les enregistrement avec le nom de champs
    //                false (defaut) : recupere le nom du champ et sont numero
    // Sorties :
    //  - $result : le r&eacute;sultat de la requète sous forme d'une ligne de
    //      tableau.
    // Variables globales : -
    //=====================================================================
    function queryRow($query, $onlyAsso = false) {
        // suppression de l'ancien r&eacute;sultat
        //unset($this->data);
        $this->data = "";

        // definition de la maniere dont le resultat de la requete doit etre renvoye
        if ($onlyAsso) {
            $mode = 'assoc';
        } else {
            $mode = 'both';
        }

         if ($this->query($query) and $this->rows > 0) {

            if ($mode == 'assoc') {

                // PDO fetch_assoc equivalence mysql_fetch_assoc
                $tab_result = $this->result->fetch(PDO::FETCH_ASSOC);
            } elseif ($mode == 'both') {

                //  PDO fetch BOTH by default
                $tab_result = $this->result->fetch();
            }


            $this->data = $tab_result;
        }

        // renvoie du r&eacute;sultat
        return($this->data);
    }

    //=====================================================================
    // Description : permet d'ex&eacute;cuter une requète devant renvoyer plusieurs
    //  lignes de r&eacute;sultat.
    // Statut : PUBLIC
    // Entr&eacute;es :
    //  - $query : la requète à ex&eacute;cuter
    //  - $onlyAsso : true : ne recupere que les enregistrement avec le nom de champs
    //                false (defaut) : recupere le nom du champ et sont numero
    // Sorties :
    //  - $result : le r&eacute;sultat de la requète sous forme d'un tableau.
    // Variables globales : -
    //=====================================================================
    function queryTab($query, $onlyAsso = false) {
        // suppression de l'ancien r&eacute;sultat
        $this->data = array();

        // definition de la maniere dont le resultat de la requete doit etre renvoye
        if ($onlyAsso) {
            $mode = 'assoc';
        } else {
            $mode = 'both';
        }

        // execution de la requete
        if ($this->query($query) and $this->rows > 0) {
            
            // recuperation du r&eacute;sultat
            for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne++) {
                
                if ($mode == 'assoc') {

                // PDO fetch_assoc equivalence mysql_fetch_assoc
                $tab_result = $this->result->fetch(PDO::FETCH_ASSOC);
            } elseif ($mode == 'both') {

                //  PDO fetch BOTH by default
                $tab_result = $this->result->fetch();
            }


            $this->data[] = $tab_result;
                
            }
            
        }

        // renvoie du r&eacute;sultat
        return($this->data);
    }

    //=====================================================================
    // Description : permet de recuperer la liste des colonnes d'une table.
    // Statut : PUBLIC
    // Entr&eacute;es :
    //  - $tableName = nom de la table
    // Sorties :
    //  - $liste : le tableau contenant le nom des colonnes
    // Variables globales : -
    //=====================================================================
    function getFieldsName($table) {
        $pdo = self::getConnexion();
        $recordset = $pdo->query("SHOW COLUMNS FROM $table");
        $fields = $recordset->fetchAll(PDO::FETCH_ASSOC);
        foreach ($fields as $field) {
            $fieldNames[] = $field['Field'];
        }
        return $fieldNames;
    }

    //=====================================================================
    // Description : permet de fixer à true/false l'affichage du temps
    //  d'ex&eacute;cution des requètes SQL.
    // Statut : PUBLIC
    // Entr&eacute;es :
    //  - $etat = true/false;
    // Sorties : -
    // Variables globales : -
    //=====================================================================
    function setDebugTime($etat) {
        $this->debug_time = $etat;
    }

    //=====================================================================
    // Description :
    // Statut :
    // Entr&eacute;es :
    // Sorties :
    // Variables globales :
    //=====================================================================
    function afficheResQuery() {
        // recuperation du r&eacute;sultat
        echo $this->getHeader("R&eacute;sultat d'une requète", "#9999CC");

        if (is_array($this->data)) {
            reset($this->data);
            for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne++) {
                if (is_array($this->data[$num_ligne])) {
                    echo "<TR><TD BGCOLOR=\"#FFFFFF\" ALIGN=\"center\" COLSPAN=\"2\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\"><B>ligne " . intval($num_ligne + 1) . "</B></FONT></TD></TR>";
                    while (list($key, $val) = each($this->data[$num_ligne])) {
                        echo "<TR>
                                    <TD BGCOLOR=\"#CCCCFF\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>$key</B></FONT></TD>
                                    <TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#000000\" SIZE=\"2\"><B>$val&nbsp;</B></FONT></TD>
                                  </TR>";
                    }
                } else {
                    while (list($key, $val) = each($this->data)) {
                        echo "<TR>
                                    <TD BGCOLOR=\"#CCCCFF\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>$key</B></FONT></TD>
                                    <TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#000000\" SIZE=\"2\"><B>$val&nbsp;</B></FONT></TD>
                                  </TR>";
                    }
                }
            }
        } elseif ($this->rows > 0) {
            echo "<TR>
                        <TD BGCOLOR=\"#CCCCFF\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>valeur</B></FONT></TD>
                        <TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#000000\" SIZE=\"2\"><B>" . $this->data . "&nbsp;</B></FONT></TD>
                      </TR>";
        } else {
            echo "<TR><TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\" COLSPAN=\"2\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#000000\" SIZE=\"2\"><B>aucun r&eacute;sultat</B></FONT></TD></TR>";
        }

        echo $this->getFooter();
    }

    //=====================================================================
    // Description : permet de fermer la connexion avec la base MySQL
    // Statut : PUBLIC
    // Entr&eacute;es : -
    // Sorties : -
    // Variables globales : -
    //=====================================================================
    function close() {
        if($pdo){
            $pdo = null;
        }
    }

    //=====================================================================
    // Description : permet de recuperer l'id du dernier objet ins&eacute;r&eacute; dans
    //  la base, si la requete est de type INSERT
    // Statut : PUBLIC
    // Entr&eacute;es :
    //  - $query : la requète
    // Sorties : -
    // Variables globales : -
    //=====================================================================
    function getLastOid($query) {
        $pdo = self::getConnexion();
        // on met la requete en minuscule sur une ligne
        $query = preg_replace("/[\s\n\r]+/", " ", trim($query));

        // on recupere le nom de la table dans le cas d'un INSERT
        if (preg_match("/^INSERT\s+INTO\s+([^\s]+)\s+.*/i", $query, $matches)) {
            // on recupere le dernier enregistrement
            $this->last_insert_id = $pdo -> lastInsertId();
        } else {
            unset($this->last_insert_id);
        }
    }

    /*     * ******************************************************************** */
    /*     * ********************** FUNCTIONS PRIVEES *************************** */
    /*     * ******************************************************************** */

    //=====================================================================
    // Description : permet d'afficher le haut du tableau servant à afficher
    //  les informations (erreur, r&eacute;sultat d'une requète...).
    // Statut : PRIVE
    // Entr&eacute;es :
    //  - $titre : le titre du tableau
    //  - $bgcolor (#B24609) : la couleur de fond du titre (optionnel)
    // Sorties : -
    // Variables globales : -
    //=====================================================================
    function getHeader($titre, $bgcolor = "#B24609") {
        return "<BR><TABLE BORDER=\"1\" CELLSPACING=\"1\" CELLPADDING=\"4\">
                        <TR BGCOLOR=\"$bgcolor\">
                            <TD ALIGN=\"center\" COLSPAN=\"2\">
                               <FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#DD0000\" SIZE=\"1\"><B>DBCLASS : </B></FONT>
                               <FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFFFF\" SIZE=\"1\"><B>$titre</B></FONT>
                            </TD>
                        </TR>";
    }

    //=====================================================================
    // Description : permet d'afficher le bas du tableau servant à afficher
    //  les informations (erreur, r&eacute;sultat d'une requète...).
    // Statut : PRIVEE
    // Entr&eacute;es : -
    // Sorties : -
    // Variables globales : -
    //=====================================================================
    /*function getFooter() {
        return "</TABLE><BR>";
    }*/

    //=====================================================================
    // Description :
    // Statut : PRIVEE
    // Entr&eacute;es :
    // Sorties :
    // Variables globales :
    //=====================================================================
    function initTimeQuery() {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $this->time_deb = $mtime[1] + $mtime[0];
    }

    //=====================================================================
    // Description :
    // Statut : PRIVEE
    // Entr&eacute;es :
    // Sorties :
    // Variables globales :
    //=====================================================================
    function displayTimeQuery($query) {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $this->time_fin = $mtime[1] + $mtime[0];

        $duree = $this->time_fin - $this->time_deb;

        echo $this->getHeader("Information sur une requète", "#9999CC");
        echo "<TR>
                    <TD BGCOLOR=\"#CCCCFF\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>Requète</B></FONT></TD>
                    <TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#000000\" SIZE=\"2\"><B>$query</B></FONT></TD>
                  </TR>
                  <TR>
                    <TD BGCOLOR=\"#CCCCFF\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>Temps d'ex&eacute;cution</B></FONT></TD>
                    <TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#000000\" SIZE=\"2\"><B>$duree sec</B></FONT></TD>
                  </TR>";
        //echo $this->getFooter();
    }

    //=====================================================================
    // Description : permet d'afficher un message d'erreur MySQL
    // Statut : PRIVEE
    // Entr&eacute;es :
    //  - $msg : le message d'erreur
    // Sorties : -
    // Variables globales : -
    //=====================================================================
    function error($msg) {
        $err = $this->getHeader("ERREUR MySQL");
        $err = "<TR>
                    <TD BGCOLOR=\"#D16D31\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>Erreur</B></FONT></TD>
                    <TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\"><B>" . nl2br($msg) . "</B></FONT></TD>
                  </TR>
                  <TR>
                    <TD BGCOLOR=\"#D16D31\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>N° erreur MySQL</B></FONT></TD>
                    <TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\">" . @mysql_errno() . "&nbsp;</FONT></TD>
                  </TR>
                  <TR>
                    <TD BGCOLOR=\"#D16D31\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>Message MySQL</B></FONT></TD>
                    <TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\">" . @mysql_error() . "&nbsp;</FONT></TD>
                  </TR>";
        echo $this->getFooter();
    }

    //=====================================================================
    // Description : permet de retourner le nombre de lignes d'une table
    // Statut : PRIVEE
    // Entr&eacute;es :
    //  - $table : le message d'erreur
    // Sorties : -
    // Variables globales : -
    //=====================================================================
    function QueryCount($table,$field) {
        $pdo = self::getConnexion();
        $result = $pdo->query("SELECT count(".$field.") FROM $table");
        $result = $result->fetch();
        return $result;
    }

      } // fin de la declaration de la classe
}