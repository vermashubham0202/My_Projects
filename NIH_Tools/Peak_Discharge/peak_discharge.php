<?php

//User's input
$state = "cg";
$district = "kanker";
$watershed = "iwmp-15";

#Connecting to database
$host = "localhost"; 
$user = "postgres"; 
$pass = "DSS_H@NIH"; 
$db = "test"; 
$con = pg_connect("host=$host port=5432 dbname=$db user=$user password=$pass")or die ("Could not connect to server\n");

#Selecting data input
$preview_data_query = "SELECT * FROM ".$state."_".$district."_peak_data WHERE watershed = '".$watershed."'";
$rs = pg_query($con, $preview_data_query) or die();//"Cannot execute query: $preview_data_query\n"

#Calculating Peak data		
echo '<h2 align="center"><u>Peak Discharge of '.$watershed.':</u></h2>';
echo '<table align="center"  border="1"><tr><th>Sub-Watershed</th><th>C</th><th>I (mm/hr)</th><th>A(ha)</th><th>Qp (cumecs)</th></tr>';
			while ($row = pg_fetch_row($rs)) {
				$qp =($row[2]*$row[3]*$row[4])/360;
				echo '<tr><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.round($qp,2).'</td></tr>';
			}
echo '</table>';

#close connection with database
pg_close($con);

?>