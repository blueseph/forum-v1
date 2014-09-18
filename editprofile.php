<?php

	session_start();
	include_once "board_functions.php";
	include_once "dbconnect.php";

	if (!isset($_SESSION['id'])) { //user not logged in

		redirect('index.php');

	} else {

		if((!$_POST) && (!$_GET)) { //user came in thru editpost.php - redirect

			redirect('topiclist.php');

		} else { 

			if ($_POST) { // user submitted information
				


			} else { //grab users information and display it



			}

		}

	}

?>