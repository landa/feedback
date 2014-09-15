<?php

	session_start(); // Must include this call every time we work with sessions
	
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	include_once 'database.php'; // Include our database code
	
	// If the user is here, we want to log them out and then prompt them
	// to log back in, perhaps with a different account.

	$_SESSION = array(); // Reset the $_SESSION array to a blank variable

	// If they're using HTTPS, then it must mean that they're trying to log in
	// using their MIT certificates.
	if (array_key_exists('HTTPS', $_SERVER)) {
		$fullname = explode(" ", $_SERVER['SSL_CLIENT_S_DN_CN']);
		$first_name = $fullname[0];
		$last_name = $fullname[count($fullname)-1];
		// The user's Athena username is whatever comes before the @ in their email
		$email = explode('@', $_SERVER['SSL_CLIENT_S_DN_Email']);
		$athena = $email[0];
		
		// Remember everything we need in the $_SESSION variable
		$_SESSION['first_name'] = $first_name;
		$_SESSION['last_name'] = $last_name;
		$_SESSION['athena'] = $athena;
		
		// Okay, now that we've saved this info in the session, let's make a record
		// in the database.
		$thequery = "INSERT INTO users
					 (first_name, last_name, athena)
					 VALUES ('$first_name', '$last_name', '$athena')
					 ON DUPLICATE KEY UPDATE logins=logins+1";
		mysql_query($thequery) or die(mysql_error()); // Make the query and short circuit out or die with the error
		$_SESSION['id'] = mysql_insert_id(); // Get the id from the row we just inserted or updated
		
		// Now we just have to redirect the user back to wherever they came from.
		// We are going to do this a simple way and just send them back to index.php,
		// but you could pass a return address as a GET parameter.
		$url = 'http://' . $_SERVER['SERVER_NAME'] . '/feedback/index.php';
		header("Location: $url");
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Feedback | log in</title>
	<link href="css/main.css" rel="stylesheet" type="text/css" />
	<script src="javascript/prototype.js" type="text/javascript"></script>
	<script src="javascript/scriptaculous.js?load=effects,controls" type="text/javascript"></script>
	<script src="javascript/main.js" type="text/javascript"></script>
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
	<div style="padding: 30px">
		<img src="images/logo.png"/><br/><br/>
		<a href="https://<?php echo $_SERVER['SERVER_NAME'] . ':444' . $_SERVER['PHP_SELF']?>">Click here</a> to login with your MIT certificates.
	</div>
</body>
</html>
