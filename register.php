<?php 
	
	include_once "site_functions.php";

	if ($_POST) {

		if ($_POST['password'] != $_POST['password_confirm']) { //passwords dont match

			redirectIn(3, "register.html");
			giveError("Passwords don't match. Returning to registration page");
			$information = 'invalid';

		}

		if (strlen($_POST['name'])<3) { //name must be at least 3 characters

			redirectIn(3, "register.html");
			giveError("Please use a username with at least 3 characters. Returning to registration page");
			$information = 'invalid';

		}
		
		if (strlen($_POST['name'])>20) { //name must be no longer than 20 characters

			redirectIn(3, "register.html");
			giveError("Please use a username with at least 3 characters. Returning to registration page");
			$information = 'invalid';

		}

		if (strlen($_POST['password'])<7) { //password must be at least 6 characters

			redirectIn(3, "register.html");
			giveError("Passwords must be longer than 6 characters. Returning to registration page");
			$information = 'invalid';

		}

		if (preg_match("~[^a-zA-Z0-9_ -']~", $_POST['name'])) { //username contains unusuable characters

			redirectIn(3, "register.html");
			giveError("Username contains unusable characters. Returning to registration page");
			$information = 'invalid';

		}

		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

			redirectIn(3, "register.html");
			giveError("Please enter a valid email. Returning to registration page");
			$information = 'invalid';

		}

		if (!isset($information)) {

			include_once "dbconnect.php";

			$conn = connect();

			$name = $conn->escape_string($_POST['name']);
			$email = $conn->escape_string($_POST['email']);
			$password = $conn->escape_string($_POST['password']);

			//check for duplicate ids/emails

			//names
			$name_query = "SELECT username FROM members WHERE username='$name'";
			$name_result = $conn->query($name_query);
			$name_rows = $name_results->num_rows;

			//emails
			$email_query = "SELECT email FROM members WHERE email='$email'";
			$email_results = $conn->query($email_query);
			$email_rows = $email_results->num_rows;

			if ($email_rows != 0) { //email already in database

				redirectIn(3, "register.html");
				giveError("Email already exists. Returning to registration page");

			} else {

				if ($name_rows != 0) { //name already in database

				redirectIn(3, "register.html");
				giveError("Username already exists. Returning to registration page");


				} else { //otherwise, register user

						//hash password using bcrpyt
							//bcrypt needs a cost. default to 11
							$options = array('cost' => 11);
							$hash = password_hash($password, PASSWORD_BCRYPT, $options);

						$register_query = "INSERT INTO members(username, email, password) VALUES ('$name', '$email', '$hash')";
						$conn->query($register_query) or die("Fatal Error. Please try to register again: ".mysql_error());

						redirectIn(3, "index.php");
						giveError("Registered successfully. Returning to main page");

					}


				}

			$name_result->free();
			$email_results->free();
			$conn->free();

		}

	} else { 

		if (!isset($_SERVER['HTTPS'])) {

			redirect("https://eeatc.com/register.php");

		} else {

			include_once "index_header.php";

			echo "<form class='pure-form' method='post' action='register.php' style='margin-top: 200px'>";
			echo "<fieldset class='pure-group'>";
			echo "<input type='text' class='pure-input-1-4' name='name' placeholder='Username' required>";
			echo "<input type='password' class='pure-input-1-4' name='password' placeholder='Password' required>";
			echo "<input type='password' class='pure-input-1-4' name='password_confirm' placeholder='Confirm Password' required>";
			echo "<input type='email' class='pure-input-1-4' name='email' placeholder='Email' required>";
			echo "</fieldset>";

			echo "<button type='submit' class='pure-button pure-input-1-4 pure-button-primary'>Register</button>";
			echo "</form>";

			include_once "footer.php";

		}

	}

?>