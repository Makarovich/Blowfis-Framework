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
//The current location
define('LOCATION', basename(__FILE__));

include('bootstrap.php');

################################################
//Multi-Database testing!

$user = $blowfis->_database->prepare('SELECT * FROM users WHERE id = ?')
        ->bindParameters(array(1))->execute();

while($array = $user->fetch_array())
{
    echo $array['id'];
}
?>