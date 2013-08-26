<?php

/** Include helpers database file */
require_once ICEBERG_DIR_HELPERS . 'database.php';

/**
 * MySQL Base
 * 
 * Basics for MySQL Object
 *  
 * @package MySQL
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 0
 */
class MySQLBase {

    /**
     * Constructor
     */
    public function __construct() {
        global $__MYSQL_ERROR_DEBUG_SHOW, $__MYSQL_ERROR_DEBUG_LOG, $__MYSQL_ERROR_DEBUG_LOG_FILE;
        ob_start();
    }

    /**
     * Get debuf info.
     * @return String 
     */
    private static function getDebugInfo() {
        $debugInfoArray = debug_backtrace();
        $i = 0;
        while((isset($debugInfoArray[$i]["file"])) && (realPath($debugInfoArray[$i]["file"]) != realPath($_SERVER["SCRIPT_FILENAME"])) && ($i<sizeOf($debugInfoArray))) $i++;
        return $debugInfoArray[$i];
    }
    
    /**
     * Report error
     * @global Boolean $__MYSQL_ERROR_DEBUG_SHOW
     * @global Boolean $__MYSQL_ERROR_DEBUG_LOG
     * @global String $__MYSQL_ERROR_DEBUG_LOG_FILE
     * @param String $msg
     * @param Int $type
     * @param Mixed $admin
     * @param String $headers 
     */
    public static function reportError ($msg='', $type=0, $admin='', $headers='')
    {
        global $__MYSQL_ERROR_DEBUG_SHOW, $__MYSQL_ERROR_DEBUG_LOG, $__MYSQL_ERROR_DEBUG_LOG_FILE;
        $errno=-1;
        if(!$msg) {
            $msg=mysql_error();
            $errno=mysql_errno();
        }
        $debugInfo = MySQL::getDebugInfo();
        //$msg = sprintf("\nFile: %s\n<br>Line: %d\n<br>Called function: %s\n<br>Contained class: %s\n<br>Passed arguments: %s\n<br>Reported error: %s<br>", $debugInfo["file"], $debugInfo["line"], $debugInfo["function"], $debugInfo["class"], implode(", ", $debugInfo["args"]), $msg . ($errno==-1 ? "" : " (" . $errno . ")"));
        switch($type) {
            case "0":
                if($__MYSQL_ERROR_DEBUG_LOG) {
                    $msg = sprintf("MySQL ERROR: %s\n<br><br>\nReported by: http://%s%s\n<br><br>Refering page: %s<br><br>", $msg, $_SERVER["HTTP_HOST"], $_SERVER["REQUEST_URI"], (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : ''));
                    error_log($msg, 0);
                }
            break;
            case "1":
                if($__MYSQL_ERROR_DEBUG_LOG) {
                    if ($headers==='') { $headers=sprintf("From: %s", $_SERVER["HTTP_HOSTS"]); }
                    $msg = sprintf("MySQL ERROR: %s\n<br><br>\nReported by: http://%s%s\n<br><br>Refering page: %s<br><br>", $msg, $_SERVER["HTTP_HOST"], $_SERVER["REQUEST_URI"], (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : ''));
                    error_log($msg, 1, $admin, $headers);
                }
            break;
            case "3":
                if($__MYSQL_ERROR_DEBUG_LOG)
                {
                    $stamp = date("[d/M/Y:G:i:s]");
                    $msg = sprintf("%s %s (%s%s)\r\n\r\n", $stamp, $msg, $_SERVER["HTTP_HOST"], $_SERVER["REQUEST_URI"]);
                    error_log($msg, 3, $__MYSQL_ERROR_DEBUG_LOG_FILE);
                }
            break;
            default:
                die("\n".'MYSQL ERROR: wrong call to reportError()');
        }
        if ($__MYSQL_ERROR_DEBUG_SHOW || true) {
            //ob_clean();
            echo $msg;
            exit;
        }
    }

    /**
     * Add new MySQL log
     * @global array $__MYSQL_QUERY_DEBUG
     * @param String $SQL
     * @param Int $numrows
     * @param Timestamp $time 
     * @return Boolean 
     */
    public static function Log($SQL, $numrows, $time)
    {
        global $__MYSQL_QUERY_DEBUG;
        if (!is_array($__MYSQL_QUERY_DEBUG)) {$__MYSQL_QUERY_DEBUG = array();}
        return array_push($__MYSQL_QUERY_DEBUG, array($SQL, $numrows, $time));
    }
    
    /**
     * Get MySQL log
     * @global Array $__MYSQL_QUERY_DEBUG
     * @return Array 
     */
    public static function GetLog()
    {
        global $__MYSQL_QUERY_DEBUG;
        if (!is_array($__MYSQL_QUERY_DEBUG)) {$__MYSQL_QUERY_DEBUG = array();}
        return $__MYSQL_QUERY_DEBUG;
    }
    
    /**
     * Print MySQL log 
     */
    public static function PrintLog()
    {
        $log = static::GetLog();
        $time_total = 0;
        $buffer = '';
        $n = count($log);
        foreach ($log AS $query)
        {
            $time_total += $query[2];
            $buffer .= 'Query time: ' . $query[2] . " seconds\n";
            $buffer .= 'Query results: ' . $query[1] . "\n";
            $buffer .= 'Query: ' . $query[0] . "\n\n";
        }
        $average = $time_total / $n;
        $buffer = "\n<!--\n\n MySQL time: " . $time_total . " seconds\n MySQL time average: " . $average . " seconds\n MySQL queries: " . $n . "\n\n\n" . $buffer . " -->";
        print $buffer;
    }
}

/**
 * MySQL
 * 
 * MySQL Object
 *  
 * @package MySQL
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 1.0
 */
class MySQL extends MySQLBase{
    
    /**
     * Connection ID
     * @var int
     */
    private $id = -1;
    
    /**
     * Connection object
     * @var object 
     */
    private $link = null;
    
    /**
     * Data base
     * @var string
     */
    private $db = null;
    
    /**
     * Error type
     * @var int
     */
    private $error_type = 0;
    
    /**
     * Admin
     * @var string
     */
    private $admin = '';
    
    /**
     * Error headers
     * @var string
     */
    private $error_headers = '';
    
    /**
     * List of collates
     * @var array
     */
    private static $collates = array(
        'utf8_general_ci' => 'UTF-8 Unicode',
        'latin2_general_ci' => 'ISO 8859-2 Central European',
        'big5_chinese_ci' => 'Big5 Traditional Chinese',
        'dec8_swedish_ci' => 'DEC West European',
        'cp850_general_ci' => 'DOS West European',
        'hp8_english_ci' => 'HP West European',
        'koi8r_general_ci' => 'KOI8-R Relcom Russian',
        'latin1_swedish_ci' => 'cp1252 West European',
        'swe7_swedish_ci' => '7bit Swedish',
        'ascii_general_ci' => 'US ASCII',
        'ujis_japanese_ci' => 'EUC-JP Japanese',
        'sjis_japanese_ci' => 'Shift-JIS Japanese',
        'hebrew_general_ci' => 'ISO 8859-8 Hebrew',
        'tis620_thai_ci' => 'TIS620 Thai',
        'euckr_korean_ci' => 'EUC-KR Korean',
        'koi8u_general_ci' => 'KOI8-U Ukrainian',
        'gb2312_chinese_ci' => 'GB2312 Simplified Chinese',
        'greek_general_ci' => 'ISO 8859-7 Greek',
        'cp1250_general_ci' => 'Windows Central European',
        'gbk_chinese_ci' => 'GBK Simplified Chinese',
        'latin5_turkish_ci' => 'ISO 8859-9 Turkish',
        'armscii8_general_ci' => 'ARMSCII-8 Armenian',
        'ucs2_general_ci' => 'UCS-2 Unicode',
        'cp866_general_ci' => 'DOS Russian',
        'keybcs2_general_ci' => 'DOS Kamenicky Czech-Slovak',
        'macce_general_ci' => 'Mac Central European',
        'macroman_general_ci' => 'Mac West European',
        'cp852_general_ci' => 'DOS Central European',
        'latin7_general_ci' => 'ISO 8859-13 Baltic',
        'cp1251_general_ci' => 'Windows Cyrillic',
        'utf16_general_ci' => 'UTF-16 Unicode',
        'cp1256_general_ci' => 'Windows Arabic',
        'cp1257_general_ci' => 'Windows Baltic',
        'utf32_general_ci' => 'UTF-32 Unicode',
        'binary' => 'Binary pseudo charset',
        'geostd8_general_ci' => 'GEOSTD8 Georgian',
        'cp932_japanese_ci' => 'SJIS for Windows Japanese',
        'eucjpms_japanese_ci' => 'UJIS for Windows Japanese',
    );

    /**
     * Contructor
     * 
     * @global object $__MYSQL_LINK
     * @param bool $new [Optional]
     * @param int $id [Optional]
     */
    public function MySQL($new=true, $id=-1) {
        global $__MYSQL_LINK;
        if ($new) {
            $this->id=count($__MYSQL_LINK);
            $__MYSQL_LINK[$this->id]=null;
        }
        else {
            if ($n!==-1 && array_key_exists($id,$__MYSQL_LINK)) {
                $this->id=$id;
            }
            else if ($n===-1 AND count($__MYSQL_LINK)>0) {
                $keys=array_keys($__MYSQL_LINK);
                $this->id=$keys[0];
            }
            else {
                MySQLBase::reportError('ERROR DATABASE CONNECT SELECTION', $this->error_type, $this->admin, $this->error_headers);
            }
        }
    }

    /**
     * Connect to database
     * 
     * @global object $__MYSQL_LINK
     * @global array $__MYSQL_CONFIG
     * @param string $db Database name
     * @param string $host Host name
     * @param string $user User
     * @param string $pass Password
     * @param string $charset [Optional] Charset
     * @param string $collate [Optional] Collate
     * @return int Connection ID
     */
    public function Connect($db, $host, $user, $pass, $charset='utf8', $collate='utf8_general_ci') {
        global $__MYSQL_LINK, $__MYSQL_CONFIG;
        $this->link = @mysql_connect($host, $user, $pass) or MySQLBase::reportError('ERROR DATABASE CONNECTION', $this->error_type, $this->admin, $this->error_headers);
        if($db) {
            if(!@mysql_select_db($db)) { MySQLBase::reportError('ERROR DATABASE SELECTION', $this->error_type, $this->admin, $this->error_headers); }
            $this->db = $db;
        }
        $__MYSQL_LINK[$this->id] = $this->link;
        $__MYSQL_CONFIG[$this->id] = array('charset'=>$charset, 'collate'=>$collate);
        return $this->id;
    }
    
    /*public function close() {
        $value = @mysql_close($this->link) or MySQLBase::reportError('ERROR DATABASE CLOSE CONNECTION', $this->error_type, $this->admin, $this->error_headers);
        return $value;
    }*/

    /**
     * Connect to all databases
     * 
     * @global array $__ICEBERG_DB
     * @global Query $__MYSQL_QUERY
     * @param bool $force [Optional] Force connection. If isn't possible throws an error
     * @param array $dbs [Optional] List of databases
     * @throws IcebergException If force and list is empty
     */
    public static function ConnectAll($force=false, $dbs=array()) {
        global $__ICEBERG_DB, $__MYSQL_QUERY;
        $__ICEBERG_DB = !empty($dbs) ? $dbs : $__ICEBERG_DB;
        if (isset($__ICEBERG_DB) && is_array($__ICEBERG_DB) && !empty($__ICEBERG_DB)) {
            foreach ($__ICEBERG_DB AS $key=>$value) {
                $mysql=new MySQL();
                $mysql->Connect($value['dbname'], $value['host'], $value['user'], $value['password'], $value['charset'], $value['collate']);
            }
            if (self::Connected())
            {
                $__MYSQL_QUERY = new Query();
            }
        }
        else if ($force) {
            throw new IcebergException('ERROR DATABASE CONNECTION');
        }
    }

    /**
     * Get charset of connection or list of connections
     * 
     * @global array $__MYSQL_CONFIG
     * @param int $id [Optional] Connection ID
     * @return array/string Charset of connection or charset list of connections
     */
    public static function GetCharset($id=null) {
        global $__MYSQL_CONFIG;
        $id = MySQL::validIdConnection($id);
        if (is_array($id)) {
            $return=array();
            foreach ($id AS $key=>$value) { $return[$key]=$__MYSQL_CONFIG[$value]['charset']; }
            return $return;
        }
        else { return $__MYSQL_CONFIG[$id]['charset']; }
    }

    /**
     * Get collate of connection or list of connections
     * 
     * @global array $__MYSQL_CONFIG
     * @param int $id [Optional] Connection ID
     * @return array/string Collate of connection or collate list of connections
     */
    public static function GetCollate($id=null) {
        global $__MYSQL_CONFIG;
        $id = MySQL::validIdConnection($id);
        if (is_array($id)) {
            $return=array();
            foreach ($id AS $key=>$value) { $return[$key]=$__MYSQL_CONFIG[$value]['collate']; }
            return $return;
        }
        else { return $__MYSQL_CONFIG[$id]['collate']; }
    }

    /**
     * Checks if table exists on database
     * 
     * @param string $table Table name
     * @param int $id [Optional] Connection ID
     * @return boolean Table exists
     */
    public static function TableExists($table, $id=null) {
        $id = MySQL::ValidIdConnection($id);
        $query = new Query("SHOW TABLES LIKE '" . mysql_escape($table) . "'", $id);
        $numrows = $query->numrows($id);
        if (is_array($numrows)) {
            if (!empty($numrows)) {
                $return = true;
                foreach ($numrows AS $value) {
                    if ($value==0) { $return = false; break; }
                }
                return $return;
            }
            else {
                return false;
            }
        }
        else {
            return $numrows>0 ? true : false;
        }
    }

    /**
     * Returns valid connections to database
     * 
     * @global object $__MYSQL_LINK
     * @param int $id [Optional] Connection ID
     * @return int Connection ID
     */
    public static function ValidIdConnection($id=null) {
        global $__MYSQL_LINK;
        if (is_null($id) && count($__MYSQL_LINK)>0) {
            $keys=array_keys($__MYSQL_LINK);
            return $keys[0];
        }
        else if (is_numeric($id) && array_key_exists($id,$__MYSQL_LINK)) {
            return $id;
        }
        else if ($id===MYSQL_QUERY_ALL_CONNECTIONS) {
            return ARRAY_KEYS($__MYSQL_LINK);
        }
        else if (is_array($id)) {
            foreach ($id As $key=>$value) {
                if (!is_numeric($value) || !array_key_exists($value,$__MYSQL_LINK)) {
                    MySQLBase::reportError('ERROR DATABASE QUERY CONNECTION SELECTION');
                }
            }
            return $id;
        }
        else { MySQLBase::reportError('ERROR DATABASE QUERY CONNECTION SELECTION'); }
    }

    /**
     * Get list of collates
     * 
     * @return array List of collates
     */
    public static function GetCollates() {
        return MySQL::$collates;
    }

    /**
     * Check if is connected to database
     * @global object $__MYSQL_LINK
     * @return bool If is connected
     */
    public static function Connected() {
        global $__MYSQL_LINK;
        return count($__MYSQL_LINK)>0 ? true : false;
    }
    
    public static function Dump($args = array())
    {
        $defaults = array(
            'drop_table' => true,
            'create_table' => true,
            'table_data' => true
        );
        $args = array_merge($defaults, $args);
        ob_start();
        echo '/* ICEBERG ' . ICEBERG_VERSION . ' */' . "\n";
        echo '/* Domain: ' . get_domain_canonical() . ' */' . "\n";
        echo '/* Time: ' . time() . ' */' . "\n";
        echo "\n\n";
        
        $query = new Query();
        $query->show_tables();
        while ($row = $query->next(MYSQL_ROW_AS_ARRAY))
        {
            $table = current($row);
            static::DumpTableStructure($table, $args);
            static::DumpTableData($table, $args);
        }
        $buffer = ob_get_flush();
        ob_end_clean();
        return $buffer;
    }
    
    public static function DumpTableStructure($table, $args = array())
    {
        echo '/* Table structure for table: ' . $table . ' */' . "\n";
        if (isset($args['drop_table']) && $args['drop_table'])
        {
            echo "DROP TABLE IF EXISTS `$table`;\n\n";
        }
        if (isset($args['create_table']) && $args['create_table'])
        {
            $query = new Query();
            $query->show_create_tables($table);
            if ($query->numrows())
            {
                $row = $query->next(MYSQL_ROW_AS_ARRAY);
                $create = $row['Create Table'];
                echo $create . ";\n\n";
            }
        } 
    }
    
    public static function DumpTableData($table, $args = array())
    {
        echo '/* Table data for table: ' . $table . ' */' . "\n";
        if (isset($args['table_data']) && $args['table_data'])
        {
            $query = new Query();
            $query->select($table);
            $num_rows = $query->numrows();
            $num_fields = $query->numfields();
            
            if ($num_rows > 0)
            {
                $field_type = array();
                for ($i=0; $i < $num_fields; $i++)
                {
                    $meta = $query->field($i);
                    array_push($field_type, $meta->type);
                }
                echo "INSERT INTO `$table` VALUES\n";
                $index = 0;
                while($row = $query->next(MYSQL_ROW_AS_ARRAY))
                {
                    echo "(";
                    for ($i=0; $i < $num_fields; $i++)
                    {
                        if(is_null( $row[$i]))
                        {
                            echo "null";
                        }
                        else
                        {
                            switch( $field_type[$i])
                            {
                                case 'int':
                                    echo $row[$i];
                                    break;
                                case 'string':
                                case 'blob' :
                                default:
                                    echo "'".mysql_escape($row[$i])."'";
                                    break;
                            }
                        }
                        if($i < $num_fields-1)
                        {
                            echo ",";
                        }
                    }
                    echo ")";
                    if( $index < $num_rows-1)
                    {
                        echo ",";
                    }
                    else
                    {
                        echo ";";
                    }
                    echo "\n";
                    $index++;
                }
                
            }
        }
        echo "\n\n";
    }
}

/**
 * Query
 * 
 * MySQL query object
 *  
 * @package MySQL
 * @subpackage Query
 * @author Marc Mascort Bou
 * @version 1.0
 * @since 0
 */
class Query extends MySQLBase {
    
    /**
     * Connection link ID
     * @var int 
     */
    private $id=-1;
    private $result=array();
    private $numrows=array();
    private $insertId=array();

    public function Query($query=null, $id=null) {
        global $__MYSQL_LINK, $__MYSQL_ROW_METHOD;
        $this->validIdConnection($id);
        if (is_null($query)) { return $this; }
        else if (is_string($query)) { return $this->doQuery($query, $this->id); }
        else { return false; }
    }

    public function select($table, $select='*', $where='', $orderby='', $limit='', $id=null) {
        $this->validIdConnection($id);
        $sql = 'SELECT ' . mysql_escape($select) . ' FROM ' . $table . ' ' . $where;
        $sql .= $orderby!=='' ? ' ORDER BY ' . mysql_escape($orderby) : '';
        $sql .= $limit!=='' ? ' LIMIT ' . mysql_escape($limit) : '';
        return $this->doQuery($sql, $this->id);
    }

    public function update($table, $update, $where='', $orderby='', $limit='') {
        $this->validIdConnection( MYSQL_QUERY_ALL_CONNECTIONS );
        $set = array();
        foreach ($update AS $key => $value) {
            if (!is_null($value)) {
                array_push($set, $key." = '" . mysql_escape($value) . "'");
            }
            else {
                array_push($set, $key." = NULL ");
            }
        }
        $sql = 'UPDATE ' . mysql_escape($table) . ' SET ' . implode(' , ', $set). ' ' . $where;
        $sql .= $orderby!=='' ? ' ORDER BY ' . mysql_escape($orderby) : '';
        $sql .= $limit!=='' ? ' LIMIT ' . mysql_escape($limit) : '';
        $done = false;
        foreach ($this->id as $key => $value) {
            $this->doQuery($sql, $value);
            $done = $this->done($value);
            if (!$done) { break; }
        }
        return $done;
    }

    public function create_table($table, $fields, $index=array()) {
        $this->validIdConnection( MYSQL_QUERY_ALL_CONNECTIONS );
        $sql = 'CREATE TABLE `' . mysql_escape($table) . '` (' . implode(',', $fields);
        $sql .= !empty($index) ? ', INDEX(`' . implode('`), INDEX(`', $index) . '`)' : '';
        $sql .= ')';
        $done = false;
        foreach ($this->id as $key => $value) {
            $b_sql = $sql . ' ENGINE = MYISAM CHARACTER SET ' . mysql_escape( MySQL::GetCharset($value) ) . ' COLLATE ' . mysql_escape( MySQL::GetCollate($value) ) . '';
            $this->doQuery($b_sql, $value);
            $done = $this->done($value);
            if (!$done) { break; }
        }
        return $done;
    }

    public function drop_table($table) {
        $this->validIdConnection( MYSQL_QUERY_ALL_CONNECTIONS );
        $sql = 'DROP TABLE IF EXISTS `' . mysql_escape($table) . '`';
        $done = false;
        foreach ($this->id as $key => $value) {
            $this->doQuery($sql, $value);
            $done = $this->done($value);
            if (!$done) { break; }
        }
        return $done;
    }

    public function insert($table, $fields, $values) {
        $this->validIdConnection( MYSQL_QUERY_ALL_CONNECTIONS );
        foreach ($fields AS $key => $value) { $fields[$key] = mysql_escape( $value ); }
        foreach ($values AS $key => $value) { $values[$key] = mysql_escape( $value ); }
        $sql = "INSERT INTO `" . mysql_escape($table) . "` (`" . implode("`,`", $fields) . "`) VALUES ('" . implode("','", $values) . "')";
        $done = false;
        foreach ($this->id as $key => $value) {
            $this->doQuery($sql, $value);
            $done = $this->done($value);
            if (!$done) { break; }
        }
        return $done;
    }

    public function delete($table, $where, $apply_table = '') {
        $this->validIdConnection( MYSQL_QUERY_ALL_CONNECTIONS );
        $apply_table = empty($apply_table) ? '' : '' . mysql_escape($apply_table) . '';
        $sql = "DELETE " . $apply_table . " FROM " . mysql_escape($table) . " " . $where;
        $done = false;
        foreach ($this->id as $key => $value) {
            $this->doQuery($sql, $value);
            $done = $this->done($value);
            if (!$done) { break; }
        }
        return $done;
    }
    
    public function show_tables($id=null)
    {
        $this->validIdConnection($id);
        $sql = 'SHOW TABLES';
        return $this->doQuery($sql, $this->id);
    }
    
    public function show_create_tables($table, $id=null)
    {
        $this->validIdConnection($id);
        $sql = 'SHOW CREATE TABLE ' . $table;
        return $this->doQuery($sql, $this->id);
    }

    public function getInsertId($id=null) {
        $this->validIdConnection($id);
        if (is_array($this->id)) {
            $return=array();
            foreach($this->id AS $key=>$value) { $return[$value]=$this->insertId[$value]; }
            return $return;
        }
        else {
            return $this->insertId[$this->id];
        }
    }

    public function numrows($id=null) {
        $this->validIdConnection($id);
        if (is_array($this->id)) {
            $return=array();
            foreach($this->id AS $key=>$value) {
                if(!($num = @mysql_num_rows($this->result[$this->id]))) { $return[$value]=0; }
                else { $return[$value]=$num; }
                //$return[$value]=$this->numrows[$value];
            }
            return $return;
        }
        else {
            if(!($num = @mysql_num_rows($this->result[$this->id]))) { return 0; }
            else { return $num; }
            //return $this->numrows[$this->id];
        }
    }

    public function numfields($id=null) {
        $this->validIdConnection($id);
        if (is_array($this->id)) {
            $return=array();
            foreach($this->id AS $key=>$value) {
                if(!($num = @mysql_num_fields($this->result[$this->id]))) { $return[$value]=0; }
                else { $return[$value]=$num; }
                //$return[$value]=$this->numrows[$value];
            }
            return $return;
        }
        else {
            if(!($num = @mysql_num_fields($this->result[$this->id]))) { return 0; }
            else { return $num; }
            //return $this->numrows[$this->id];
        }
    }
    
    public function done($id=null) {
        $this->validIdConnection($id);
        if (is_array($this->id)) {
            $return=1;
            foreach($this->id AS $key=>$value) { if ($this->result[$value]!=1) { $return=0; } }
            return $return;
        }
        else {
            return $this->result[$this->id];
        }
    }

    public function next($method=null,$id=null) {
        global $__MYSQL_ROW_METHOD;
        if(is_null($method)) { $method = $__MYSQL_ROW_METHOD; }
        if($method == MYSQL_ROW_AS_OBJECT) { return $this->fetchObjectRow($id); }
        elseif($method == MYSQL_ROW_AS_ARRAY) { return $this->fetchArrayRow($id); }
    }
    
    public function field($n=0, $id=null)
    {
        $this->validIdConnection($id);
        if (is_array($this->id)) {
            $return=array();
            foreach($this->id AS $key=>$value) {
                if(!($row = @mysql_fetch_field($this->result[$value], $n))) { $return[$value]=false; }
                else { $return[$value]=$row; }
            }
            return $return;
        }
        else {
            if(!($row = @mysql_fetch_field($this->result[$this->id], $n))) { return false; }
            else { return $row; }
        }
    }

    public function free() {
        global $__MYSQL_LINK;
        foreach ($__MYSQL_LINK AS $key=>$value) {
            @mysql_free_result($this->result) or  MySQLBase::reportError('ERROR MYSQL FREE RESULT');
        }
    }

    public function reset() {
        global $__MYSQL_LINK;
        foreach ($__MYSQL_LINK AS $key=>$value) {
            @mysql_data_seek($this->result) or  MySQLBase::reportError('ERROR MYSQL DATA SEEK');
        }
    }



    private function validIdConnection($id=null) {
        $this->id = MySQL::ValidIdConnection($id);
    }

    private function fetchObjectRow($id=null) {
        $this->validIdConnection($id);
        if (is_array($this->id)) {
            $return=array();
            foreach($this->id AS $key=>$value) {
                if(!($row = @mysql_fetch_object($this->result[$value]))) { $return[$value]=false; }
                else { $return[$value]=$row; }
            }
            return $return;
        }
        else {
            if(!($row = @mysql_fetch_object($this->result[$this->id]))) { return false; }
            else { return $row; }
        }
    }

    private function fetchArrayRow($id=null) {
        $this->validIdConnection($id);
        if (is_array($this->id)) {
            $return=array();
            foreach($this->id AS $key=>$value) {
                if(!($row = @mysql_fetch_array($this->result[$value]))) { $return[$value]=false; }
                else { $return[$value]=$row; }
            }
            return $return;
        }
        else {
            if(!($row = @mysql_fetch_array($this->result[$this->id]))) { return false; }
            else { return $row; }
        }
    }

    private function doQuery($query, $id=null) {
        global $__MYSQL_LINK;
        $this->insertId=array();
        $this->numrows=array();
        $this->result=array();
        if (is_array($this->id)) {
           foreach ($this->id AS $key=>$value) {
               $response=$this->sendQuery($query,$__MYSQL_LINK[$value]);
               $this->insertId[$value]=$response[0];
               $this->numrows[$value]=$response[1];
               $this->result[$value]=$response[2];
           }
           return $this->result;
        }
        else {
            $response=$this->sendQuery($query,$__MYSQL_LINK[$this->id]);
            $this->insertId[$this->id]=$response[0];
            $this->numrows[$this->id]=$response[1];
            $this->result[$this->id]=$response[2];
            return $this->result[$this->id];
        }
    }

    private function sendQuery($query, $link) {
        $insertId=0;
        $numrows=0;
        $time_start = microtime(true);
        //echo $query ."<br /><br />\n\n";
        $result = @mysql_query($query, $link);
        if($result) {
            if(preg_match("/^insert/", strtolower($query))) {
                $insertId = @mysql_insert_id();
            }
            else if(preg_match("/^select/", strtolower($query))) {
                $numrows = @mysql_num_rows($result);
            }
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            MySQL::Log($query, $numrows, $time);
            return array($insertId, $numrows, $result);
        }
        else { MySQLBase::reportError('ERROR DATABASE QUERY: '.$query); }
    }
}

