<?php
	session_start();
	include_once "dbconnect.php";
	include_once "board_functions.php";
	include_once "site_functions.php";

	if (!isset($_SESSION['id'])){ //user not logged in

		redirect('index.php');

	} else { // user is logged in

		//grab all topics, but sort them by descending order based on "last post"

		$topic_query = "SELECT * FROM topics ORDER BY last_post DESC";
		$topic_result = $conn->query($topic_query);

		$user_array = generateUserArray(); //board_functions

		//begin topic table

		include_once "board_header.php";
		echo "<table class='pure-table pure-table-bordered' width=80%>";
		echo "<thead>";
		echo "<tr>";
		echo "<th width='70%'>Topics</th>";
		echo "<th>Posted By</th>";
		echo "<th>Last Post</th>";
		echo "</tr>";
		echo "</thead>";
		echo " ";
		echo "<tbody>";

		while ($topic = $topic_result->fetch_assoc()) {

			$tc = matchUserIdToUsername($topic['topic_creator_id'], $user_array); //board_functions

			$date = formatTime($topic['last_post']); //board_functions

			echo "<tr>";
			echo "<td><a href='showmsg.php?topic_id=".$topic['id']."'>".$topic['subject']."</a></td>";
			echo "<td>$tc</td>";
			echo "<td>".$date."</td>";
			echo "</tr>";

		}

		echo "</tbody>";
		echo "</table>";

		$topic_result->free();
		$conn->close();
	}


?>