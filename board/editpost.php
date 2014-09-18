<?php

	session_start();
	include_once "board_functions.php";
	include_once "site_functions.php";
	include_once "dbconnect.php";


	if (!isset($_SESSION['id'])) { //user not logged in

		redirect('index.php');

	} else {

		if((!$_POST) && (!$_GET)) { //user came in thru editpost.php - redirect

			redirect('topiclist.php');

		} else { 

			if(!isset($_REQUEST['id']) && !is_numeric($_REQUEST['id'])) { //if url info is valid

				if (!isset($_POST['post_content']) && !isset($_POST['post_id'])) { //user came in thru url @ editpost.php

					giveError("Invalid Post");

				} else { // post came from form. escape it and store it

					if (!is_numeric($_POST['post_id'])) {

						giveError("Invalid Request. Please try again");

					} else {

						$edited_post_content = $_POST['post_content'];
						$edited_post_time = date('Y-m-d H:i:s');

						//grab all the stuff we need to edit the post

						$post_id = $_POST['post_id'];

						$post_query = "SELECT * FROM posts WHERE id='$post_id'";
						$post_result = $conn->query($post_query);

						$total_rows = $post_result->num_rows;

						if ($total_rows == 0) {

							giveError("Invalid Request.");

						} else {

							while ($row = $post_result->fetch_assoc()) {

								$post_creator_id = $row['post_creator_id'];
								$post_time = $row['post_time'];
								$topic_id = $row['topic_id'];
								$post_content = $row['post_content'];

							}

							editPost($edited_post_content, $edited_post_time, $post_id, $post_creator_id, $post_time, $topic_id, $post_content);
							redirect("showmsg.php?topic_id=$topic_id");

						}

					}

				}

		} else { // post came from edit button

			$id = $_REQUEST['id'];

			$post_query = "SELECT * FROM `posts` WHERE id='$id'";
			$post_result = $conn->query($post_query);

			$total_rows = $post_result->num_rows;

			if ($total_rows == 0) {

				giveError("Invalid Post");

			} else {

				while ($row = $post_result->fetch_assoc()) {

					$creator_id = $row['post_creator_id'];
					$post_content = $row['post_content'];

				}

				if ($_SESSION['id'] == $creator_id) { //same user who made the post is making the request to edit post

					include_once "board_header.php";
					displayEditPostForm('editpost.php', $post_content, $id);
					include_once "footer.php";

				}

			}

		$post_result->free();
		$conn->close();

		}

	}

	}


?>