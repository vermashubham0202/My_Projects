<?php
//Database connectivity
$district = "kanker_drought_data"; //change according to input
$host = "localhost"; 
$user = "postgres"; 
$pass = "DSS_H@NIH"; 
$db = "test"; 
$con = pg_connect("host=$host dbname=$db user=$user password=$pass")or die ("Could not connect to server\n");

//Query to fetch data from database
$query = 'SELECT MIN(year),MAX(year) FROM '.$district.';';
$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
$row = pg_fetch_row($rs);
$min_year = $row[0]; 	//minimum year in $row[0]
$max_year = $row[1];	//maximum year in $row[1]
pg_close($con);	

echo '<html>
<head>
	<title>Drought Tool</title>
	<!--Plugin CSS file with desired skin-->
	<link rel="stylesheet" href="css_files/ion.rangeSlider.min.css"/>
    
	<!--jQuery-->
	<script src="js_files/jquery.min.js"></script>
    
	<!--Plugin JavaScript file-->
	<script src="js_files/ion.rangeSlider.min.js"></script>

	<style>
	body {
  		font-family: arial;
	}
	.hide {
  		display: none;
	}
	p {
  		font-weight: bold;
	}
	</style>
</head>
<body>
<form action="drought_analysis_tool.php" method="get"><!--drought_analysis_tool.php or test.php-->
	
	<b>Select drought assessment type:</b><br/>
	<input type="radio" name="drought_type" value="Annual" onclick="show1();"> Annual assessment<br/>
	<input type="radio" name="drought_type" value="Monthly" onclick="show2();"> Monthly assessment<br/><br/>
	
	<input type="text" name="district" value="'.$district.'" class="hide">

	
	<div id="div1" class="hide">
	<b>Select year range:</b><br/>
	<div id="select_years">
    	<!--<input class="input-range" type="range" value="250" min="1" max="500">
    	<span class="range-value"></span>-->
	<input type="text" name="new_min_year" id="new_min_year">
	<input type="text" name="new_max_year" id="new_max_year">
		<script>
    			$("#select_years").ionRangeSlider({
        			type: "double",
				skin: "modern",
        			grid: true,
        			//from: my_from,
        			//to: my_to,
        			//values: custom_values
				min: '.$min_year.',
				max: '.$max_year.',
				step: 1
    			});
	
			var new_min_year = '.$min_year.';
			var new_max_year = '.$max_year.';
			document.getElementById("new_min_year").value = new_min_year;
			document.getElementById("new_max_year").value = new_max_year;

			$("#select_years").on("change", function () {
				var $this = $(this);
				var value = $this.prop("value").split(";");
				var new_min_year = value[0];
				var new_max_year = value[1];
				document.getElementById("new_min_year").value = new_min_year;
				document.getElementById("new_max_year").value = new_max_year;
			});
		</script>
	</div>
	</div>
	<br/>

	
	<div id="div2" class="hide">
	<b>Select month range:</b><br/>
	<div id="select_months">
    	<!--<input class="input-range" type="range" value="250" min="1" max="500">
    	<span class="range-value"></span>-->
	<input type="text" name="new_min_month" id="new_min_month">
	<input type="text" name="new_max_month" id="new_max_month">

		<script>
			var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
			var frm = "Jan";
			var to = "Dec";
  			$("#select_months").ionRangeSlider({
        			grid: true,
				type: "double",
				skin: "modern",
        			from: frm,
				to: to,
        			values: months
    			});


			var new_min_month = frm;
			var new_max_month = to;
			document.getElementById("new_min_month").value = new_min_month;
			document.getElementById("new_max_month").value = new_max_month;

			$("#select_months").on("change", function () {
				var $this = $(this);
				var value = $this.prop("value").split(";");
				var new_min_month = value[0];
				var new_max_month = value[1];
				document.getElementById("new_min_month").value = new_min_month;
				document.getElementById("new_max_month").value = new_max_month;
			});

		</script>

	</div>
		
			
	</div>

	<br/>


	<div id="div3" class="hide">

		<b>Select graph type:</b><br/>
		<input type="radio" name="graph_type" value="bar" checked> Bar Graph<br/>
		<input type="radio" name="graph_type" value="line"> Line Graph<br/><br/>

		<input value="submit" type="submit">
	</div> 
</form> 


<script>
	function show1(){
		document.getElementById("div1").style.display ="block";
  		document.getElementById("div2").style.display ="none";
		document.getElementById("div3").style.display ="block";
	}
	function show2(){
  		document.getElementById("div1").style.display ="block";
  		document.getElementById("div2").style.display ="block";
		document.getElementById("div3").style.display ="block";
	}
</script>

</body>
</html>';
?>
