<?php

	include_once "board_functions.php";

	$user_array = generateUserArray($conn); //board_functions

	$user = matchUserIdToUsername($_SESSION['id'], $user_array);

	$user_query = "SELECT * FROM members WHERE username='$user'";
	$user_result = $conn->query($user_query);


	while ($user = $user_result->fetch_assoc()) {

		if (!isset($_GET['topic_id'])) { //not in a topic

				echo "<center>";
				echo "<a href='profile.php?id=".$user['id']."'>".$user['username']."</a> | <a href='postmsg.php'>Create New Topic</a> | <a href='logout.php'>Logout</a>";
				echo "</center>";

		} else {

				echo "<center>";
				echo "<a href='profile.php?id=".$user['id']."'>".$user['username']."</a> | <a href='topiclist.php'>Topic List</a> | <a href='postmsg.php?topic_id=".$_GET['topic_id']."'>Reply</a> | <a href='logout.php'>Logout</a>";
				echo "</center>";

		}


		echo "<br>";

	}

?>