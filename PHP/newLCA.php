
<?php
$source = $_POST["source"];
$dest = $_POST["dest"];

$connection = mysqli_connect("localhost","root","","hw1") or die("Error " . mysqli_error($connection));
$sql = "call tempListOfNewNodes('$source','$dest')";
$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
$nodesarray = array();
while($row =mysqli_fetch_assoc($result))
{
$nodesarray[] = $row;
echo $row['name'];
}
  
?>

