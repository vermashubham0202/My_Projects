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

	//We consider the maximum area coverage of a parituclar soil depth and grade as per that
	$query = "SELECT MAX(area) FROM soildepth WHERE year = ".$year." AND state = '".$_SESSION["state"]."' AND district = '".$_SESSION["district"]."' AND watershed = '".$_SESSION["watershed"]."';";
	$rs = pg_query($con, $query) or die();

	//Calculating soil depth grade
	$row = pg_fetch_row($rs);
	$cmp_value = (float)$row[0];

	if($cmp_value < 10){
		$grade = 'E';	//This variable is going to hold grade value.
	}
	elseif($cmp_value >= 10 && $cmp_value < 50){
		$grade = 'D';
	}
	elseif($cmp_value >= 50 && $cmp_value < 75){
		$grade = 'C';
	}
	elseif($cmp_value >= 75 && $cmp_value < 150){
		$grade = 'B';
	}
	else{
		$grade = 'A';
	}
	
	//Printing grade
	echo "<h2>".$grade."</h2>";

	//Close connection with database
	pg_close($con);
?>
