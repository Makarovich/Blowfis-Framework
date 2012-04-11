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
 * name: Template Class
 * description: A class that handles all things to do with HTML
 */

class Template
{
    ################################################
    //Temmplate content
    var $_templateContent;
    
    ################################################
    //The content at the end of the _templateContent
    private $_endContent;
    
    ################################################
    //The template parameters
    private $_templateParameters = array();
    
    ################################################
    //The template css files
    private $_templateCascading;
    
    ################################################
    //The template js files
    private $_templateJavascript;
    
    ################################################
    //Do we have an error on our hands?
    private $_hasError;
    
    ################################################
    //The template body
    private $_templateBody;
    
    ################################################
    //Have we already init'd our background?
    private $_backgroundCreated;
    
    ################################################
    //Directories
    private $_templateDirectory;
    
    private $_cascadingDirectory;
    
    private $_javascriptDirectory;
    
    public function __construct()
    {
        ################################################
        //Set our directories..
        $this->_templateDirectory = APPLICATION.LIBRARY.VIEWS.HTML;
        $this->_cascadingDirectory = APPLICATION.LIBRARY.VIEWS.CASCADING;
        $this->_javascriptDirectory = APPLICATION.LIBRARY.VIEWS.JAVASCRIPT;
    }
    
    public function addTemplate($templateFile)
    {
        $this->onCreate();
        
        ################################################
        //Check if it's a valid html file.. 
        if (!strpos($templateFile, '.html'))
        {
            $templateFile = $templateFile.'.html';
        }
        
        ################################################
        //We have an error so let's stop all dis.
        if ($this->_hasError)
        {
            exit;
        }
        
        ################################################
        //Footer is always at end so skip it.
        if ($templateFile == 'footer')
        {
            exit;
        }
        
        ################################################
        //Check if our templateFile exists!
        if (!file_exists(APPLICATION.LIBRARY.VIEWS.HTML.$templateFile))
        {
            trigger_error($templateFile.' does not exist');
            exit;
        }
        
        if (is_null($this->_templateContent))
        {
            $this->_templateContent = $this->grabTemplate(APPLICATION.LIBRARY.VIEWS.HTML.$templateFile);
        }
        else
        {
            $this->_templateContent = $this->_templateContent.BR.$this->grabTemplate(APPLICATION.LIBRARY.VIEWS.HTML.$templateFile);
        }
    }
    
    public function addFooter()
    {
        if (is_null($this->_endContent))
        {
            $this->_endContent = $this->grabTemplate(APPLICATION.LIBRARY.VIEWS.HTML.'page-footer.html');
        }
        else
        {
            $this->_endContent = $this->_endContent.BR.$this->grabTemplate(APPLICATION.LIBRARY.VIEWS.HTML.'page-footer.html');
        }
    }
    public function addCascading($cascadingFile)
    {
        ################################################
        //Check if it's a valid css file.. 
        if (!strpos($cascadingFile, '.css'))
        {
            $cascadingFile = $cascadingFile.'.css';
        }
        
        ################################################
        //Check if our cascadingFile exists!
        if (!file_exists(APPLICATION.LIBRARY.VIEWS.CASCADING.$cascadingFile))
        {
            trigger_error($cascadingFile.' does not exist');
            exit;
        }
        
        $cascadingRule = new includeFile(APPLICATION.LIBRARY.VIEWS.CASCADING.$cascadingFile);
        
        $cascadingRule = $cascadingRule->result();
        
        if (is_null($this->_templateCascading))
        {
            $this->_templateCascading = $cascadingRule;
        }
        else
        {
            $this->_templateCascading = $this->_templateCascading.BR.$cascadingRule;
        }
    }
    
    public function addJavascript($javascriptFile)
    {
        ################################################
        //Check if it's a valid js file.. 
        if (!strpos($javascriptFile, '.js'))
        {
            $javascriptFile = $javascriptFile.'.js';
        }
        
        ################################################
        //Check if our javascriptFile exists!
        if (!file_exists(APPLICATION.LIBRARY.VIEWS.JAVASCRIPT.$javascriptFile))
        {
            trigger_error($javascriptFile.' does not exist');
            exit;
        }
        
        $javascriptRule = new includeFile(APPLICATION.LIBRARY.VIEWS.JAVASCRIPT.$javascriptFile);
        
        $javascriptRule = $javascriptRule->result();
        
        if (is_null($this->_templateJavascript))
        {
            $this->_templateJavascript = $javascriptRule;
        }
        else
        {
            $this->_templateJavascript = $this->_templateJavascript.BR.$javascriptRule;
        }
    }
    
    private function grabTemplate($filePath)
    {
        return file_get_contents($filePath);
    }
    
    public function setParameter($_templateKey, $_templateValue) 
    { 
        $this->_templateParameters['{'.$_templateKey.'}'] = $_templateValue;  
    } 
    
    private function parseParameters($requestedPage)
    {
        return str_replace(
                array_keys($this->_templateParameters), 
                array_values($this->_templateParameters), 
                $requestedPage); 
    }
    
    public function publishHTML()
    {
        global $blowfis;
        
        ################################################
        //Set our title!
        $this->parseTitle();
        
        ################################################
        //Set our javascript and css parameters
        $this->setParameter('javascript', $this->_templateJavascript);
        $this->setParameter('cascading', $this->_templateCascading);
        
        ################################################
        //Set default javascript files, needed
        //TODO: add the functions here ;)
        
        ################################################
        //Set some default parameters
        $this->setParameter('content', $this->_templateContent);
        $this->setParameter('end', $this->_endContent);
        
        $this->setParameter('exec_time', round(microtime(true) - $blowfis->_executionStart, 3));
        $this->setParameter('query_count', $blowfis->_database->_queryCount);
        
        ################################################
        //ECHO the template content w/ parsed parameters.
        echo $this->parseParameters($this->_templateBody);
    }
    
    private function parseTitle()
    {
        $name = (explode('.', LOCATION));
        
        $name = ucfirst($name[0]);
        
        $this->setParameter('title', TITLE.'-'.$name);
    }
    
    ################################################
    //Event functions
    
    ################################################
    //When a template is created..?
    private function onCreate()
    {
        if ($this->_backgroundCreated)
        {
            return;
        }
        
        $this->_backgroundCreated = true;
        
        $this->_templateBody = $this->grabTemplate(APPLICATION.LIBRARY.VIEWS.HTML.'templateBackground.html');
    }
}

class simpleTemplate
{
    ################################################
    //The simpleTemplate content
    private $_templateContent;
    
    public function __construct($file)
    {
        $this->_templateContent = file_get_contents(
                APPLICATION.LIBRARY.VIEWS.HTML.SIMPLE.$file.'.html'
                );
    }
    
    ################################################
    //Parse our parameters!
    public function replace($inquiry, $new_value)
    {
        $this->_templateContent = str_ireplace(
                '['.$inquiry.']', 
                $new_value, 
                $this->_templateContent
                );
    }
    
    ################################################
    //Grab our html'd result.
    public function result()
    {
        return $this->_templateContent;
    }
}

class includeFile
{    
    ################################################
    //The file's rule
    private $_fileRule;
    
    public function __construct($requestedFile)
    {   
        switch($this->grabExtension($requestedFile))
        {
            case 'js':
                $this->_fileRule = '<script type="text/javascript" src="'.$requestedFile.'"></script>';
                break;
            
            case 'css':
                $this->_fileRule = '<link rel="stylesheet" type="text/css" href="'.$requestedFile.'" />';
                break;
        }
        
        return $this;
    }   
    
    ################################################
    //Grab the extension of the file!
    private function grabExtension($requestedFile)
    {
        $array = explode('.', $requestedFile);
        
        return $array[2];
    }
    
    public function result()
    {
        return (string)$this->_fileRule;
    }
}
?>
