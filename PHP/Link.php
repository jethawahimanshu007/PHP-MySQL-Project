<?php
$linkSource = $_POST["linkSource"];
$linkDest= $_POST["linkDest"];

$connection = mysqli_connect("localhost","root","","hw1") or die("Error " . mysqli_error($connection));
$sql = "INSERT IGNORE INTO LINK_TBL VALUES('$linkSource','$linkDest')";
$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

$sql = "INSERT IGNORE INTO LINK_TBL VALUES('$linkDest','$linkSource')";
$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

?>