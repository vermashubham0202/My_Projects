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
	$query = "SELECT * FROM groundwater WHERE years = ".$year.";";
	$rs = pg_query($con, $query) or die();

	$row = pg_fetch_row($rs);
	$area = round($row[1],2);
	$recharge = round($row[2],2);

	//Calculating Recharge/Area percentage	
	$cmp_value = round(($recharge / $area)*100);

	if($cmp_value < 10){
		$grade = 'D';	//This variable is going to hold grade value.
	}
	elseif($cmp_value >= 10 && $cmp_value < 30){
		$grade = 'C';
	}
	elseif($cmp_value >= 30 && $cmp_value < 50){
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
