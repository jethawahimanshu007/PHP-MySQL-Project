
<?php
$newNode = $_POST["newNode"];
$parent = $_POST["parent"];

$connection = mysqli_connect("localhost","root","","hw1") or die("Error " . mysqli_error($connection));
$sql = "call addNewNode('$newNode','$parent')";
$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

$nodesarray = array();
while($row =mysqli_fetch_assoc($result))
{
$nodesarray[] = $row;
echo $row['name'];
}
  
?>

