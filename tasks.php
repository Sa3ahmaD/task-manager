<?php

// $action = isset($_POST['action']) ? $_POST['action'] : '';
include_once 'config.php';
$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

if (!$connection) {
	throw new Exception("Cannot connect to database");
} else{
	$action = $_POST['action'] ?? '';

	if (!$action) {
		header('location: index.php');
	} else {
		if ('add' == $action) {
			// insert record
			$task = $_POST['task'];
			$date = $_POST['date'];
			
			if ($task && $date) {
				$query = "INSERT INTO ".DB_TABLE." (task,date) VALUES ('{$task}','{$date}')";
				mysqli_query($connection, $query);
				header('location: index.php?added=true');
			} else {
				header('location: index.php');
			}
		} else if ('complete' == $action) {
			$taskid = $_POST['taskid'];
			if ($taskid) {
				$query = "UPDATE ".DB_TABLE." SET complete=1 WHERE id={$taskid} LIMIT 1";
				mysqli_query($connection, $query);
			}
			header('location: index.php');
		} else if ('delete' == $action) {
			$taskid = $_POST['taskid'];
			if ($taskid) {
				$query = "DELETE FROM ".DB_TABLE." WHERE id={$taskid} LIMIT 1";
				mysqli_query($connection, $query);
			}
			header('location: index.php');
		} else if ('incomplete' == $action) {
			$taskid = $_POST['taskid'];
			if ($taskid) {
				$query = "UPDATE ".DB_TABLE." SET complete=0 WHERE id={$taskid} LIMIT 1";
				mysqli_query($connection, $query);
			}
			header('location: index.php');
		}
	}
}

mysqli_close($connection);