<?php
	$_SESSION["state"] = "CG";
	$_SESSION["district"] = "kanker";
	$_SESSION["watershed"] = "IWMP14";
	$year = 2015;	//Pass year for which you want to see the scorecard.

	//Connecting to database
	$host = "localhost"; 
	$user = "postgres"; 
	$pass = "DSS_H@NIH"; 
	$db = "test";
	$port = 5432; 

	$con = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass")or die ("Could not connect to server\n");

	$query = "SELECT MAX(area) FROM landcap WHERE year = ".$year." AND state = '".$_SESSION["state"]."' AND district = '".$_SESSION["district"]."' AND watershed = '".$_SESSION["watershed"]."';";
	$rs = pg_query($con, $query) or die();

	//Calculating land capability grade
	$row = pg_fetch_row($rs);

	$max_area = (float)$row[0];

	$query = "SELECT class FROM landcap WHERE year = ".$year." AND state = '".$_SESSION["state"]."' AND district = '".$_SESSION["district"]."' AND watershed = '".$_SESSION["watershed"]."' AND area = ".$max_area.";";

	$rs = pg_query($con, $query) or die();

	$row = pg_fetch_row($rs);

	$cmp_value = (int)$row[0];
	
	if($cmp_value == 1) {$grade = 'A';}
	elseif($cmp_value == 2 || $cmp_value == 3) {$grade = 'B';}
	elseif($cmp_value == 4 || $cmp_value == 5) {$grade = 'C';}
	elseif($cmp_value == 6 || $cmp_value == 7) {$grade = 'D';}
	else {$grade = 'E';}
	
	//Printing grade
	echo "<h2>".$grade."</h2>";

	//Close connection with database
	pg_close($con);
?>
