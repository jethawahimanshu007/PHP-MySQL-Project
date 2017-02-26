<html>
<head>
  <style>

 .node circle {
   fill: #fff;
   stroke: steelblue;
   stroke-width: 3px;
 }

 .node text { font: 12px sans-serif; }

 .link {
   fill: none;
   stroke: #ccc;
   stroke-width: 2px;
 }
 #div1{
  margin-left:400px;
  margin-top:60px;
  position:absolute;
 }

#div4{
  margin-left:300px;
}
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
 
  @keyframes example {
    0%   {fill: white;}
    100% {fill: yellow;}
}
    </style>
    <script src="http://d3js.org/d3.v3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<div id="div1"></div>

<?php
    //open connection to mysql db

    $connection = mysqli_connect("localhost","root","","hw1") or die("Error " . mysqli_error($connection));


     $sql = "select * from newNodes";
    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

     $nodesarray = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $nodesarray[] = $row;
    }
    $nodesJson= json_encode($nodesarray);
    //echo $nodesJson

    $sql = "SELECT * from params_tbl";
$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
$nodesarray1 = array();
while($row =mysqli_fetch_assoc($result))
{
$AcPoS=$row['AcPoS'];
$AcPoU=$row['AcPoU'];
$AcLoS=$row['AcLoS'];
$AcLoU=$row['AcLocU'];
$FoPoS=$row['FoPoS'];
$FoPoU=$row['ForPoU'];
$ParS=$row['ParS'];
$ParU=$row['ParU'];
}

    
?>
<div id="div4">

Search to Update Ratio
<br>
Actual Pointer:<?php echo $AcPoS;?>/<?php echo $AcPoU;?> &nbsp &nbsp &nbsp

Actual Location:<?php echo $AcLoS;?>/<?php echo $AcLoU;?>  &nbsp &nbsp &nbsp Forwarding Pointer:<?php echo $FoPoS;?>/<?php echo $FoPoU;?> &nbsp &nbsp &nbsp Partition:<?php echo $ParS;?>/<?php echo $ParU;?>
</div>
<?php 
     $dataTest=1; 
    ?>

<script>
    var data1 = '<?=$nodesJson?>';
    var data=JSON.parse(data1);
    
    var dataMap = data.reduce(function(map, node) {
 map[node.name] = node;
 return map;
}, {});
 var treeData = [];
data.forEach(function(node) {
 // add to parent
 var parent = dataMap[node.parent];
 if (parent) {
  // create child array if it doesn't exist
  (parent.children || (parent.children = []))
   // add node to child array
   .push(node);
 } else {
  // parent is null or missing
  treeData.push(node);
 }
});


// ************** Generate the tree diagram  *****************
var margin = {top: 50, right: 120, bottom: 20, left: 50},
 width = 960 - margin.right - margin.left,
 height = 500 - margin.top - margin.bottom;
 
var i = 0;

var tree = d3.layout.tree()
 .size([height, width]);

var diagonal = d3.svg.diagonal()
 .projection(function(d) { return [d.x, d.y]; });

var svg = d3.select("#div1").append("svg")
 .attr("width", width + margin.right + margin.left)
 .attr("height", height + margin.top + margin.bottom)
  .append("g")
 .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

root = treeData[0];
  
update(root);

function update(source) {

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse(),
   links = tree.links(nodes);


  
  // Normalize for fixed-depth.
  nodes.forEach(function(d) { d.y = d.depth * 100; });

  // Declare the nodesâ€¦
  var node = svg.selectAll("g.node")
   .data(nodes, function(d) { return d.id || (d.id = ++i); });

  // Enter the nodes.
  var nodeEnter = node.enter().append("g")
   .attr("class", "node")
   .attr("transform", function(d) { 
    return "translate(" + d.x + "," + d.y + ")"; });

  nodeEnter.append("circle")
   .attr("r", 10)
   .attr("id", function(d){return d.name})
   .style("fill", "#fff");

  nodeEnter.append("text")
   .attr("y", function(d) { 
    return d.children || d._children ? -18 : 18; })
   .attr("dy", ".35em")
   .attr("text-anchor", function(d) { 
    return d.children || d._children ? "end" : "start"; })
   .text(function(d) { return d.name; })
   .style("fill-opacity", 1);

  // Declare the linksâ€¦
  var link = svg.selectAll("path.link")
   .data(links, function(d) { return d.target.id; });

  // Enter the links.
  link.enter().insert("path", "g")
   .attr("class", "link")
   .attr("d", diagonal);

   


   

}


</script>
<div id="div3">
<br>
Add a user:

<form>
    User:  <input type="text" name="nodeNew" id="nodeNew"><br>
    Parent:  <input type="text" name="parentNew" id="parentNew"><br>
</form>

<button id="testBu">Add User</button>

<form>

</form>
  Delete a user:
  <br>User name: <input type="text" name="nodeDelete" id="nodeDelete"><br>
 

</form>
 <button id="deleteButton">Delete User</button>

<br><br>
Actual Pointer
<form>
    Source:  <input type="text" name="foracSource" id="foracSource"><br>
    Destination:  <input type="text" name="foracDest" id="foracDest"><br>
</form>

<button id="foracButton">Search Actual Pointer</button>

<form>
    Source:  <input type="text" name="acLocSource" id="acLocSource"><br>
    Destination:  <input type="text" name="acLocDest" id="acLocDest"><br>
</form>

<button id="acLocButton">Search Actual Location</button>

<form>
    User:  <input type="text" name="updateUserName" id="updateUserName"><br>
    After: <input type="text" name="updateUserAfter" id="updateUserAfter"><br>
</form>

<button id="updateUserButton">Move user</button>

<br><br>

<div id="div2">
Add a link:
<form>
    Link node 1:  <input type="text" name="linkSource" id="linkSource"><br>
    Link node 2: <input type="text" name="linkDest" id="linkDest"><br>
</form>
<button id="createLink">Create link</button>

<!--<button id="linkSiblings">Link siblings for forwarding pointer</button> -->
<br><br>
Forwarding Pointer:
<form>
    Source:  <input type="text" name="forpoSource" id="forpoSource"><br>
    Destination:  <input type="text" name="forpoDest" id="forpoDest"><br>
</form>

<button id="forpoButton">Search Forwarding Pointer</button>
<br><br>
Partition Scheme:
<form>
    Source:  <input type="text" name="parSou" id="parSou"><br>
    Destination:  <input type="text" name="parSou" id="parDes"><br>
</form>

<button id="parButton">Search Partitions</button>

</div>


<script>



$(document).ready(function(){
    $("#testBu").click(function(){

       var newNodeName= $("#nodeNew").val();
       var parentName= $("#parentNew").val();
        $.post("AddNodeBG.php",
        {
          newNode: newNodeName,
          parent: parentName
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
           location.reload();
        });
    });

    $("#deleteButton").click(function(){

       var newNodeName= $("#nodeDelete").val();
       
        $.post("DeleteNodeBG.php",
        {
          newNode: newNodeName
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
           location.reload();
        });
    });

     $("#foracButton").click(function(){

       var $source= $("#foracSource").val();
       var $dest=$("#foracDest").val();
        $.post("newLCA.php",
        {
          source: $source,
          dest: $dest
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
            //alert(data);
            
            var dataArray=data.split(',');
            var arrayLen=dataArray.length;
            for(var i=0;i<dataArray.length;i++)
            {
                $("#"+$.trim(dataArray[i])).css("animation-name","example");
                $("#"+$.trim(dataArray[i])).css("animation-duration","1s");
                $("#"+$.trim(dataArray[i])).css("animation-delay",(i+1)+"s");
            }

          //  $(document).scrollTop(0);

            
        });
        //
    });

     $("#acLocButton").click(function(){

       var $source= $("#acLocSource").val();
       var $dest=$("#acLocDest").val();
        $.post("actualLocation.php",
        {
          source: $source,
          dest: $dest
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
            //alert(data);
            //$(document).scrollTop(100);
            var dataArray=data.split(',');
            
            for(var i=0;i<dataArray.length;i++)
            {
                $("#"+$.trim(dataArray[i])).css("animation-name","example");
                $("#"+$.trim(dataArray[i])).css("animation-duration","1s");
                $("#"+$.trim(dataArray[i])).css("animation-delay",(i+1)+"s");
            }
            
        });
    });

          $("#updateUserButton").click(function(){

       var userName= $("#updateUserName").val();
       var nodeAfter= $("#updateUserAfter").val();
        $.post("updateNodeBG.php",
        {
          updateUserName:userName,
          updateNodeAfter: nodeAfter
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
           location.reload();
        });
    });

$("#linkSiblings").click(function(){
$.post("LinkSiblings.php",
{},
 function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
            location.reload();
           alert("Link between siblings created");
        });

});
         
  $("#createLink").click(function(){

       var linkSourceVal= $("#linkSource").val();
       var linkDestVal= $("#linkDest").val();
        $.post("Link.php",
        {
          linkSource: linkSourceVal,
          linkDest: linkDestVal
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
           location.reload();
           alert("A link has been created");
        });
    }); 


   $("#forpoButton").click(function(){

       var forpoSource= $("#forpoSource").val();
       var forpoDest=$("#forpoDest").val();
        $.post("ForPo.php",
        {
          source: forpoSource,
          dest: forpoDest
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
            //alert(data);
           // $(document).scrollTop(100);
            var dataArray=data.split(',');
            
            for(var i=0;i<dataArray.length;i++)
            {
                $("#"+$.trim(dataArray[i])).css("animation-name","example");
                $("#"+$.trim(dataArray[i])).css("animation-duration","1s");
                $("#"+$.trim(dataArray[i])).css("animation-delay",(i+1)+"s");
            }
            
        });
    });

    $("#parButton").click(function(){

       var parSou= $("#parSou").val();
       var parDes=$("#parDes").val();
        $.post("Par.php",
        {
          source: parSou,
          dest: parDes
        },
        function(data,status){
            //alert(data);

          var dataArray=data.split(';');
            //alert(dataArray);
            
            for(var i=0;i<dataArray.length;i++)
            {
              var dataArray1=dataArray[i].split(",");


                for(var j=0;j<dataArray1.length;j++)
            {
              
                $("#"+$.trim(dataArray1[j])).css("animation-name","example");
                $("#"+$.trim(dataArray1[j])).css("animation-duration","1s");
                $("#"+$.trim(dataArray1[j])).css("animation-delay",(i+1)+"s");
              }
            }
            
            
        });
    });
});

</script>

</body>
</html>