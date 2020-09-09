<?php
	$max_year = 2015;	//Pass year for which you want to see the scorecard.
	$min_year = ($max_year - 30);	//Move 30 years before the highest datum year.
	
	$runoff_rainfall = array();	//This array is going to store runoff/rainfall for each year.

	//Connecting to database
	$host = "localhost"; 
	$user = "postgres"; 
	$pass = "DSS_H@NIH"; 
	$db = "test";
	$port = 5432; 

	$con = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass")or die ("Could not connect to server\n");

	//Selecting input data
	$query = "SELECT * FROM runoff WHERE years BETWEEN ".$min_year." AND ".$max_year.";";
	$rs = pg_query($con, $query) or die();

	//Calculating runoff/rainfall for each year and inserting the result into array $runoff_rainfall[]
	while ($row = pg_fetch_row($rs)) {
		$runoff_rainfall[] = round(($row[2]/$row[1]),2);
	}

	/*Value of $max_year (Later it will be used to find the grade).
	  Always 31st value (according to array value at 30th position) 
	  will be the value for which we need to find the grade.
	  We are storing this value before sorting array.*/
	$cmp_value = $runoff_rainfall[($max_year - $min_year)];
	
	//Sorting $runoff_rainfall array in ascending order
	sort($runoff_rainfall);
	
	//Total number of elements in array
	$arrlength = count($runoff_rainfall);

	/*Calculating quartile positions for given set of data.
	  Remember that the method coded here is only for odd number of elements.*/
	/*This method is not going for work if number of elements are odd in number.
	  As per the requirement of this tool only odd number of elements considered.*/
	$position_q1 = (($arrlength + 1) * 0.25) - 1;	// -1 because array ($runoff_rainfall[]) index starts from 0.
	$position_q2 = (($arrlength + 1) * 0.5) - 1;
	$position_q3 = (($arrlength + 1) * 0.75) - 1;

	$Q1 = $runoff_rainfall[$position_q1];
	$Q2 = $runoff_rainfall[$position_q2];
	$Q3 = $runoff_rainfall[$position_q3];

	if($cmp_value < $Q1){
		$grade = 'A';	//This variable is going to hold grade value.
	}
	elseif($cmp_value >= $Q1 && $cmp_value < $Q2){
		$grade = 'B';
	}
	elseif($cmp_value >= $Q2 && $cmp_value < $Q3){
		$grade = 'C';
	}
	else{
		$grade = 'D';
	}
	
	//Printing grade
	echo "<h2>".$grade."</h2>";

	//Close connection with database
	pg_close($con);
?>
