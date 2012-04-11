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

################################################
//Blowfis' configuration array
$_blowfisConfiguration = array();


################################################
//Website origin
$_blowfisConfiguration['site']['origin'] = 'http://localhost/'; //Yes it does end in a slash!

################################################
//Website folder origin
/*
 * root - It's not in a folder and is physically in the root index, if not define it here.
 */
$_blowfisConfiguration['site']['folder'] = 'blowfis';

################################################
//Website name
$_blowfisConfiguration['site']['name'] = 'Blowfis';


################################################
//Website tagline
$_blowfisConfiguration['site']['tagline'] = 'Im back!';


################################################
//Website environment
/*
 * 0 - Consumer; You don't want to see any errors at all!
 * 1 - ?; You may want to see some important errors if it affects the website.
 * 2 - Developer; You want to see every error that the system throws!
 */
$_blowfisConfiguration['site']['environment'] = 2;


################################################
//Database type
/*
 * 0 - MySQL; The basic MySQL database which is supported almost everywhere.
 * 1 - MySQLi; An upgrade from MySQL, includes prepared statements.
 * 2 - PDO; Another database system that used prepared statements also!
 */
$_blowfisConfiguration['database']['type'] = 1;


################################################
//Database host
$_blowfisConfiguration['database']['host'] = 'localhost';


################################################
//Database name
$_blowfisConfiguration['database']['name'] = 'mcd';


################################################
//Database username
$_blowfisConfiguration['database']['user'] = 'root';

################################################
//Database password
$_blowfisConfiguration['database']['password'] = 'lol123';
?>