<?php
	include "db.php";
	$conn = mysql_connect("localhost:3306", $db_user,$db_passwd);
	if(! $conn) {
		die('Could not connect to database: ' . mysql_error());
	}
	mysql_select_db('ROBLOX');
	$data = file_get_contents('php://input');
	if ($_SERVER["HTTP_CONTENT_ENCODING"] == "gzip") {
		$data = gzdecode($data);
	}
	$data = json_decode($data, true);
	$JobId = $data['JobId'];
	echo $JobId;
	$query = "SELECT id FROM SERVERS WHERE job_id = '$JobId'";
	$id = mysql_query($query, $conn);
	$id = mysql_result($id, 0);
	if (empty($id)) {
		// No ID found, so pls, lets make this.
		$iquery = "INSERT INTO SERVERS (job_id) VALUES ('$JobId')";
		$retval = mysql_query($iquery, $conn);
		if (! $retval) {
			echo( "Something went wrong: " . mysql_error());
		}
		$id = mysql_query($query, $conn);
		$id = mysql_result($id, 0);
		// Create tables for chat and events
		$mkchatq = "CREATE TABLE `CHAT$id` (`num` BIGINT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT, `userid` BIGINT NOT NULL, `playername` VARCHAR(30) NOT NULL, `chatstring` TEXT NOT NULL, `timestamp` TIMESTAMP NOT NULL, PRIMARY KEY (`num`));";
		$result = mysql_query($mkchatq, $conn);
		if (! $result) {
			echo ("Something went wrong: " . mysql_error());
		}
		$mkevtq = "CREATE TABLE `EVENT$id` (`num` BIGINT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT, `event` TEXT NOT NULL, `timestamp` TIMESTAMP NOT NULL, PRIMARY KEY (`num`));";
		$result = mysql_query($mkevtq, $conn);
		if (! $result){
			echo("Something went wrong: " . mysql_error());
		}
	}
	// Call update (kinda haxy, but ye, k thx sql only update when rly update
	$retval = mysql_query("UPDATE SERVERS SET last_activity=null WHERE id=$id", $conn);
	if (! $retval) {
		echo("Could not update: " . mysql_error());
	}	
	echo $id;
	foreach($data as $key => $value) {
	}
?>
