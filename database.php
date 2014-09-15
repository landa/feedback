<?php

$dbh = mysql_connect('sql.mit.edu', 'landa', 'redacted');
if (!$dbh)
   die('Could not connect: ' . mysql_error() . '<br />');

mysql_select_db("landa+feedback") or die("No database selected.");

?>
