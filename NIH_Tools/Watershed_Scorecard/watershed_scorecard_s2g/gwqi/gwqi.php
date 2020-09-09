<?php
	$year = 2015;	//Pass year for which you want to see the scorecard.

	//Connecting to database
	$host = "localhost"; 
	$user = "postgres"; 
	$pass = "DSS_H@NIH"; 
	$db = "test";
	$port = 5432; 

	$con = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass")or die ("Could not connect to server\n");

	//Selecting input data
	$query = "SELECT * FROM gwqi WHERE years = ".$year.";";
	$rs = pg_query($con, $query) or die();

	$row = pg_fetch_row($rs);
	$cmp_value = round($row[1]);	//This variable is going to hold WQI value for the given year.

	if($cmp_value <= 50){
		$grade = 'A';	//This variable is going to hold grade value.
	}
	elseif($cmp_value >= 51 && $cmp_value <= 100){
		$grade = 'B';
	}
	elseif($cmp_value >= 101 && $cmp_value <= 150){
		$grade = 'C';
	}
	elseif($cmp_value >= 151 && $cmp_value <= 200){
		$grade = 'D';
	}
	elseif($cmp_value >= 201 && $cmp_value <= 250){
		$grade = 'E';
	}
	else{
		$grade = 'F';
	}
	
	//Printing grade
	echo "<h2>".$grade."</h2>";

	//Close connection with database
	pg_close($con);
?>
