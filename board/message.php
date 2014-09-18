<?php

	session_start();
	include_once "board_functions.php";
	include_once "site_functions.php";

	if (!isset($_SESSION['id'])) { //user not logged in

		redirect('index.php');

	} else { // user is logged in

		if(!isset($_REQUEST['id']) && !isset($_REQUEST['topic']) && !is_numeric($_REQUEST['id']) && !is_numeric($_REQUEST['topic_id'])) { //if url info is valid

			giveError("Invalid Post");

		} else {

			include_once "dbconnect.php";

			$user_array = generateUserArray(); //board_functions
			$id = $_REQUEST['id'];
			$topic_id = $_REQUEST['topic_id'];

			if ($_GET['r'] && (!$_GET['r'] == 0 && (is_numeric($_GET['r'])))) { //post is revised {

				$revision = $_GET['r'];

				if (!$revision==0){

					$revision -= 1;
				}

				$post_query = "SELECT * FROM edited_posts WHERE post_id='$id' LIMIT $revision, 1";
				$post_result = $conn->query($post_query);
				$post_num_rows = $post_result->num_rows;

			} else {

				$post_query = "SELECT * FROM posts WHERE id='$id'";
				$post_result = $conn->query($post_query);
				$post_num_rows = $post_result->num_rows;

			}

			if ($post_num_rows=0) { //if postid does not exist

				giveError("Invalid Post");

			} else {

				while ($post = $post_result->fetch_assoc()) {

					$poster = matchUserIdToUsername($post['post_creator_id'], $user_array); //board_functions

					$date = formatTime($post['post_time']); //board_functions

					$filter = array('exist' => false);

					$edited = array('exist' => false);

					$details = array('exist' => false);

					$header = assemblePostHeader($poster, $post['post_creator_id'], $date, $details, $edited, $filter); //board_functions

					include_once "board_header.php";

					$assembledPost = assemblePost($header, $post['post_content']); //board_functions

					if ($_SESSION['id'] == $post['post_creator_id']) { //post creator is looking at his own post

						echo "<a class='pure-button' href='editpost.php?id=".$post['id']."' style='margin-top: 5px;'>Edit Post</a>";
						echo "<a class='pure-button' href='deletepost.php?id=".$post['id']."' style='margin-left: 10px; margin-top: 5px;'>Delete Post</a>";

					}

					if (isset($_GET['r'])) {

						if (is_numeric($_GET['r'])) {

							$revision = $_GET['r'];

							$revision_query = "SELECT * FROM edited_posts WHERE post_id='$id' ORDER BY post_time DESC";
							$revision_result = $conn->query($revision_query);
							$revision_num_row = $revision_result->num_rows;

							if (!$revision_num_row==0) {

								echo "<br>";
								echo "<br>";
								echo "Revisions:<br>";

								$i = 0;

								if ($revision==$i) {

									echo "Current<br>";

								} else {

									echo "<a href='message.php?id=$id&topic_id=$topic_id&r=$i'>Current</a><br>";

								}

								$i++;

								while ($row = $revision_result->fetch_assoc()) {

									$date = formatTime($row['post_time']);

									if ($revision==$i) {

										echo "$date<br>";

									} else {

										echo "<a href='message.php?id=$id&topic_id=$topic_id&r=$i'>$date</a><br>";

									}

									$i++;

								}

							}

							$revision_result->free();

						}

					}

					include_once "footer.php";
				
				}

			}

			$conn->close();

		}

	}

?>


