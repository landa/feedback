<?php

	session_start();
	
	include_once 'database.php';
	
	$logged_in = isset($_SESSION['athena']);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Feedback</title>
	<link href="css/main.css" rel="stylesheet" type="text/css" />
	<script src="javascript/prototype/prototype.js" type="text/javascript"></script>
	<script src="javascript/scriptaculous/scriptaculous.js?load=effects,controls" type="text/javascript"></script>
	<script src="javascript/main.js" type="text/javascript"></script>
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
	<center>
		<div id="container">
			<div id="logo">
				<div id="top">
					<img src="images/logo.png"/>
				</div>
				<div id="stats">
					<?php
						
						if ($logged_in) {
							// We want to display some basic information about the user.
							// Let's show them their first and last names, their email
							// address, and the number of times they've used the system.

							// To get that last one, we need to query the database. We
							// want to SELECT logins from the 'users' table WHERE the id
							// is the one that matches the id of our user.

							$id = $_SESSION['id'];
							$sql = mysql_query("SELECT logins FROM users WHERE id='$id'");
							$obj = mysql_fetch_object($sql);
							$visits = $obj->logins;

							echo '<center><u>' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</u><br/>';
							echo $_SESSION['athena'] . '@mit.edu<br/>';
							echo 'Visited ' . $visits . ' times</center>';
						}
						else {
							?>
							
								Feedback allows instructors to get feedback mid-lecture. It was also developed to help teach PHP to 6.470 students.
							
							<?php
						}

					?>
				</div>
				<div id="controls">
				<?php
				
					if ($logged_in) {
						
						// List courses for which the user is authorized to create lectures.
						$id = $_SESSION['id'];
						$query = "SELECT * FROM courses, authorization WHERE courses.id = authorization.course_id AND authorization.user_id = $id";
						$sql = mysql_query($query);
						$courses = array();
						if (mysql_num_rows($sql) > 0) {
							echo "Authorized for ";
							while ($obj = mysql_fetch_object($sql)) {
								array_push($courses, $obj->num);
							}
							echo implode(",", $courses) . '<br /><br />';
						}
						
						?>
						
							<input type="button" value="Log out" onclick="window.location = 'logout.php'"/>
						
						<?php
					}
					else {
						?>
						
							<input type="button" value="Log in" onclick="window.location = 'auth.php'"/>
						
						<?php
					}
				
				?>
				</div>
				<div style="clear: both"> </div>
			</div>
			<div id="lectures">
				<?php
				
					// List all of the lectures that are going on right now, and possibly
					// lectures that have recently ended.
					
					$sql = mysql_query("SELECT * FROM courses, lectures");
					if (mysql_num_rows($sql) == 0) echo "There are no lectures going on right now.";
					else {
						while ($obj = mysql_fetch_object($sql)) {
							echo '<div class="lecture_item">' . $obj->num . ': ' . $obj->title
								 . ' Lecture<div class="lecture_popularity">popularity ' . $obj->popularity . '</div></div>';
						}
					}
					
					// Create a button that allows a user to start a lecture for a subject
					// that they are authorized to control.
					
					if (count($courses) > 0) {
						echo '<br/><center><select style="min-width: 100px">';
						foreach ($courses as $v) {
							echo "<option>" . $v . "</option>";
						}
						echo '</select> <input type="text" id="lecture_title" value="Enter a lecture title (required)" style="width: 350px" class="quickwrite"/>
							  <input type="button" value="Create a new lecture"/></center>';
					}
				
				?>
			</div>
			<div style="clear: both"> </div>
		</div>
		<div style="clear: both"> </div>
	</center>
</body>
</html>
