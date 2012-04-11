<?php
/*-------------------------------------------- 
* PROJECT NAME - PROJECT MOTTO
* BUILT ON BLOWFIS FRAMEWORK VERSION 2
* -------------------------------------------- 
* COPYRIGHT YEAR AUTHOR
* BLOWFIS COPYRIGHT 2012 COBE MAKAROV
* -------------------------------------------- 
* BLOWFIS FRAMEWORK RELEASED UNDER THE GNU 
* PUBLIC LICENSE V3. COBE MAKAROV IS NOT 
* AFFILIATED WITH THE SERVER(S) RAN WITH ANY 
* WEB APPLICATION BUILT UPON BLOWFIS VERSION 2
* -------------------------------------------- 
* @author: AUTHOR
* @framework-author: Cobe Makarov 
* --------------------------------------------*/

################################################
//Someone is trying to access this file directly!
if (!defined('BLOWFIS'))
{
   exit; 
}

/*
 * author: Cobe Makarov
 * name: MySQLi Class
 * description: A class that parses some MySQLi functions and STMT functions aswell
 */

class blowfisMySQLi implements Database
{
    ################################################
    //The variable that holds the database obj.
    private $_link;
    
    ################################################
    //The count of queries ran per page
    public $_queryCount;
    
    ################################################
    //The database host
    private $_databaseHost;
    
    ################################################
    //The database name
    private $_databaseName;
    
    ################################################
    //The database user
    private $_databaseUser;
    
    ################################################
    //The database password
    private $_databasePassword;
    
    ################################################
    //Did we successfully connect?
    private $_databaseConnected;
    
    ################################################
    //The query that the class is working with
    private $_classQuery;
    
    ################################################
    //The STMT variable
    private $_STMT;
    
    
    public function __construct($database)
    {
        ################################################
        //Set our db values..
        $this->_databaseHost = $database['host'];
        $this->_databaseName = $database['name'];
        $this->_databaseUser = $database['user'];
        $this->_databasePassword = $database['password'];
        
        $this->connect();
    }
    
    private function connect()
    {
        if ($this->_databaseConnected)
        {
            ################################################
            //System forbids this anyways, but...
            return;
        }
               
        ################################################
        //Let's try to connect!
        $this->_link = new MySQLi(
                $this->_databaseHost, 
                $this->_databaseUser, 
                $this->_databasePassword, 
                $this->_databaseName);
        
        //No error handler setup atm, I'll come back and do that..
        if ($this->_link->connect_error)     
        {
            trigger_error($this->_link->connect->errno);  
        }
        
        $this->_databaseConnected = true;
    }
    
    public function disconnect()
    {
        ################################################
        //Close the database connection and tell the system
        //We're done!
        $this->_link->close();
        
        $this->_databaseConnected = false;
    }
    
    public function secure(&$requestedVariable)
    {
        $this->_link->real_escape_string($requestedVariable);
    }
    
    public function prepare($databaseQuery)
    {
        $this->_classQuery = $databaseQuery;
        
        //No error handler setup atm, I'll come back and do that..
        if (!$this->_STMT = $this->_link->prepare($databaseQuery)) 
        { 
            die($this->_STMT->error); 
        } 
         
        return $this; 
    }
    
    public function bindParameters($requestedParameters)
    {
        global $blowfis;
        
        $parameterTypes = '';
        
        foreach($requestedParameters as $key => $value) 
        {          
            $parameterTypes .= $blowfis->getType($value); 
        } 
        
        //Fill our arguments variable with an array of the parameter types
        $arguments = array($parameterTypes); 

        //Make sure we have the correct parameters
        $this->retrieveParams($requestedParameters, $arguments); 

        //Bind the parameters
        call_user_func_array(array($this->_STMT, 'bind_param'), $arguments);
        
        return $this; 
    }
    
    //@credits : Jos Piek
    private function retrieveParams(array &$array, array &$out)
    {
        //Make sure the system is at a usuable version
        if (strnatcmp(phpversion(),'5.3') >= 0) 
        { 
            foreach($array as $key => $value) 
            { 
                $out[] =& $array[$key]; 
            } 
        } 
        else 
        { 
            $out = $array; 
        } 
    }
    
    public function execute()
    {
        if(!$this->_STMT->execute()) 
        { 
            return $this->_STMT->error; 
        } 
        
        $this->_queryCount++;
        
        return new MySQLiResult(null, $this->_STMT); 
    }
}

class MySQLiResult implements ResultObj
{
    ################################################
    //The MySQLi connection variable
    private $_STMT;
    
    ################################################
    //Is our stmt associated yet?
    private $assoc = false;
    
    ################################################
    //If the stmt is an array, this is where the rows are.
    private $rows = array();
    
    public function __construct($queryObject, $connectionVariable)
    {
        $this->_STMT = $connectionVariable; 

        mysqli_stmt_store_result($connectionVariable); 
    }
    
    public function result()
    {
        $this->_STMT->bind_result($_queryResult);
        
        $this->_STMT->fetch();
        
        return $_queryResult;
    }
    
    ################################################
    //credits : Jos Piek
    private function stmt_assoc(&$stmt, array &$out)
    {
        $data = mysqli_stmt_result_metadata($stmt);
        
        $fields = array($this->_STMT);
        
        $out = array();
        
        while ($field = mysqli_fetch_field($data))
        {
            $fields[] =& $out[$field->name];
        }
        
        call_user_func_array('mysqli_stmt_bind_result', $fields);
    }
    
    public function fetch_array()
    {
        if (!$this->assoc)
        {
            $this->assoc = true;
            
            $this->stmt_assoc($this->_STMT, $this->rows);
        }
        
        if (!$this->_STMT->fetch())
        {
            $this->assoc = false;

            $this->rows = array();
        }
        
        $data = array();
        
        foreach ($this->rows as $key => $value)
        {
            $data[$key] = $value;
        }

        return ($this->assoc) ? $data : false;
    }
    
    public function num_rows()
    {
        $this->_STMT->num_rows();
    }
}
?>
