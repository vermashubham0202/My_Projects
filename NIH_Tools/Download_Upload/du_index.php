<?php
session_start();
?>
<?php
$_SESSION["state"] = "CG";
$_SESSION["district"] = "KN";
$_SESSION["tool"] = "ET";
$_SESSION["user_name"] = "user1";

date_default_timezone_set("Asia/Calcutta");
$year = date("Y-m-d");

echo'<html>
<head>
<style>
	#wrapper .text {
		position:relative;
		bottom:30px;
		left:0px;
		visibility:hidden;
	}

	#wrapper:hover .text {
		visibility:visible;
	}
</style>
</head>
<body>
	<h2 style="text-align:center;"><u>Add Data Details</u></h2>
	
	<form action="download_upload.php" method="POST">
		<h3>Data Frequency:</h3>
  		<input type="radio" name="data_freq" value="daily" checked> Daily <br>
  		<input type="radio" name="data_freq" value="monthly"> Monthly <br>
  		<input type="radio" name="data_freq" value="yearly"> Yearly <br>

		<h3>Data range:</h3>
		From: <input type="date" name="data_from" max="'.$year.'" value="echo date(&#39;Y-m-d&#39;);"> 
		To: <input type="date" name="data_to" max="'.$year.'" value="echo date(&#39;Y-m-d&#39;);">	<br><br>

		<h3>Data source:</h3> 
		<input type="text" name="data_source" size="40"><br><br>
	
		<input type="submit" value="Submit">
	</form>
	<div id="wrapper">
		<img src="img_files/instruct.jpg" alt="instructions" width="40" height="45" class="hover">&nbsp;
		<ul class="text">
			<li>You can&#39;t add future date&#39;s data.</li>
			<li>If you are uploading daily data (eg. only 01/01/2019) then select same date in &#39;Form&#39; and &#39;To&#39; field.</li>
			<li>You should provide a valid data source, otherwise your data will be discarded.</li>
		</ul>
	</div>
</body>
</html>';
?>
