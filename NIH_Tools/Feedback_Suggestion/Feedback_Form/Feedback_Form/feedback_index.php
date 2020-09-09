<?php

    //Start the session
    session_start();

    //Data required by session variables
    $_SESSION["user_type"] = "admin";
    $_SESSION["user_id"] = "admin@gmail.com";
    $_SESSION["state"] = "CG";

?>

<html>
    <head>
    
    </head>
    <body>
        <h2>Feedback:</h2>
        <form action="feedback_upload.php" method="POST">
	        <!--Options for different issues-->
	        <input type="radio" name="feedback_type" value="error_report" checked> Error Reporting<br/>
	        <input type="radio" name="feedback_type" value="data_issue"> Data Issue<br/>
	        <input type="radio" name="feedback_type" value="tool_issue"> Tool Issue<br/>
	        <input type="radio" name="feedback_type" value="other_issue"> Other Issue<br/>
	        <input type="radio" name="feedback_type" value="feedback_suggestion"> Feedback/Suggestion<br/><br/>
	        <textarea name="user_text" rows="8" cols="65" placeholder="Write your comments here..."></textarea><br/><br/>
	        <input type="submit" value="Submit">
        </form> 
    </body>
</html>
