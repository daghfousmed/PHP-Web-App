<?php
include_once 'dbconfig.php';
if(!$user->is_loggedin())
{
 $user->redirect('index.php');
}
$umail = $_SESSION['user_session'];
$stmt = $DB_con->prepare("SELECT * FROM Admin WHERE email=:umail");
$stmt->execute(array(":umail"=>$umail));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['newprodId'])){
  $orderId=$_POST['newprodId'];
  $status="Delivered";
  $stmt = $DB_con->prepare("UPDATE  OrderPlaced SET orderStatus=:orderStatus WHERE orderId=:orderId");

  if($stmt->execute(array(":orderId"=>$orderId,":orderStatus"=>$status))){

    ?>
    <script>
        alert("Status Changed Successfully");
        window.location.href=(custorders.php);

    </script>

    <?php
  }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <title>Orders</title>
</head>
<body>

  <div  class="container">
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <ul class="nav navbar-nav">
        <li>  <a class="navbar-brand" href="adminhome.php">Home</a></li>
        <li  ><a href="custorders.php" style="float:right;">Orders</a></li>
        <li  ><a href="logout.php" style="float:right;">Logout</a></li>
      </ul>
     </div>
  </nav>
</div>
<div  class="container" style="padding:48px 16px">

<form action="custorders.php" method="post">
	<div class="container">
	   <div  class="row">
	        <div class=" col-xs-12 col-md-6">
	        	<h4>Orders Received</h4>
            <p>For more details, Please click on Order Number</p>
	       	</div>
		 </div>
<table id="cart" class="table table-hover table-condensed">
         <thead>
         <tr>
           <th style="width:15%">Order Number</th>
           <th style="width:20%">Customer</th>
           <th style="width:10%">Cost</th>
           <th style="width:10%" >Order Date</th>
           <th style="width:10%">Status</th>
           <th style="width:20%"></th>
         </tr>
       </thead>
 <?php
 $stmtp = $DB_con->query('SELECT * from OrderPlaced');
 while($row = $stmtp->fetch(PDO::FETCH_ASSOC)) {
 ?>

   <tbody>
     <tr>
       <td data-th="Order Number">
         <div class="row">
             <?php
             echo '<a href="orderDetails.php?email='.$row["email"].'&orderid='.$row["orderId"].'">'.$row["orderId"].'</a>';

              ?>

         </div>
       </td>
       <td data-th="Customer">
         <div class="row">
             <?php

             echo '<p>'.$row["email"].'</p>';

              ?>


         </div>
       </td>
       <td data-th="Cost">
         <div class="row">
             <?php

             echo '<p>'.$row["totalCost"].'</p>';


              ?>

         </div>
       </td>
       <td data-th="Order Date">
         <div class="row">
             <?php

             echo '<p>'.$row["orderDate"].'</p>';

              ?>

         </div>
       </td>
       <td data-th="Status">
         <div class="row">
             <?php

             echo '<p>'.$row["orderStatus"].'</p>';

              ?>

         </div>
       </td>
       <td data-th="Status">
         <div class="row">
           <form action="custorders.php" method="post">
                  <?php
                    if($row["orderStatus"]=="Pending"){
                  echo '<input type="hidden" name="newprodId" value="'.$row["orderId"].'" />';
                  ?>
                  <input type="submit" name="submit" class="btn btn-info btn-sm" value="Update Status" />
                  <?php
                }else if($row["orderStatus"]=="Delivered"){
                    ?>
                    <p  class="btn btn-info btn-sm" > Delivered </p>
                    <?php

                }
                   ?>
            </form>
           </div>
         </div>
       </td>
     </tr>

   </tbody>


<?php
}
 ?>
</table>
  </div>



</body>

</html>
