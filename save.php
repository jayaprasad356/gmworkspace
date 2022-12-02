<?php
	$request = $_REQUEST; //a PHP Super Global variable which used to collect data after submitting it from the form
	$name = $request['name']; //get the date of birth from collected data above
	$role = $request['role']; //get the date of birth from collected data above
	$email = $request['email'];
	$mobile = $request['mobile'];
	$password = $request['password'];
	$image = $request['image'];
	$github = $request['github'];
	$upi = $request['upi'];

	$servername = "localhost"; //set the servername
	$username = "root"; //set the server username
	$password = ""; // set the server password (you must put password here if your using live server)
	$dbname = "greymatter"; // set the table name

	$mysqli = new mysqli($servername, $username, $password, $dbname);

	if ($mysqli->connect_errno) {
	  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	  exit();
	}

	// Set the INSERT SQL data
	$sql = "INSERT INTO staffs (name, role, email, mobile, password, image, github, upi)
	VALUES ('".$name."', '".$role."', '".$email."', '".$mobile."', '".$password."', '".$image."', '".$github."', '".$upi."')";

	// Process the query so that we will save the date of birth
	if ($mysqli->query($sql)) {
	  echo "Staffs has been created successfully.";
	} else {
	  return "Error: " . $sql . "<br>" . $mysqli->error;
	}

	// Close the connection after using it
	$mysqli->close();
?>