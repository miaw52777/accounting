<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 

$user_id = 'miaw52777'; //$_POST['user_id'];
$type = '食'; //$_POST['type'];

$result = getOverhead_Item_List_test($user_id,$type);

while($data=mysqli_fetch_assoc($result['DATA'])){
   $res .= "
      <option value='{$data["name"]}'>{$data['name']}</option>";
};
echo $res;//將型號項目丟回給ajax

?>