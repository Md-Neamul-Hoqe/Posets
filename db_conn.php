<?php
// Define database informations 
$sname = "localhost";
$unmae = "root";
$password = "";
$db_name = "Posets";    // Check Database Name Before.

// Connect to the database
$conn = mysqli_connect($sname, $unmae, $password, $db_name);       //   no need write any thing in die() for server connection problem.

?>
