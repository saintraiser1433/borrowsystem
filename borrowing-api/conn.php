<?php
	$conn = new mysqli('localhost', 'root', '', 'borrowing');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
?>