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
	$JobId = mysql_real_escape_string($data['JobId']);
	$query = "SELECT id FROM SERVERS WHERE job_id = '$JobId'";
	$id = mysql_query($query, $conn);
	$id = mysql_result($id, 0);
	$tweet = False;
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
		// NEW: TWEET
		$tweet = True; 
	}
	// Call update (kinda haxy, but ye, k thx sql only update when rly update
	$retval = mysql_query("UPDATE SERVERS SET last_activity=null WHERE id=$id", $conn);
	if (! $retval) {
		echo("Could not update: " . mysql_error());
	}
	foreach($data as $type => $value) {
		if ($type != 'JobId') {
			foreach($value as $index => $field) {
				// Type chat ->
				if ($type == 'Chats') {
					$userid = $field['UserId'];
					$playername = mysql_real_escape_string($field['PlayerName']);
					$chatstring = mysql_real_escape_string($field['ChatString']);
					$timestamp = $field['Timestamp'];
					$inschat = "INSERT INTO `CHAT$id` (userid, playername, chatstring, timestamp) VALUES ($userid, '$playername', '$chatstring', FROM_UNIXTIME($timestamp));";
					$result = mysql_query($inschat, $conn);
					if (! $result) {
						echo( "Something went wrong: " . mysql_error());
					} 
				} elseif ($type == 'Events') {
					$event = mysql_real_escape_string($field['Event']);
					$timestamp = $field['Timestamp'];
					$insevent = "INSERT INTO EVENT$id (event, timestamp) VALUES ('$event', FROM_UNIXTIME($timestamp));";
					$result = mysql_query($insevent, $conn); 
					if (! $result) {
						echo(" Something went wrong: " . mysql_error());
					}
				}
				// For expansion, add more types here!!
			}
		}
	}
    	require("twitter.php");
        require ("twitteroauth/autoload.php");

	use Abraham\TwitterOAuth\TwitterOAuth;
	// Tweet here.

	if ($tweet) {
        $connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_access_token, $twitter_access_secret);
        $content = $connection->get("account/verify_credentials");
        $statues = $connection->post("statuses/update", array("status" => "A new Stranded server came online!"));
	}
?>
