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
 * name: PDO Class
 * description: A class that parses some PDO functions and such
 */

class blowfisPDO implements Database
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
    //The query's parameters.
    private $_classParameters = array();
    
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
        try
        {
            $this->_link = new PDO(
                    'mysql:dbname='.$this->_databaseName.
                    ';host='.$this->_databaseHost, 
                    $this->_databaseUser, 
                    $this->_databasePassword);
        }
        catch(PDOException $e)
        {
            trigger_error($e->getMessage());
        }
        
        $this->_databaseConnected = true;
    }
    
    public function disconnect()
    {
        $this->_link = null;
    }
    
    public function secure(&$requestedVariables)
    {
        stripslashes($requestedVariables);
        htmlentities($requestedVariables);
    }
    
    public function prepare($databaseQuery)
    {
        $this->_classQuery = $databaseQuery;
        
        if (!$this->_STMT = $this->_link->prepare($this->_classQuery))
        {
            //Error handling due later..
            die($this->_STMT->error); 
        }
        
        return $this;
    }
    
    public function bindParameters($requestedParameters)
    {
        if (!is_array($requestedParameters))
        {
            // -.-
        }
        
        $this->_classParameters = $requestedParameters;
        
        return $this;
    }
    
    public function execute()
    {
        if(!$this->_STMT->execute($this->_classParameters)) 
        { 
            return $this->_STMT->error; 
        } 
        
        $this->_queryCount++;
        
        return new PDOResult(null, $this->_STMT); 
    }
}
class PDOResult implements ResultObj
{
    ################################################
    //The PDO connection variable
    private $_STMT;
    
    
    public function __construct($queryObject, $connectionVariable)
    {
        $this->_STMT = $connectionVariable;
    }
    
    public function result()
    {
        return $this->_STMT->fetchColumn();
    }
    
    public function fetch_array()
    {
        return $this->_STMT->fetch(PDO::FETCH_ASSOC);
    }
    
    public function num_rows()
    {
        return $this->_STMT->rowCount();
    }
}
?>
