<html>
	<head>
		<title>Roblox game data</title>
	</head>
	<body>
		<?php 
			include "db.php";
			$conn = mysql_connect("localhost:3306", $db_user, $db_passwd);
			if (! $conn) {
				die('Could not connect to database: ' . mysql_error());
			}
			mysql_select_db('ROBLOX');
			if (isset($_GET['id'])) {
				// id is set so we have a server;
				if (isset($_GET['chat'])){
					// chat is set, inspect server chat
				} elseif (isset($_GET['event'])) {
					// event is set, inspect server events	
				} else {
					// dump server URLs for inspect
					echo "hi";
				}
			} elseif (isset($_GET['page'])) { 
				// have page, show offline servers for this page
			} else {
				// present homepage 
				if (isset($_GET['page'])) {
				} else { 
					$query = "SELECT * FROM SERVERS WHERE last_activity > (NOW() - 60*5)";
					$retval = mysql_query($query, $conn);
					if (! $retval) {
						die(mysql_error());
					}
					echo "<h1>Online servers</h1>";
					echo "<table>";
					echo "<tr><th>JobId</th><th>Last Activity</th></tr>";
					while ($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
						echo "<tr>";
						$jobid = $row['job_id'];
						$last_active = $row['last_activity'];
						//add a href here
						$data = array('id' => $row['id']);
						$href = "<a href=/index.php?" . (http_build_query($data)) . ">$jobid</a>";
						echo "<td>$href</td><td>$last_active</td>";
						echo "</tr>";
					}
					echo "</table>";
				}
			}
		?>
	</body>
</html>
