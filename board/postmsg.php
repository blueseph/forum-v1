<?php

	session_start();

	if (!isset($_SESSION['id'])) {

		redirect('index.php');

	} else { 

		include_once "dbconnect.php";
		include_once "board_functions.php";
		include_once "site_functions.php";

		if (!isset($_REQUEST['topic_id'])) { // user not coming from inside a topic [has no referral id]. user wants to create a topic

			if ($_SERVER['REQUEST_METHOD'] != 'POST')  {// user hasn't submitted the form, so make one

				include_once "board_header.php";
				displayTopicForm('postmsg.php', '');
				include_once "footer.php";

			} else { // user posted topic, create it and post msg

				// create topic 

				$subject = $_POST['subject'];
				$current_time = date('Y-m-d H:i:s');
				$topic_creator_id = $_SESSION['id'];

				createTopic($current_time, $topic_creator_id, $subject);

				// post message 

				$topic_id = $conn->insert_id; // gets id of last inserted topic
				$post_content = $_POST['message'];

				postMsg($topic_id, $current_time, $topic_creator_id, $post_content);

				$conn->close();

				redirect("showmsg.php?topic_id=$topic_id");

				}

		} else { //referred by topic id. no topic creation required

			if (!is_numeric(($_REQUEST['topic_id']))) {

				giveError("Invalid Topic");

			} else {

				if (!isset($_POST['message'])) { //user hasn't submitted form. 

					include_once "board_header.php";
					echo "<form class= 'pure-form pure-form-stacked' method='post' action='postmsg.php'>";
					echo "<fieldset>";
					echo "<textarea name='message' placeholder='Message'></textarea>";
					echo "<input type='hidden' name='topic_id' value='".$_REQUEST['topic_id']."'>";
					echo "<br>";
					echo "<button class='pure-button pure-button-primary'>Post Message</button>";
					echo "</fieldset>";
					echo "</form>";
					include_once "footer.php";

				} else { //user submitted form. process it.
					
					$topic_id = $_POST['topic_id'];
					$current_time = date('Y-m-d H:i:s');
					$post_creator_id = $_SESSION['id'];
					$post_content = $_POST['message'];

					postMsg($topic_id, $current_time, $post_creator_id, $post_content);

					$conn->close();
					redirect("showmsg.php?topic_id=$topic_id");
				}

			}


		}

	$conn->close();

	}


?>