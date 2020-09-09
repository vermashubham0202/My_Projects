<?php

	/*These lines are only for testing purpose.
   	    echo $_POST["state"];
    	echo $_POST["user_type"];
    	echo $_POST["feedback_type"];
	*/

	//Connecting to database
	$host = "localhost"; 
	$user = "postgres"; 
	$pass = "DSS_H@NIH"; 
	$db = "test";
	$port = 5432; 

	$con = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass")or die ("Server issue...(Please inform us at: shubhamvermanih@gmail.com)");

	//Fetching data from feedback table.
	$query = "SELECT * FROM feedbackTable WHERE state IN ('".$_POST["state"]."') AND usertype IN ('".$_POST["user_type"]."') AND feedbacktype IN ('".$_POST["feedback_type"]."');";
	$rs = pg_query($con, $query) or die("Either it's a Database issue or any Feedback might not be present...(Please inform us at: shubhamvermanih@gmail.com)");

	//Check whether any row return by sql query or not.
	
	$total_rows = pg_num_rows($rs);	
	if($total_rows == 0)
	{
		echo "No feedback data available...<br>";
		echo $total_rows . " row(s) returned.<br>";
	}
	
	else {	
	//Presenting data in tabular format.
	echo '<html>
		<body>
			<h2 align="center"><u>Data Preview</u></h2>
			<table align="center" border="1"><tr><th>State</th><th>User Type</th><th>UserID</th><th>Feedback Type</th><th>Feedback/Suggestion</th></tr>';
			
			while ($row = pg_fetch_row($rs)) {
				echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td></tr>';
			}
			echo '</table>';
			
			echo $total_rows . ' row(s) returned.<br>';
		echo '</body>
	</html>';
	}

?>
