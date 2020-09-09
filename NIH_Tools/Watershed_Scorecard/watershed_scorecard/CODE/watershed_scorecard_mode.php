<?php
session_start();
$_SESSION["state"] = "cg";
$_SESSION["district"] = "kanker";
$_SESSION["watershed"] = "iwmp_14";

echo '<!DOCTYPE html>
<html>
<body>

<h2>Select tool mode:</h2>

<form action="select_year.php" method="post">
  <input type="radio" name="mode" value="view" checked> View mode
  <input type="radio" name="mode" value="comparison"> Comparison mode<br><br>
  <input type="submit" value="Submit">
</form> 

</body>
</html>';
?>