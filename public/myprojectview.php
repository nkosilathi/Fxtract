<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('file_uploads','on');
require_once('../includes/initialize.php');

// ini_set('display_errors','on');
//ini_set('file_uploads','on');
//ini_set('upload_temp_dir','/home/digitalincentives/www/loginregister/uploads/');

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: login.php');
}

$_POST['cool'] = 1;
$pid = trim($_GET['pid']);

$_SESSION['projectid'] = $pid;

//define page title
$title = 'My Projects';

// print_r(Tool::findToolByAngle(90));
//include header template
require('templates/header.php');

?>
<?php
//ob_end_flush();
//xsession_start();
// include ('includes/config.php');
// ODO: Fix
// include('classes/extract.php');
//require('classes/parser.php');

//ini_set('display_errors','on');

//if logged in redirect to members page
if(!$user->is_logged_in()){
  unset($_POST['cool']);
  unset($_POST['process']);
  header('Location: login.php');
}
/*
$_POST['pro'] = 1;*/
$_POST['cool'] = 1;
$_POST['process'] = 1;

// TOD: Fix lines below
$fileID = trim($_GET['id']);
// echo $fileID;
$query = "select filename from files where fileid=? LIMIT 1";

$res = $database->getRow($query, [$fileID]);
$iges_file = array_shift($res);
$title = 'Draw';
//include header template
require('templates/header.php');




/*
if (isset($_SESSION['BENDS'])){
$obj = (($_SESSION['BENDS']));
//$objj = unserialize($obj);
$temp = (unserialize($obj));
print_r($temp);
}*/
?>

<script src="assets/js/springy.js"></script>
<script src="assets/js/springyui.js"></script>
<link rel="stylesheet" href="style/draw.css">
<link rel="stylesheet" href="style/overlay.css">


<div id="panel" class="collapsed">

<div class = "panel panel-default">
<div class = "panel-heading">
  <h3 class = "panel-title">
     <b>Model Features</b>
  </h3>
</div>

<div class = "panel-body panel-body1">

  <!--<a id="expandButton" href="#">
    <span></span>
    <span></span>
    <span></span>
  </a>
  div class="filterBlock" >
    <input type="text" id="filterInput" placeholder="Type to filter"/>
    <a href="#" id="clearFilterButton" >x</a><div id="content">   </div>

  </div-->

<div class = "panel panel-default">
<div class = "panel-heading">
  <h3 class = "panel-title">
     <b>Face Adjacency Graph</b>
  </h3>
</div>

<div class = "panel-body">
<script>
var graph = new Springy.Graph();
/*
var dennis = graph.newNode({
label: 'Dennis',
ondoubleclick: function() { console.log("Hello!"); }
});*/

<?php
// $query = "SELECT * FROM features  where fileid = ?";
// $i = 1;
// $result = $database->getAllRows($query, [$fileID]);
// if(isset($result))
foreach ($result as $row){
// //$iges_file = $row['file_id'];
// //if (($i % 2) == 0)
// {
?>
if (!face<?php echo $row['face1_id'];?>)
var face<?php echo $row['face1_id'];?> = graph.newNode({label: 'Face <?php echo $row['face1_id'];?>'});
var bend<?php echo $row['bend_id'];?> = graph.newNode({label: 'Bend <?php echo $row['bend_id'];?>'});
if (!face<?php echo $row['face2_id'];?>)
var face<?php echo $row['face2_id'];?> = graph.newNode({label: 'Face <?php echo $row['face2_id'];?>'});
<?php
// }
++$i;
}
?>
<?php
$result = BendFeatures::find_feature_by_id($fileID);
foreach ($result as $row){
if (($i % 2) == 0)
{
$colours = array("#00A0B0","#6A4A3C","#CC333F","#EB6841", "#EDC951", "#7DBE3C", "#000000");
$key = array_rand($colours, 1);
?>
graph.newEdge(face<?php echo $row['face1_id'];?>, face<?php echo $row['face2_id'];?>, {color: '<?php echo $colours[$key];?>'});
graph.newEdge(face<?php echo $row['face1_id'];?>, bend<?php echo $row['bend_id'];?>, {color: '#00A0B0'});
graph.newEdge(face<?php echo $row['face2_id'];?>, bend<?php echo $row['bend_id'];?>, {color: '#00A0B0'});
<?php
}
++$i;
}
?>
jQuery(function(){
var springy = window.springy = jQuery('#springydemo').springy({
graph: graph,
nodeSelected: function(node){
  console.log('Node selected: ' + JSON.stringify(node.data));
}
});
});
</script>
  <canvas id="springydemo" width="330" height="300"></canvas>
</div>
</div>
<div class = "panel panel-default">
<div class = "panel-heading"><b>Bend Features</b></div>
<table class = "table table-hover">
 <thead>
<tr>
  <th>Bend ID</th>
  <th>Radius</th>
  <th>Thickness</th>
  <th>Length</th>
  <th>Angle</th>
</tr>
</thead>
<?php
// $query = "SELECT * FROM bends  where file_id = '$fileID'";
// $i = 1;
// var_dump($result);
if (isset($result) && is_array($result))
foreach ($result as $row){
// $iges_file = $row['file_id'];
// if (($i % 2) == 0)
{
?>

  <tr>
     <td><?php echo $row['bend_id']; //2;?></td>
     <td><?php echo $row['bend_radius'];?></td>
     <td><?php echo $row['bend_thickness'];?></td>
     <td><?php echo $row['bend_length'];?></td>
     <td><?php echo $row['angle']."&deg";?></td>
  </tr>
<?php
}
++$i;
  }
?>
</table>

</div>
<div class = "panel panel-default" style="margin-bottom:30px">
<div class = "panel-heading"><b>Face Relationships</b></div>

<table class = "table table-hover">
 <thead>
<tr>
  <th>Face 1</th>
  <th>Bend</th>
  <th>Face 2</th>
</tr>
</thead>
<?php
$i = 1;
if (isset($result) && is_array($result))
foreach ($result as $row){
// if (($i % 2) == 0)
{
?>
  <tr>
     <td><?php echo $row['face1_id'];?></td>
     <td><?php echo $row['bend_id'];//2;?></td>
     <td><?php echo $row['face2_id'];?></td>
  </tr>
<?php
}
++$i;
}
?>
<span></span>
</table>

</div>


</div>
</div>
</div>


<iframe id="viewer" name="viewer" allowfullscreen onmousewheel="" src=<?php echo "A_draw.php?id=".$fileID;?>></iframe>
<div class="container">

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
 <div class="modal-dialog modal-lg">
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close"
data-dismiss="modal">&times;</button>
       <h3 class="modal-title">Bend Features : (<?php //echo $caption;?>)</h3>
     </div>
     <div class="modal-body">
       <p>
          <table class = "table table-hover">
  <thead>
 <tr>
   <th>Bend ID</th>
   <th>Radius<?php echo "(mm)"; ?></th>
   <th>Thickness<?php echo "(mm)"; ?></th>
   <th>Length<?php //echo "(".$unit.")"; ?></th>
   <th>Angle(Degrees)</th>
   <th>Height<?php echo "(mm)"; ?></th>
   <th>Bending Force(N)</th>
 </tr>
</thead>
<?php
if (isset($result) && is_array($result))
foreach ($result as $row){
?>

   <tr>
      <td><?php echo $row['bend_id'];///2;?></td>
      <td><?php echo $row['bend_radius'];?></td>
      <td><?php echo $row['bend_thickness'];?></td>
      <td><?php echo $row['bend_length'];?></td>
      <td><?php echo $row['angle']."&deg";?></td>
      <td><?php echo $row['bend_height'];?></td>
      <td><?php echo $row['bending_force'];?></td>
   </tr>
<?php
}
// ++$i;
// }
?>
</table></p>
     </div>
     <div class="modal-footer">
       <button type="button" class="btn btn-default btn-lg"
data-dismiss="modal">Close</button>
     </div>
   </div>
 </div>
</div>

<?php
require('templates/footer.php');
?>
