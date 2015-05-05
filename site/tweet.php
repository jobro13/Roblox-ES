<?php
	require("twitter.php");
	require ("twitteroauth/autoload.php");
	use Abraham\TwitterOAuth\TwitterOAuth;
	$connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_access_token, $twitter_access_secret);
	$content = $connection->get("account/verify_credentials");
	$statues = $connection->post("statuses/update", array("status" => "hello world"));
?>
