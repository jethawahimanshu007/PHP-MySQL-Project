
<?php
$updateUserName = $_POST["updateUserName"];
$updateNodeAfter= $_POST["updateNodeAfter"];

$connection = mysqli_connect("localhost","root","","hw1") or die("Error " . mysqli_error($connection));
$sql = "call deleteNewNode('$updateUserName')";
$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
$sql = "call addNewNode('$updateUserName','$updateNodeAfter')";
 $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection)); 
 $sql = "call LCA('$updateUserName','$updateNodeAfter')";
 $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection)); 
?>

