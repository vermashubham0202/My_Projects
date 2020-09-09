<?php
session_start();

$state = $_SESSION["state"];
$district = $_SESSION["district"];
$watershed = $_SESSION["watershed"];
$_SESSION["mode"] = $_POST["mode"];

#Connecting to database
$host = "localhost"; 
$user = "postgres"; 
$pass = "DSS_H@NIH"; 
$db = "test"; 
$con = pg_connect("host=$host port=5432 dbname=$db user=$user password=$pass")or die ("Could not connect to server\n");

#Selecting data input
$query = "SELECT year FROM watershed_scorecard_index WHERE state = '".$state."' AND district = '".$district."' AND watershed = '".$watershed."';";
$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"

echo '<!DOCTYPE html>
	<html>
	<body>';
	
if($_SESSION["mode"] == "view")
{
	echo '<h2>Select year:</h2>';
	echo '<form action="watershed_scorecard_tool.php" method="post">';
	echo '<select name="year">';
	while ($row = pg_fetch_row($rs)) {
		echo '<option value="'.$row[0].'">'.$row[0].'</option>';
	}
	echo'</select>';
	echo '<br><br>';
	echo '<input type="submit" value="Submit">
	</form>';
}
else
{
	echo '<h2>Select years for comparison:</h2>';
	echo '<form action="watershed_scorecard_tool.php" method="post">';
	while ($row = pg_fetch_row($rs)) {
		echo '<input type="checkbox" name="year[]" id="year" value="'.$row[0].'">'.$row[0].'<br>';
	}
	echo '<br><br>';
	echo '<input type="submit" value="Submit">
	</form>';	
} 
	echo '</body>
	</html>';
?>