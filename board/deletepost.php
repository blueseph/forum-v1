<?php

	session_start();
	include_once "board_functions.php";
	include_once "site_functions.php";

	if (!isset($_SESSION['id'])) { //user not logged in

		redirect('index.php');

	} else {

		if(!isset($_REQUEST['id']) && !is_numeric($_REQUEST['id'])) { //if url info is valid

			giveError("Invalid Post");

		} else { // grab details of post

			include_once "dbconnect.php";

			$user_query = "SELECT * FROM posts WHERE id='".$_REQUEST['id']."'";
			$user_result = $conn->query($user_query);

			$user_rows = $user_result->num_rows;

			if ($user_rows == 0) { //if this post doesnt exist

				giveError("Invalid Post");

			} else { //post exists. check if the user that made this post has permission to delete this post (can only be done by same user)

				while ($post = $user_result->fetch_assoc()) { //grab the info
					
					$pcid = $post['post_creator_id'];
					$deleted = $post['deleted'];
					$topic_id = $post['topic_id']; //for redirect

				} 

				if ($_SESSION['id']==$pcid) { //make the check

					if (!$deleted==true) { // check to see if post has been previously deleted

						// fucking finally. we can delete the post

						$deleted_text = "[This post has been willingly deleted by its creator]";

						$delete_query = "UPDATE posts SET post_content='$deleted_text' WHERE id='".$_REQUEST['id']."'";
						$update_deleted = "UPDATE posts SET deleted='true' WHERE id='".$_REQUEST['id']."'";

						$conn->query($delete_query);
						$conn->query($update_deleted);

						redirect("showmsg.php?topic_id=$topic_id");
						
					} else {

						giveError("This post has already been deleted");

					}

				} else { 

					giveError("You aren't able to delete this post.");

				}

			}

			$conn->close();
			$user_result->free();
			
		}

	}

?>