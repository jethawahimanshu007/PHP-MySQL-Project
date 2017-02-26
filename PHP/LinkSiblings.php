
<?php
$connection = mysqli_connect("localhost","root","","hw1") or die("Error " . mysqli_error($connection));
$sql = "call siblings('1')";
$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
?>

