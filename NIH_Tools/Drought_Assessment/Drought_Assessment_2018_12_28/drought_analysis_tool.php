<?php
//Fetch from user's input
$district = $_GET["district"];//"kanker_drought_data";
$drought_type = $_GET["drought_type"]; //Annual or Monthly
$min_year = $_GET["new_min_year"];
$max_year = $_GET["new_max_year"];
$min_month = (int)date('m', strtotime($_GET["new_min_month"]));
$max_month = (int)date('m', strtotime($_GET["new_max_month"]));
$graph_type = $_GET["graph_type"]; //bar or scatter
//Calculating total number of years and months
$total_years = ($max_year - $min_year) + 1;
$total_months = ($max_month - $min_month) + 1;

//Used arrays
$average_rainfall = array();
$total_rainfall = array();
$rainfall_departure = array();

//Database connectivity
$host = "localhost"; 
$user = "postgres"; 
$pass = "DSS_H@NIH"; 
$db = "test"; 
$con = pg_connect("host=$host dbname=$db user=$user password=$pass")or die ("Could not connect to server\n");

//Query to fetch data from database
$query = 'SELECT * FROM '.$district.' WHERE year BETWEEN '.$min_year.' AND '.$max_year;
$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");

//Calculating Total rainfall & Average rainfall for each year according to drought type
if($drought_type == "Annual") {
	while ($row = pg_fetch_row($rs)) {
		$sum = $row[1] + $row[2] + $row[3] + $row[4] + $row[5] + $row[6] + $row[7] + $row[8] + $row[9] + $row[10] + $row[11] + $row[12];
		$average_rainfall[] = $sum/12;
		$total_rainfall[] = $sum;
	}
}
elseif($drought_type == "Monthly") {
	while ($row = pg_fetch_row($rs)) {
		$sum = 0;
		for($i = $min_month ; $i <= $max_month ; $i++) {
			$sum = $sum + $row[$i];
		}
		$average_rainfall[] = $sum/$total_months;
		$total_rainfall[] = $sum;
	}
}

//Calculating mean rainfall and 75% of mean rainfall
$mean_rainfall = array_sum($total_rainfall)/$total_years;
$modified_mean_rainfall = 0.75 * $mean_rainfall;


//Identifing drought years
echo "<h1>Drought Years:</h1>";
for($i = 0; $i < $total_years; $i++) {
	if($total_rainfall[$i] < $modified_mean_rainfall) {
		echo ($min_year +$i)."<br/>";
	}
}

//Calculating rainfall departure
for($i = 0; $i < $total_years ; $i++) {
	$rainfall_departure[$i] = (($total_rainfall[$i] - $mean_rainfall)/$mean_rainfall)*100;
}

//Drought years counter
$count_drought = 0;

//Identifing drought severity
echo "<h1>Drought Severity:</h1>";
for($i = 0; $i < $total_years; $i++) {
	if($rainfall_departure[$i] < (-20) && $rainfall_departure[$i] >= (-25))
		{
			echo ($min_year + $i).'	-	Mild Drought<br/>';
			$count_drought++;
		}
		if($rainfall_departure[$i] < (-25) && $rainfall_departure[$i] >= (-50))
		{
			echo ($min_year + $i).'	-	Moderate Drought<br/>';
			$count_drought++;
		}
		if($rainfall_departure[$i] < (-50) && $rainfall_departure[$i] >= (-75))
		{
			echo ($min_year + $i).'	-	Severe Drought<br/>';
			$count_drought++;
		}
}

//Calculating drought probability and drought frequency
$drought_events_probability = $count_drought / ($total_years + 1);
$drought_events_frequency = (1 / $drought_events_probability);

echo "<br/>Drought Probability : ".$drought_events_probability."<br/>";
echo "Drought Frequeny : ".floor($drought_events_frequency)." <years>";

//Identifing minimum and maximum rainfall
$numbers = $total_rainfall;
$min_rainfall = min($numbers);
$max_rainfall = max($numbers);
echo "<br/>Minimum Rainfall (".$min_year."-".$max_year.") : ".$min_rainfall." (".($min_year + (array_search($min_rainfall,$numbers))).")";
echo "<br/>Maximum Rainfall (".$min_year."-".$max_year.") : ".$max_rainfall." (".($min_year + (array_search($max_rainfall,$numbers))).")";

//Function to calculate the standard deviation of array elements 
function Stand_Deviation($arr) 
{ 
    $num_of_elements = count($arr); 
    $variance = 0.0; 
          
    // calculating mean using array_sum() method 
    $average = array_sum($arr)/$num_of_elements; 
          
    foreach($arr as $i) 
    { 
		// sum of squares of differences between all numbers and means. 
            $variance += pow(($i - $average), 2); 
    } 
        return (float)sqrt($variance/$num_of_elements); 
} 

//Calculating standard deviation
echo "<br/>Standard Deviation : ";
$standard_deviation = Stand_Deviation($total_rainfall);
print_r($standard_deviation);

//Calculating variance
echo "<br/>Variance : ";
print_r(pow($standard_deviation,2));

###################################################################################################################

echo '<html>';
echo '<head>';
  echo '<script src="js_files/plotly-latest.min.js"></script>';
echo "</head>";
echo "<body>";
echo "<br/>";
################ X-Axis Years ######################
$x_axis_years = "x: [";
for($i = $min_year ; $i <= $max_year ; $i++)
{
	if($i == $max_year)
	{
		$x_axis_years .= $i;
	}
	else
	{
		$x_axis_years .= $i.", ";
	}
}
$x_axis_years .= "],";
###################################################

################# Y-Axis Total rainfall ################
$y_axis_total_rainfall = "y: [";
for($i = 0; $i < $total_years; $i++)
{
	if($i == ($total_years - 1))
	{
		$y_axis_total_rainfall .= $total_rainfall[$i];
	}
	else 
	{
		$y_axis_total_rainfall .= $total_rainfall[$i].", ";
	}
}
$y_axis_total_rainfall .= "],";
###############################################################

################## Y-Axis Rainfall Departure ##################
$y_trace2 = "y: [";
$y_trace3 = "y: [";
$y_axis_rainfall_departure = "y: [";
for($i = 0; $i < $total_years; $i++)
{
	if($i == ($total_years - 1))
	{
		$y_axis_rainfall_departure .= $rainfall_departure[$i];
		$y_trace2 .= (-20);
		$y_trace3 .= (-25);
	}
	else 
	{
		$y_axis_rainfall_departure .= $rainfall_departure[$i].", ";
		$y_trace2 .= (-20).", ";
		$y_trace3 .= (-25).", ";
	}
}
$y_axis_rainfall_departure .= "],";
$y_trace2 .= "],";
$y_trace3 .= "],";
###############################################################
echo "<h2 style='text-align:center'>Total ".$drought_type." Rainfall</h2>";
echo '<div id="myDiv">';
		
		echo '<script>';
		#########Total rainfall graph#############
			echo 'var data = [{';
				echo $x_axis_years;
				echo $y_axis_total_rainfall;
				echo "type: '".$graph_type."'";
			echo "}];";

			echo "Plotly.newPlot('myDiv', data);";
		#################################################
		echo "</script>";
echo "</div>";

echo "<h2 style='text-align:center'>".$drought_type." Rainfall Departure(%)</h2>";
echo '<div id="myDiv2">';

		echo '<script>';
		#########Rainfall Departure graph#############
			echo 'var trace1 = {';
				echo $x_axis_years;
				echo $y_axis_rainfall_departure;
				echo "type: '".$graph_type."'";
				echo "};";
			
			echo 'var trace2 = {';
				echo $x_axis_years;
				echo $y_trace2;
				echo "type: 'scatter'";
				echo "};";
				
			echo 'var trace3 = {';
				echo $x_axis_years;
				echo $y_trace3;
				echo "type: 'scatter'";
				echo "};";
				
				echo "var data2 = [trace1, trace2,trace3];";

			echo "Plotly.newPlot('myDiv2', data2);";
		##############################################
		echo "</script>";
	echo "</div>";
	echo "</body>";
echo "</html>";
###################################################################################################################
pg_close($con);	

?>
