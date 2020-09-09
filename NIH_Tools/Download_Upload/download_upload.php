<?php
$cookie_data_freq = "data_freq";
$cookie_value = $_POST["data_freq"];
setcookie($cookie_data_freq, $cookie_value, time() + (86400), "/"); // 86400 = 1 day

$cookie_data_from = "data_from";
$cookie_value = (string)date('Ymd', strtotime($_POST["data_from"]));
setcookie($cookie_data_from, $cookie_value, time() + (86400), "/"); // 86400 = 1 day

$cookie_data_to = "data_to";
$cookie_value = (string)date('Ymd', strtotime($_POST["data_to"]));
setcookie($cookie_data_to, $cookie_value, time() + (86400), "/"); // 86400 = 1 day

$cookie_data_source = "data_source";
$cookie_value = $_POST["data_source"];
setcookie($cookie_data_source, $cookie_value, time() + (86400), "/"); // 86400 = 1 day
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<!--Page Title-->
	<title>Download-Upload</title>
	
	<meta charset="UTF-8">
	<meta name="description" content="Download forms and upload data">
	
	<!--keywords for search engines-->
	<meta name="keywords" content="Download,Upload,NIH Forms">
	
	<!--viewport element gives the browser instructions on how to control the page's dimensions and scaling.
	width=device-width part sets the width of the page to follow the screen-width of the device (which will vary depending on the device).
	initial-scale=1.0 part sets the initial zoom level when the page is first loaded by the browser.-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<h2 style="text-align:center";><u>NIH Download/Upload Files Page</u></h2>
	<hr>
	
	<!--################################################Download file code###################################################-->
	
		<p>
			Download file1 :
			<a href="download_files/file.csv">
				<img src="img_files/Download-icon.png" alt="Download" style="width:42px;height:42px;border:0;">
			</a>
		</p>
		
	<!--################################################Upload file form######################################################-->
	
	<form enctype="multipart/form-data" action="download_upload.php" method="POST">
		<p>Upload your file1 :</p>
		<input type="file" name="uploaded_file"></input><br />
		<input type="submit" value="Upload"></input>
	</form>
	
	<hr>
</body>
</html>


<?php
if(!isset($_COOKIE[$cookie_data_freq])) {
    //echo "Cookie named '" . $cookie_data_freq . "' is not set!";
} else {
    //echo "Cookie '" . $cookie_data_freq . "' is set!<br>";
    //echo "Value is: " . $_COOKIE[$cookie_data_freq];
}
//echo "<br>";
if(!isset($_COOKIE[$cookie_data_from])) {
    //echo "Cookie named '" . $cookie_data_from . "' is not set!";
} else {
   // echo "Cookie '" . $cookie_data_from . "' is set!<br>";
    //echo "Value is: " . $_COOKIE[$cookie_data_from];
}

//echo "<br>";

if(!isset($_COOKIE[$cookie_data_to])) {
   // echo "Cookie named '" . $cookie_data_to . "' is not set!";
} else {
   // echo "Cookie '" . $cookie_data_to . "' is set!<br>";
   // echo "Value is: " . $_COOKIE[$cookie_data_to];
}

//echo "<br>";
if(!isset($_COOKIE[$cookie_data_source])) {
    //echo "Cookie named '" . $cookie_data_source . "' is not set!";
} else {
    //echo "Cookie '" . $cookie_data_source . "' is set!<br>";
    //echo "Value is: " . $_COOKIE[$cookie_data_source];
}

//echo "<br>";
?>

<?php

	//User's input


	/*echo "<br>State : ".$_SESSION["state"];
	echo "<br>District : ".$_SESSION["district"];
	echo "<br>Tool: ".$_SESSION["tool"];
	echo "<br>User Name: ".$_SESSION["user_name"]."<br>";
	*/
	echo '<head><style> h2 {text-align:center;} table, th, td {border: 1px solid black;} .scrollable {height: 500px; overflow-y: auto;}</style></head>';
	####################################Upload file code#######################################
	if(!empty($_FILES['uploaded_file']))
		{
			$target_dir = "upload_files/";
			date_default_timezone_set("Asia/Calcutta");
			$timestamp = date("Ymdhis");
			$timestamped_file_name = $_SESSION["state"]."_".$_SESSION["district"]."_".$_SESSION["tool"]."_".$_SESSION["user_name"]."_".$_COOKIE[$cookie_data_freq]."_DF".$_COOKIE[$cookie_data_from]."_DT".$_COOKIE[$cookie_data_to]."_".$timestamp."_". basename( $_FILES['uploaded_file']['name']);
			$target_file = $target_dir .$timestamped_file_name;
			$uploadOk = 1;
			$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
			#Check if file already exists
			if (file_exists($target_file)) {
				echo "Sorry, file already exists!<br/>";
				$uploadOk = 0;
			}

			#Check file size
			if ($_FILES['uploaded_file']['size'] > 2000000) {
				echo "Sorry, your file is too large!<br/>";
				$uploadOk = 0;
			}

			#Check if file is .csv file or not
			if($FileType != "csv") {
				echo "Sorry, only CSV files are allowed!<br/>";
				$uploadOk = 0;
			}

			#Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded!<br/>";
			}

			#if everything is ok, try to upload file
			else {
				if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $target_file)) {
			
				########################################inserting into database code########################################
					#open csv file in read mode
					if(!file_exists($target_file)) {
						die("Your file was corrupted! Please re-upload your file.");
					} else {
					$file = fopen($target_file,"r");
					}
					
					#Connecting to database
					$host = "localhost"; 
					$user = "postgres"; 
					$pass = "DSS_H@NIH"; 
					$db = "test"; 
					$con = pg_connect("host=$host port=5432 dbname=$db user=$user password=$pass")or die ("Could not connect to server\n");
					
					
					#Later used for file format modified or not
					$data = fgetcsv($file);
					
					#check index table exist or not (if not then create it)
					$check_query = "DESCRIBE dataindex";
					if(pg_query($con, $check_query))
					{
						//echo "Table Exist";
					}
					else{
					$query_index = "CREATE TABLE dataindex(state text,district text,tool text,username text,frequency text,yearfrom bigint,yearto bigint,timestamp bigint,datasource text);";
					pg_query($con , $query_index); //or die("Cannot execute query: $query_index\n");
					}
					
					#inserting records in index
					$query_index = "INSERT INTO dataindex VALUES('".$_SESSION['state']."','".$_SESSION['district']."','".$_SESSION['tool']."','".$_SESSION['user_name']."','".$_COOKIE[$cookie_data_freq]."',".$_COOKIE[$cookie_data_from].",".$_COOKIE[$cookie_data_to].",".$timestamp.",'".$_COOKIE[$cookie_data_source]."')";
					pg_query($con , $query_index) or die("Cannot execute query index: $query_index\n");
					
					#creating table of uploded file in our database
					$table_name = chop($timestamped_file_name,".csv");
					$query = "CREATE TABLE ".$table_name."(first_name VARCHAR(30),last_name VARCHAR(30),user_id VARCHAR(20),password VARCHAR(20));";
					pg_query($con , $query) or die("Cannot execute query: $query\n");
					
					#inserting file data into database
					while(! feof($file))
					{
						$data = fgetcsv($file);
						$str = $data[0]."','".$data[1]."','".$data[2]."','".$data[3];
						$query = "INSERT INTO ".$table_name." VALUES ('". $str."')";
						pg_query($con , $query) or die("Cannot execute query: $query\n");
					}

					#close connection with file
					fclose($file);

					
				##############################################################################################################
			
					echo "The file ".  basename( $_FILES['uploaded_file']['name']). " has been uploaded!";
				} 
				else{
					echo "There was an error uploading the file, please try again!";
				}
			}
			echo "<hr/>";
			
			#########################################Preview data code###################################################
			$preview_data_query = "SELECT * FROM ".$table_name;
			$rs = pg_query($con, $preview_data_query) or die();//"Cannot execute query: $preview_data_query\n"
			
			echo '<h2><u>Data Preview</u></h2>';
			echo '<div class="scrollable">';
			echo '<table align="center"><tr><th>First Name</th><th>Last Name</th><th>UserID</th><th>Password</th></tr>';
			
			while ($row = pg_fetch_row($rs)) {
				echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
			}
			echo '</table>';
			echo '</div>';
			###############################################################################################################
			
			
			#close connection with database
			pg_close($con);
		} 
?>