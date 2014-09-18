<?php
	session_start();
	include_once "site_functions.php";

	if ($_POST) { // if user attempted to login

			if ($_POST['username'] && $_POST['password']) { //if user entered both login and password

				include_once "dbconnect.php";

				$username = $_POST['username'];
				$username = $conn->escape_string($username);

				$password = $_POST['password'];

				$query = "SELECT * FROM members WHERE username='$username'";
				$result = $conn->query($query); 
				$rows = $result->num_rows;

				if ($rows != 0) { //if there's at least one result

					while ($row = $result->fetch_assoc()) {
						$dbname = $row['username'];
						$dbhash = $row['password']; //this password is already bcrypt hashed
						$dbid = $row['id'];
					}

					if ($_POST['username']==$dbname && password_verify($_POST['password'], $dbhash)==1) { // login name and password match

						//user has logged in. set session info

						$_SESSION['id'] = $dbid;
						$_SESSION['name'] = $dbname;

						//update last_login_ip for cookie security reasons

						$ip = $_SERVER['REMOTE_ADDR'];

						$ip_update_query = "UPDATE members SET last_login_ip='$ip' WHERE id='$dbid'";
						$ip_result = $conn->query($ip_update_query);

						if (isset($_POST['remember'])) {

							if ($_POST['remember'] == 'on') { //if user wants to stay logged in, set a cookie. hash ip

								$expire = time() + 86400; // 24 hours

								//hash the ip like we would a password
									$options = array('cost' => 11);
									$hash = password_hash($ip, PASSWORD_BCRYPT, $options);

								setcookie('ip', $hash, $expire, '/', '//eeatc.com', isset($_SERVER["HTTPS"]), true);

							}

						}

						$conn->close();

						//redirect("https://eeatc.com/topiclist.php");
						redirect("topiclist.php");

					} else {

						$conn->close();
						$result->free();
						// wrong password
						redirectIn(3, "index.php");
						giveError("Incorrect username or password");


					}

				} else { // wrong username

						$conn->close();
						$result->free();
						redirectIn(3, "index.php");
						giveError("Incorrect username or password");


				}

			} else {

				$conn->close();
				$result->free();
				redirectIn(3, "index.php");
				giveError("Please enter a username or password");

			}
			
	} else {

		redirect("index.php");

	}
?>

