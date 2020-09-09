<?php

    //Start the session
    session_start();
	
    /*These lines are only for testing purpose
    echo $_SESSION["user_type"]."<br/>";
    echo $_SESSION["user_id"]."<br/>";
    echo $_SESSION["state"]."<br/>";
    */

    //Connecting to database (Change these values accordingly)
    $host = "localhost";
    $port = 5432;   //Postgresql's port
    $user = "postgres";
    $pass = "DSS_H@NIH";
    $db = "test";

    $con = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass")or die ("Server issue...(Please inform us at: shubhamvermanih@gmail.com)");

    //Check feedback table exist or not (if not then create it)
    $check_query = "DESCRIBE feedbackTable";
    if(pg_query($con, $check_query))
    {
	    //Table exist
    }
    else {
      //Create feedback table (It is not present)
	    $query = "CREATE TABLE feedbackTable(state text,userType text,userID text,feedbackType text,userText text);";
	    pg_query($con , $query);
    }

    //Replacing apostrophes s(') from user text. Otherwise it is going to create problem for insert query below.
    $text = str_replace("'","&#39;",$_POST['user_text']);

    //Inserting feedback into database
    $query = "INSERT INTO feedbackTable VALUES('".$_SESSION['state']."','".$_SESSION['user_type']."','".$_SESSION['user_id']."','".$_POST['feedback_type']."','".$text."')";
    pg_query($con , $query) or die("Database issue...(Please inform us at: shubhamvermanih@gmail.com)");
    echo "Your data has been saved successfully!";

    //Close connection with database
    pg_close($con);

?>
