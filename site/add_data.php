<?php
	echo "lol";
	echo $_SERVER["CONTENT_TYPE"];
	$data = file_get_contents('php://input');
	echo strlen($data);
	echo "nope";
	echo htmlspecialchars($data);
	if ($_SERVER["HTTP_CONTENT_ENCODING"] == "gzip") {
		$data = gzdecode($data);
		echo $data;
	}
	$data = json_decode($data);
	echo var_dump($data, true);
?>
