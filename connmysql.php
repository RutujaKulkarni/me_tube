<?php
function connect_db(){
	global $mysqli;
	$servername = "*********";
	$user = 'SYSADM';
	$password = '********';
	$database = '********';
	$port = 3306;
	$mysqli = new mysqli($servername, $user, $password, $database, $port);

	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') '
				. $mysqli->connect_error);
	}
	return $mysqli;
}

function close_db_connection(){
	//echo 'Closing connection';
	$mysqli->close();
}

?>
