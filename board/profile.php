<?php

	session_start();
	include_once "board_functions.php";
	include_once "site_functions.php";

	if (!isset($_SESSION['id'])){ //user not logged in

		redirect('index.php');

	} else { // user is logged in

		if(!isset($_GET['id'])) {

			giveError("Invalid User");

		}

		if (!is_numeric($_GET['id'])) {

			giveError("Invalid User");

		}

		include_once "dbconnect.php";

		$user_query = "SELECT * FROM members WHERE id='".$_GET['id']."'";
		$user_result = $conn->query($user_query);
		$user_num_results = $user_result->num_rows;

		if($user_num_results==0) {

			giveError("Invalid User");

		} else {

			while ($user = $user_result->fetch_assoc()) { 

				$user_id = $user['id'];
				$username = $user['username'];
				$email = $user['email'];

				}

			include_once "board_header.php";

			echo "<table class='pure-table pure-table-bordered' width=80%>";

			echo "<tr>";
			echo "<td colspan='2' style='text-align: center;'>Current Information for $username</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td>Username</td>";
			echo "<td>$username</td>";
			echo "<tr>";

			echo "<tr>";
			echo "<td>User ID</td>";
			echo "<td>$user_id</td>";
			echo "</tr>";

			// if this id matches users id, print more information and options

			if ($_SESSION['id'] == $_GET['id']) {

			echo "<tr>";
			echo "<td>Email</td>";
			echo "<td>$email</td>";
			echo "</tr>";

			}

		}

		include_once "footer.php";

		$conn->close();
	}


?>
