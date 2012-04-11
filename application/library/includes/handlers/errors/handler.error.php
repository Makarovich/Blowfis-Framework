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
 * name: Error Handler
 * description: The function that will be called when an error is thrown.
 */


//Write out our error
function writeError($error_number, $error_message, $error_file, $error_line)
{
    global $blowfis;
    
    //OBV: The administrator doesn't want any errors shown.
    if ($blowfis->_configuration['site']['environment'] == 0)
    {
        return;
    }
    
    $output = new simpleTemplate('error');

    $output->replace('title', $error_number);
    $output->replace('error', $error_message);
    $output->replace('file', $error_file);
    $output->replace('line', $error_line);

    echo $output->result();    
}
?>
