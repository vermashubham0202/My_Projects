<?php
session_start();

//echo $_SESSION["state"].'	'.$_SESSION["district"].'	'.$_SESSION["watershed"];

if($_SESSION["mode"] == "view")
{
	//echo $_POST["year"];
	calculateScore($_POST["year"]);
	gradeClassification();
}
else
{
	$year = $_POST["year"];
	foreach ($year as $year){ 
    //echo $year."<br />";
	calculateScore($year);
	}
	gradeClassification();
}

#Conversion of Grades to Scores
function gradeConverter($grade)
{
	if($grade == 'A' or $grade == 'a'){
		return 5;
	}
	elseif($grade == 'B' or $grade == 'b') {
		return 4;
	}
	elseif($grade == 'C' or $grade == 'c') {
		return 3;
	}
	elseif($grade == 'D' or $grade == 'd') {
		return 2;
	}
	elseif($grade == 'E' or $grade == 'e') {
		return 1;
	}
}
function calculateScore($year) {
   // echo $year."passed<br>";
	$table = "ws_".$_SESSION["state"]."_".$_SESSION["district"]."_".$_SESSION["watershed"]."_".$year;
	//echo $table;
	#Connecting to database
	$host = "localhost"; 
	$user = "postgres"; 
	$pass = "DSS_H@NIH"; 
	$db = "test"; 
	$con = pg_connect("host=$host port=5432 dbname=$db user=$user password=$pass")or die ("Could not connect to server\n");
	
	#Selecting Water Quantity data input
	$query = "SELECT * FROM ".$table." WHERE  themes = 'Water Quantity' AND indicators = 'RLFW'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$water_quantity_theme_weight = $row[1];
	$RLFW = $row[3];
	$RLFW_score = gradeConverter($RLFW);
	
	$query = "SELECT * FROM ".$table." WHERE  themes = 'Water Quantity' AND indicators = 'NGR'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$NGR = $row[3];
	$NGR_score = gradeConverter($NGR);
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	#Selecting Water Quality data input
	$query = "SELECT * FROM ".$table." WHERE  themes = 'Water Quality' AND indicators = 'SWQI'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$water_quality_theme_weight = $row[1];
	$SWQI = $row[3];
	$SWQI_score = gradeConverter($SWQI);

	$query = "SELECT * FROM ".$table." WHERE  themes = 'Water Quality' AND indicators = 'GQI'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$GQI = $row[3];
	$GQI_score = gradeConverter($GQI);
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	#Selecting Forest Conditions data input
	$query = "SELECT * FROM ".$table." WHERE  themes = 'Forest Conditions' AND indicators = 'FC'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$forest_conditions_theme_weight = $row[1];
	$FC = $row[3];
	$FC_score = gradeConverter($FC);
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	#Selecting Agricultural Conditions data input
	$query = "SELECT * FROM ".$table." WHERE  themes = 'Agricultural Conditions' AND indicators = 'CDI'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$agricultural_conditions_theme_weight = $row[1];
	$CDI = $row[3];
	$CDI_score = gradeConverter($CDI);

	$query = "SELECT * FROM ".$table." WHERE  themes = 'Agricultural Conditions' AND indicators = 'CI'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$CI = $row[3];
	$CI_score = gradeConverter($CI);
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	#Selecting Soil Conditions data input
	$query = "SELECT * FROM ".$table." WHERE  themes = 'Soil Conditions' AND indicators = 'SD'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$soil_conditions_theme_weight = $row[1];
	$SD = $row[3];
	$SD_score = gradeConverter($SD);

	$query = "SELECT * FROM ".$table." WHERE  themes = 'Soil Conditions' AND indicators = 'SI'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$SI = $row[3];
	$SI_score = gradeConverter($SI);

	$query = "SELECT * FROM ".$table." WHERE  themes = 'Soil Conditions' AND indicators = 'LC'";
	$rs = pg_query($con, $query) or die();//"Cannot execute query: $query\n"
	$row = pg_fetch_row($rs);
	$LC = $row[3];
	$LC_score = gradeConverter($LC);
	
	//echo $RLFW."_".$NGR."_".$SWQI."_".$GQI."_".$FC."_".$CDI."_".$CI."_".$SD."_".$SI."_".$LC."<br>";
	//echo $RLFW_score."_".$NGR_score."_".$SWQI_score."_".$GQI_score."_".$FC_score."_".$CDI_score."_".$CI_score."_".$SD_score."_".$SI_score."_".$LC_score."<br>";
	//echo $water_quantity_theme_weight."_".$water_quality_theme_weight."_".$forest_conditions_theme_weight."_".$agricultural_conditions_theme_weight."_".$soil_conditions_theme_weight;
	
	$water_quantity_theme_score = ($RLFW_score + $NGR_score)/2;
	$water_quality_theme_score = ($SWQI_score + $GQI_score)/2;
	$forest_conditions_theme_score = $FC_score;
	$agricultural_conditions_theme_score = ($CDI_score + $CI_score)/2;
	$soil_conditions_theme_score = ($SD_score + $SI_score + $LC_score)/3;
	
	$water_quantity_final_theme_score = $water_quantity_theme_score * $water_quantity_theme_weight;
	$water_quality_final_theme_score = $water_quality_theme_score * $water_quality_theme_weight;
	$forest_conditions_final_theme_score = $forest_conditions_theme_score * $forest_conditions_theme_weight;
	$agricultural_conditions_final_theme_score = $agricultural_conditions_theme_score * $agricultural_conditions_theme_weight;
	$soil_conditions_final_theme_score = $soil_conditions_theme_score * $soil_conditions_theme_weight;
	
	$total_theme_score = $water_quantity_final_theme_score + $water_quality_final_theme_score + $forest_conditions_final_theme_score + $agricultural_conditions_theme_score + $soil_conditions_final_theme_score;
	$total_theme_weight = $water_quantity_theme_weight + $water_quality_theme_weight + $forest_conditions_theme_weight + $agricultural_conditions_theme_weight + $soil_conditions_theme_weight;
	
	//echo $total_theme_score;
	//echo $total_theme_weight;
	
	$overall_watershed_score = ($total_theme_score / $total_theme_weight) * 20;
	$overall_watershed_score = round($overall_watershed_score,2);
	//echo $overall_watershed_score."<br>";
	//echo watershedStatus($overall_watershed_score)."<br>";
	//echo watershedStatusGrade($overall_watershed_score);
	
	echo '<!DOCTYPE html>
	<html>
	<head>
	<style>
		table, th, td {
			border: 1px solid black;
		}
		td {
			text-align: center;
		}
	</style>
	</head>
	<body>
	<br>
	<h2>Watershed Scorecard of year '.$year.' :</h2>
	<table>
	<tr>
		<th>Themes</th>
		<th>Theme weight</th>
		<th>Indicators</th>
		<th>How to measure?</th>
		<th>Grades Obtained</th>
	</tr>
	<tr>
		<td rowspan="2">Water Quantity</td>
		<td rowspan="2">'.$water_quantity_theme_weight.'</td>
		<td>Runoff Losses from Watershed</td>
		<td>Runoff to Rainfall (SCS CN Method)</td>
		<td>'.$RLFW.'</td>
	</tr>
	<tr>
		<td>Natural Groundwater Recharge</td>
		<td>Recharge to Area (Rainfall Infiltration Method)</td>
		<td>'.$NGR.'</td>
	</tr>
	
	
	<tr>
		<td rowspan="2">Water Quality</td>
		<td rowspan="2">'.$water_quality_theme_weight.'</td>
		<td>Surface Water Quality Index</td>
		<td>NSF Method</td>
		<td>'.$SWQI.'</td>
	</tr>
	<tr>
		<td>Groundwater Quality Index</td>
		<td>Weighted Arithmetic Index Method</td>
		<td>'.$GQI.'</td>
	</tr>
	
	
	<tr>
		<td>Forest Conditions</td>
		<td>'.$forest_conditions_theme_weight.'</td>
		<td>% Forest Cover (Dense and Scrub Forests)</td>
		<td>NDVI Analysis</td>
		<td>'.$FC.'</td>
	</tr>
	
	
	<tr>
		<td rowspan="2">Agricultural Conditions</td>
		<td rowspan="2">'.$agricultural_conditions_theme_weight.'</td>
		<td>Crop Diversification Index</td>
		<td>Gibbs and Martins Method</td>
		<td>'.$CDI.'</td>
	</tr>
	<tr>
		<td>Cropping Intensity</td>
		<td>(Gross Cropped Area / Net Area Sown) * 100</td>
		<td>'.$CI.'</td>
	</tr>
	
	
	<tr>
		<td rowspan="3">Soil Conditions</td>
		<td rowspan="3">'.$soil_conditions_theme_weight.'</td>
		<td>Soil Depth</td>
		<td>GIS</td>
		<td>'.$SD.'</td>
	</tr>
	<tr>
		<td>Soil Irrigability</td>
		<td>GIS</td>
		<td>'.$SI.'</td>
	</tr>
	<tr>
		<td>Land Capability</td>
		<td>GIS</td>
		<td>'.$LC.'</td>
	</tr>
	
	
	<tr>
		<td colspan="4">Overall Watershed Score</td>
		<td>'.$overall_watershed_score.'</td>
	</tr>
	
	<tr>
		<td colspan="4">Overall Watershed Grade</td>
		<td>'.watershedStatusGrade($overall_watershed_score).'</td>
	</tr>
	
	<tr>
		<td colspan="4">Overall Watershed Status</td>
		<td>'.watershedStatus($overall_watershed_score).'</td>
	</tr>
	</table>
	
	<br><br>';

	
	
}

function watershedStatus($score)
{
	if($score <= 20)
	{
			return 'Very Poor';
	}
	elseif($score > 20 and $score <= 40)
	{
			return 'Poor';
	}
	elseif($score > 40 and $score <= 60)
	{
			return 'Good';
	}
	elseif($score > 60 and $score <= 80)
	{
			return 'Very Good';
	}
	elseif($score > 80 and $score <= 100)
	{
			return 'Excellent';
	}
}

function watershedStatusGrade($score)
{
	if($score <= 20)
	{
			return 'E';
	}
	elseif($score > 20 and $score <= 40)
	{
			return 'D';
	}
	elseif($score > 40 and $score <= 60)
	{
			return 'C';
	}
	elseif($score > 60 and $score <= 80)
	{
			return 'B';
	}
	elseif($score > 80 and $score <= 100)
	{
			return 'A';
	}
}

function gradeClassification()
{
	echo '<h4>Watershed Status as per Score and Grade Classification :</h4>
	
	<table>
	<tr>
		<th>Grade</th>
		<th>Class Intervals</th>
		<th>Condition</th>
	</tr>
	
	<tr>
		<td>A</td>
		<td>81 - 100</td>
		<td>Excellent</td>
	</tr>
	
	<tr>
		<td>B</td>
		<td>61 - 80</td>
		<td>Very Good</td>
	</tr>
	
	<tr>
		<td>C</td>
		<td>41 – 60</td>
		<td>Good</td>
	</tr>
	
	<tr>
		<td>D</td>
		<td>21 – 40</td>
		<td>Poor</td>
	</tr>
	
	<tr>
		<td>E</td>
		<td>0 – 20</td>
		<td>Very Poor</td>
	</tr>
	
	</body>
	</html>';
}

?>