<?php
	
	session_start();

	//if (!isset($_SERVER['HTTPS'])) {

	//	header('location: https://eeatc.com');

	//} else {

		if (!isset($_SESSION['id']) && !isset($_COOKIE['ip']))  { //user not logged in

			include_once "index_header.php";

			include_once "loginbar.php";

			include_once "footer.php";

		} else {

			if (isset($_COOKIE['ip'])) {

				include_once "dbconnect.php";
				include_once "password.php";

				$all_user_query = "SELECT * FROM members";
				$all_user_result = $conn->query($all_user_query);
				$all_user_array = $all_user_result->fetch_assoc();

				foreach ($all_user_array as $user) {

					if (password_verify($user['last_login_ip'], $_COOKIE['ip'])==1) {

						$_SESSION['id'] = $user['id'];
						break;

					}

				}

				$all_user_result->free();
				$conn->close();

			}

			header("location: topiclist.php");

		}

	//}

?>