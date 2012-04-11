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

ob_start();
session_start();

################################################
//Telling the system we have some auth!
define('BLOWFIS', true);

################################################
//Grabing our main global class, Blowfis!
require('blowfis.php');

$blowfis = new Blowfis();

################################################
//The website title
define('TITLE', $blowfis->_configuration['site']['name']);

################################################
//Start the Steve Jobs class
$blowfis->_jobs->start();
?>