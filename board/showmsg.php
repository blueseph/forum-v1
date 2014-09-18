<?php
	session_start();
	include_once "board_functions.php";
	include_once "site_functions.php";


	if (!isset($_SESSION['id'])){ //user not logged in

		redirect('index.php');

	} else { // user is logged in

		if (!isset($_GET['topic_id'])) {

			redirect('topiclist.php');

		} else {

			if (!is_numeric(($_REQUEST['topic_id']))) {

				giveError("Invalid Topic");

			} else {

				$topic_id = $_GET['topic_id'];

				include_once "dbconnect.php";

				//grab all topics, but sort them by descending order based on "last post"

				$post_query = "SELECT * FROM posts WHERE topic_id = '$topic_id' ORDER BY id ASC";
				$post_result = $conn->query($post_query);

				$responses = $post_result->num_rows;

				if ($responses==0) { //not a valid topic

					$post_result->free();
					giveError("Invalid Post");

				} else {

					if (isset($_GET['u'])) {

						if (is_numeric($_GET['u'])) {

							$filtered_user = $_GET['u'];

						}

					}

					$user_array = generateUserArray(); //board_functions

					include_once "board_header.php"; //board_functions

					while ($post = $post_result->fetch_assoc()) {

						//check to see if post has been edited

						if ($post['edited']>0) {

							$edited = array('exist' => true, 'times' => $post['edited'], 'id' =>$post['id']);

						} else {

							$edited = array('exist' => false);

						}

						$poster = matchUserIdToUsername($post['post_creator_id'], $user_array); //board_functions

						$date = formatTime($post['post_time']);

						$details = array('exist' => true, 'id' => $post['id'], 'topic_id' => $topic_id);

						if (!isset($filtered_user)) {

							$filtered = array('exist' => false, 'pc_id' => $post['post_creator_id'], 'topic_id' => $topic_id, 'id' =>  $post['id']);

							$header = assemblePostHeader($poster, $post['post_creator_id'], $date, $details, $edited, $filtered);

							assemblePost($header, $post['post_content']); //board_functions


						} else {

							if ($post['post_creator_id'] == $filtered_user) {

								$filtered = array('exist' => true, 'pc_id' => $post['post_creator_id'], 'topic_id' => $topic_id, 'id' =>  $post['id']);

								$header = assemblePostHeader($poster, $post['post_creator_id'], $date, $details, $edited, $filtered);

								assemblePost($header, $post['post_content']); //board_functions

							}

						}

					}

				include_once "quickpost.php";

				$post_result->free();

				$conn->close();

				}
		
			}

		}

	}


?>