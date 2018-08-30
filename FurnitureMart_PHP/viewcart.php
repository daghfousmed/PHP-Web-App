<?php
include_once 'dbconfig.php';
if(!$user->is_loggedin())
{
 $user->redirect('index.php');
}
$umail = $_SESSION['user_session'];
if(isset($_GET['newquantity']) && isset($_GET['newprodId'])){
  $stmtpr = $DB_con->prepare("UPDATE BufCArt SET quantity =:quantity WHERE productId=:prodId AND email=:email");
  $stmtpr->bindparam(":quantity", $_GET['newquantity']);
  $stmtpr->bindparam(":prodId", $_GET['newprodId']);
  $stmtpr->bindparam(":email", $umail);
  $stmtpr->execute();
}
if(isset($_GET['newdelprodId'])){
  $stmtprd = $DB_con->prepare("DELETE from BufCArt where productId=:prodId and email=:email");
  $stmtprd->bindparam(":prodId", $_GET['newdelprodId']);
  $stmtprd->bindparam(":email", $umail);
  $stmtprd->execute();
}
if(isset($_GET["action"])&& isset($_GET['value'])){
  $orderId=mt_rand(10,100000);
  $crntDate=date("Y-m-d");
  $status="Pending";
  $cost=$_GET['value'];
  $stmtodr = $DB_con->prepare("INSERT INTO OrderPlaced(orderId,email,totalCost,orderDate,orderStatus) VALUES (:orderId,:email,:totalCost,:orderDate,:orderStatus)");
  $stmtodr->bindparam(":orderId", $orderId);
  $stmtodr->bindparam(":email", $umail);
  $stmtodr->bindparam(":totalCost", $cost);
  $stmtodr->bindparam(":orderDate", $crntDate);
  $stmtodr->bindparam(":orderStatus", $status);
  $stmtodr->execute();

  $stmt = $DB_con->prepare("SELECT * FROM BufCArt WHERE email=:umail");
  $stmt->execute(array(":umail"=>$umail));




  while($userRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $stmtcar = $DB_con->prepare("INSERT INTO CartItems(productId,quan,dateAdded,orderId) VALUES (:productId,:quantity,:dateAdded,:orderId)");
    $stmtcar->bindparam(":productId", $userRow['productId']);
    $stmtcar->bindparam(":quantity", $userRow['quantity']);
    $stmtcar->bindparam(":dateAdded", $crntDate);
    $stmtcar->bindparam(":orderId", $orderId);
    $stmtcar->execute();

    $stmtprd = $DB_con->prepare("DELETE from BufCArt where productId=:prodId and email=:email");
    $stmtprd->bindparam(":prodId", $userRow['productId']);
    $stmtprd->bindparam(":email", $umail);
    $stmtprd->execute();
}
$message="successfully placed";

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Cart </title>

<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">

      <ul class="nav navbar-nav">
        <li>  <a class="navbar-brand" href="userhome.php">Home</a></li>
        <li  ><a href="address.php" style="float:left;">Address</a></li>
        <li  ><a href="viewcart.php" style="float:left;">View Cart</a></li>
        <li  ><a href="logout.php" style="float:right;">Logout</a></li>

      </ul>
     </div>
  </nav>

<div class="container">
  <?php

  $stmtemp = $DB_con->prepare("SELECT * FROM BufCArt WHERE email=:umail");
  if(isset($message)){

    ?>
    <h4>Your order has been placed Successfully</h4>
    <p> Click to continue your shopping</p>
  <br><a href="userhome.php" class="btn btn-primary"><i class="fa fa-angle-left"></i> Continue Shopping</a>
<?php
}else if($res1=$stmtemp->execute(array(":umail"=>$umail))){

   ?>
   <p> To change Quantity, Edit Quantity and Click on Update button</p>
	<table id="cart" class="table table-hover table-condensed">
    				<thead>
						<tr>
							<th style="width:50%">Product</th>
							<th style="width:10%">Price</th>
							<th style="width:8%">Quantity</th>
							<th style="width:22%" class="text-center">Subtotal</th>
							<th style="width:10%"></th>
						</tr>
					</thead>

<?php
$umail = $_SESSION['user_session'];
$stmt = $DB_con->prepare("SELECT * FROM BufCArt WHERE email=:umail");
$stmt->execute(array(":umail"=>$umail));
$total=0;
while($userRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
$stmtprod = $DB_con->prepare("SELECT * FROM Product WHERE productId=:prodId");
$stmtprod->execute(array(":prodId"=>$userRow["productId"]));
while($prodRow=$stmtprod->fetch(PDO::FETCH_ASSOC)){
$total=$total+($prodRow["productprice"]*$userRow["quantity"]);
?>
					<tbody>
						<tr>
							<td data-th="Product">
								<div class="row">
                  <?php
                     echo '<div class="col-sm-2 hidden-xs"><img class="img-responsive" src="data:image;base64,'.$prodRow['productImage'].'"/></div>';
                     ?>

									<div class="col-sm-10">

                    <?php
                    echo '<h4 class="nomargin">'.$prodRow["productname"].'</h4>';
                    echo '<p>'.$prodRow["description"].'</p>'
                     ?>


									</div>
								</div>
							</td>
              <?php
              echo '<td data-th="Price">'.$prodRow["productprice"].'</td>';

               ?>
               <form action="viewcart.php">
                     <?php
                     echo '<td data-th="Quantity">
       								<input type="number" name="newquantity" class="form-control text-center" value="'.$userRow["quantity"].'">
       							</td>';

                      ?>

                    <?php
                    echo '<td data-th="Subtotal" class="text-center">'.$prodRow["productprice"]*$userRow["quantity"].'</td>';

                     ?>

      							<td class="actions" data-th="">
                      <?php
                      echo '<input type="hidden" name="newprodId" value="'.$prodRow["productId"].'" />';

                       ?>

                    <input type="submit" name="submit" class="btn btn-info btn-sm" value="update" />

                </form>
                 <form action="viewcart.php">
                   <?php
                   echo '<input type="hidden" name="newdelprodId" value="'.$prodRow["productId"].'" />';

                    ?>
								<input type="submit" name="submit" class="btn btn-info btn-sm" value="delete " />
              </form>
							</td>
						</tr>
					</tbody>

<?php
}
}
 ?>

					<tfoot>
						<tr class="visible-xs">
              <?php
              echo '<td class="text-center"><strong>Total: '.$total.'</strong></td>';

               ?>

						</tr>
						<tr>
							<td><a href="userhome.php" class="btn btn-primary"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
							<td colspan="2" class="hidden-xs"></td>
              <?php

              if($total!=0){
                  echo '<td class="hidden-xs text-center"><strong>Total: '.$total.'</strong></td>';
                  ?>
                  <td><a href="viewcart.php?action=checkout&value=<?php echo $total ?>" class="btn btn-primary btn-block">Checkout <i class="fa fa-angle-right"></i></a></td>
                  <?php
              }
               ?>


						</tr>
					</tfoot>


				</table>
</div>
<?php
}
?>

</body>
</html>
