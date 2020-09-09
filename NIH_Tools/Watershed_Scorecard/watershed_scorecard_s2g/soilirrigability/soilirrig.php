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

	// We consider the maximum area coverage of a parituclar soil Irrigability classification and grade as per that
	$query = "SELECT MAX(area) FROM soilirrig WHERE year = ".$year." AND state = '".$_SESSION["state"]."' AND district = '".$_SESSION["district"]."' AND watershed = '".$_SESSION["watershed"]."';";
	$rs = pg_query($con, $query) or die();

	//Calculating soil irrigability grade
	$row = pg_fetch_row($rs);

	$max_area = (float)$row[0];

	$query = "SELECT series FROM soilirrig WHERE year = ".$year." AND state = '".$_SESSION["state"]."' AND district = '".$_SESSION["district"]."' AND watershed = '".$_SESSION["watershed"]."' AND area = '".$max_area."';";
	$rs = pg_query($con, $query) or die();

	$row = pg_fetch_row($rs);

	$grade = $row[0];
	
	//Printing grade
	echo "<h2>".$grade."</h2>";

	//Close connection with database
	pg_close($con);
?>
