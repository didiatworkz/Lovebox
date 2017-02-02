<!--
							_         
   ____                    | |        
  / __ \__      _____  _ __| | __ ____
 / / _` \ \ /\ / / _ \| '__| |/ /|_  /
| | (_| |\ V  V / (_) | |  |   <  / / 
 \ \__,_| \_/\_/ \___/|_|  |_|\_\/___|
  \____/                              
          
		http://www.atworkz.de	
		   info@atworkz.de	
________________________________________
			Lovebox System 
	   Version 2.0 - February 2017
________________________________________
-->
<?php
$system_version = '2.0';
if (@file_exists('dbase.db')){
		echo '';
	} else {
		echo 'Datenbank ist nicht vorhanden';
    } 

 
ini_set('display_errors',0);
error_reporting(E_ALL|E_STRICT); 

 
$db = new SQLite3("dbase.db");

function redirect($url, $info, $time=1) {
    echo'<meta http-equiv="refresh" content="'.$time.';URL='.$url.'">';
	}
	
function sysinfo($status, $message, $refresh) {
    echo'<script>$.toaster({ priority : \''.$status.'\', title : \'System\', message : \''.$message.'\'});</script>';
	if(isset($refresh)){
		echo'<meta http-equiv="refresh" content="2;URL=index.php">';
	}
}

// Login Check 

$set = $db->query("SELECT * FROM settings WHERE userID=1");
$set = $set->fetchArray(SQLITE3_ASSOC);
	
$Zugangspasswort = $set['password'];
$Benutzername    = $set['username'];
  
 
if($_GET['restart'] == date('d.m.Y')) {
	$db->exec("UPDATE settings SET username='demo', password='fe01ce2a7fbac8fafaed7c982a04e229', refresh='1', refresh_time='10', clean='clean' WHERE userID='1'");
			sysinfo('warning', 'Reset durchgeführt','1');
} 
?>