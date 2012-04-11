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
 * name: "Steve" Jobs Class
 * description: A class that parses all cron jobs within the database and determines when to be ran
 */

class Jobs
{
    ################################################
    //The directory in which jobs are housed!
    private $_jobsDirectory;
    
    public function start()
    {
        global $blowfis;
        
        $this->_jobsDirectory = APPLICATION.LIBRARY.INCLUDES.JOBS;

        $jobs = $blowfis->_database->prepare('SELECT * FROM sulake_jobs WHERE `binary` = ?')
                ->bindParameters(array(1))->execute();
        
        while ($array = $jobs->fetch_array())
        {
            $this->run($array);
        }
    }
    
    private function run($job_array)
    {
        global $blowfis;
        
        $_fileName = 'job.'.$job_array['name'].'.php';
        $_className = ucfirst($job_array['name']);
        
        if (!include($this->_jobsDirectory.$_fileName))
        {
            trigger_error($this->_jobsDirectory.$_fileName.' was not found!');
        }
        
        $cron_class = new $_className();
        
        if (($job_array['last'] + $job_array['interval']) > time())
        {
            return;   
        }
        
        $cron_class->_authorized = true;
        
        $cron_class->run();
        
        $blowfis->_database->prepare('UPDATE sulake_jobs SET last = ? WHERE id = ?')
                ->bindParameters(array(time(), $job_array['id']))->execute();
    }
}
?>
