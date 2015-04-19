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
			echo("<p><a href=/index.php>Home</a></p>");
			if (isset($_SERVER['HTTP_REFERER'])) {
				$ref = htmlspecialchars($_SERVER['HTTP_REFERER']);
				echo("<p><a href=$ref>Back</a></p>");
			}
			if (isset($_GET['jobid'])) {
                                $jobid = $_GET['jobid'];
                                echo("<h1>Viewing server $jobid</h1>");
                        }
			if (isset($_GET['id'])) {
				// id is set so we have a server;
				if (isset($_GET['chat'])){
					// chat is set, inspect server chat
					if (isset($_GET['page'])) {
						// load page x 
					} else { 
						// show page 1
						$id = $_GET['id'];
						// order, descending, latest chats on top
						$query =  "SELECT * FROM CHAT$id ORDER BY `num` DESC;";
						$result = mysql_query($query, $conn);
						echo("<table><tr><th>Time</th><th>user id</th><th>Player name</th><th>Chat message</th></tr>");

						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
							$time = $row['timestamp'];
							$userid = $row['userid'];
							$playername = $row['playername'];
							$chatstring = $row['chatstring'];
							echo("<tr>");
							echo("<td>$time</td>");
							echo("<td>$userid</td>");
							echo("<td>$playername</td>");
							echo("<td>$chatstring</td>");
							echo("</tr>");
						}
						echo("</table>");
					}
				} elseif (isset($_GET['event'])) {
					// event is set, inspect server events
					if (isset($_GET['page'])) {
						// load page x 
					} else { 
						// show events
						$id = $_GET['id'];
						$query = "SELECT * FROM EVENT$id ORDER BY `num` DESC;";
						$result = mysql_query($query, $conn);
						echo("<table><tr><th>Time</th><th>Event</th></tr>");
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
							$event = $row['event'];
							$time = $row['timestamp'];
							echo ("<tr>");
							echo("<td>$time</td>");
							echo("<td>$event</td>");
							echo("</tr>");
						}
						echo("</table>");
					}
				} else {
					// dump server URLs for inspect
					// get job id
					$id = $_GET['id'];
					$query = "SELECT job_id FROM SERVERS WHERE id=$id";
					$retval = mysql_query($query, $conn);
					$jobid = mysql_result($retval, 0);
					echo "<h1>Viewing server $jobid</h1>";
					// redirect to the 'chat' and 'event' pages
					$event = array('id' => $id, 'event' => 1, 'jobid' => $jobid);
					$chat = array('id' => $id, 'chat' => 1, 'jobid' => $jobid); 
					$event_href = "<a href=/index.php?" . (http_build_query($event)) . ">Events for this server</a>";
					$chat_href  = "<a href=/index.php?" . (http_build_query($chat)) . ">Chat log for this server</a>";
					echo "<p>$event_href</p>";
					echo "</p>$chat_href</p>";
				}
			} elseif (isset($_GET['page'])) { 
				// have page, show offline servers for this page
			} else {
				// present homepage 
				if (isset($_GET['page'])) {
					// page list 
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
					// actually add the offline servers
					$query = "SELECT * FROM SERVERS WHERE last_activity <= (NOW() - 60*5)";
					$retval = mysql_query($query, $conn);
					echo("<h1>Offline/Archived servers</h1>");
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
					echo("</table>");
				}
			}
		?>
	</body>
</html>
