<?php
include"db.php";
if($_GET['date']!='')
{
$sql = "SELECT count(id)as active FROM users where status='1' and bys='".$_GET['id']."' and DATE_DELIVER='".$_GET['date']."'";
}
else
{
$sql = "SELECT count(id)as active FROM users where status='1' and bys='".$_GET['id']."'";
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $active=$row['active'];
    }
} else {
     $active="0";
}
if($_GET['date']!='')
{
$sql = "SELECT count(id)as deactive FROM users where status='0' and bys='".$_GET['id']."' and DATE_DELIVER='".$_GET['date']."'";
}else
{
$sql = "SELECT count(id)as deactive FROM users where status='0' and bys='".$_GET['id']."'";
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $deactive=$row['deactive'];
    }
} else {
     $deactive="0";
}
if($_GET['date']!='')
{
$sql = "SELECT count(id)as users FROM users where bys='".$_GET['id']."' and DATE_DELIVER='".$_GET['date']."'";
}else
{
$sql = "SELECT count(id)as users FROM users where bys='".$_GET['id']."'";
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $users=$row['users'];
    }
} else {
     $users="0";
}
if($_GET['date']!='')
{
$sql = "SELECT count(id)as mannual FROM users  where bys='".$_GET['id']."' and REG_TYPE='M'";
}else
{
$sql = "SELECT count(id)as mannual FROM users  where bys='".$_GET['id']."' and REG_TYPE='M'";
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $mannual=$row['mannual'];
    }
} else {
     $mannual="0";
}

$sql = "SELECT count(id)as app FROM users  where bys='".$_GET['id']."' and REG_TYPE='C'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $app=$row['app'];
    }
} else {
     $app="0";
}

echo'

  ';

  echo'
  <div class="list">
<a class="item item-icon-left" href="#">
    <i class="icon ion-android-contacts positive"></i>
    Total Users
    <span class="badge badge-assertive">'.$users.'</span>
  </a>
  
 
 
  <a class="item item-icon-left" href="#">
    <i class="icon ion-android-phone-portrait positive"></i>
    App Users
    <span class="badge badge-assertive">'.$app.'</span>
  </a>
  
   <a class="item item-icon-left" href="#">
    <i class="icon ion-android-contact positive"></i>
    Mannual Users
    <span class="badge badge-assertive">'.$mannual.'</span>
  </a>
   <a class="item item-icon-left" href="#">
    <i class="icon ion-checkmark-circled balanced"></i>
    Active Users
    <span class="badge badge-assertive">'.$active.'</span>
  </a>
  <a class="item item-icon-left " href="#">
    <i class="icon ion-close-circled assertive"></i>
    Deactive Users
    <span class="badge badge-assertive">'.$deactive.'</span>
  </a>
  
  </div>
  ';
?>