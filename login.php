<?php
session_start();
include "db_conn.php";

// VALIDATE THE USER-NAME & PASSWORD FOR SIGN-IN FORM 
if (isset($_POST['uname']) && isset($_POST['password'])) {

	function validate($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);

	if (empty($uname)) {
		header("Location: index.php?error1=User Name is required");
		exit();
	} else if (empty($pass)) {
		header("Location: index.php?error2=Password is required");
		exit();
	} else {
		$sql = "SELECT * FROM users WHERE user_name='$uname' AND password='$pass'";

		// $result = mysqli_query($conn, $sql);
		$result = mysqli_query($conn, $sql) or die("<span class='error'>Database not connected. Connection Failed.</span>");

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
			if ($row['user_name'] === $uname && $row['password'] === $pass) {
				$_SESSION['user_name'] = $row['user_name'];
				$_SESSION['name'] = $row['name'];
				$_SESSION['id'] = $row['id'];
				header("Location: index.php");
				exit();
			} else {
				if ($row['user_name'] !== $uname) {
					header("Location: index.php?error1=Incorect User name.");
					exit();
				} else {
					header("Location: index.php?error2=Incorect Password.");
					exit();
				}
			}
		} else {
			header("Location: index.php?error1=No Such User name&error2=No Such password");		// Instead of 29 line.
			exit();
		}
	}
} else {
	header("Location: index.php");
	exit();
}
