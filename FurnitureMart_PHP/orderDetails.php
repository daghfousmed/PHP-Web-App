<?php
include_once 'dbconfig.php';
if(!$user->is_loggedin())
{
 $user->redirect('index.php');
}
$umail = $_SESSION['user_session'];
if(isset($_GET["email"])){
$umail=$_GET["email"];
$uorder=(string)$_GET["orderid"];
$stmt = $DB_con->prepare('SELECT productname , productprice ,quan from  Product
   inner join cartitems on Product.productId = cartitems.productId
   inner join orderplaced on orderplaced.orderId = cartitems.orderId
   where email=:email and orderplaced.orderId=:orderId');
$stmt->execute(array(":email"=>$umail,":orderId"=>$uorder));

$stmtadd = $DB_con->prepare("select * from Address where email=:email");
$stmtadd->execute(array(":email"=>$umail));
$useraddRow=$stmtadd->fetch(PDO::FETCH_ASSOC);

$stmtaddph = $DB_con->prepare("select firstname,lastname,phonenumber from User where email=:email");
$stmtaddph->execute(array(":email"=>$umail));
$userphneRow=$stmtaddph->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<title>Details</title>
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
	<div class="container">
	   <div  class="row">
	        <div class=" col-xs-12 col-md-6">
	        	<h4>Order Details</h4><hr>
        <table id="cart" class="table table-hover table-condensed">
                 <thead>
                 <tr>
                   <th style="width:20%">Product Name</th>
                   <th style="width:20%">Price</th>
                   <th style="width:10%">Quantity</th>
                 </tr>
               </thead>
              <tbody>

      <?php
while($userRow=$stmt->fetch(PDO::FETCH_ASSOC)){
      ?>
              <tr>
                <td data-th="Name " style="text-align:left;">
                  <div class="row">
                    <?php
                  echo  $userRow['productname'];

                    ?>
                    </div>
                  </div>
                </td>
                <td data-th="Price" style="text-align:left;">
                  <div class="row">
                    <?php
                    echo $userRow['productprice'];

                    ?>
                    </div>

                  </div>
                </td>
                <td data-th="Quantity"style="text-align:left;">
                  <div class="row">
                    <?php
                  echo  $userRow['quan'];

                    ?>
                    </div>
                  </div>
                </td>
              </tr>
<?php }?>
            </tbody>
</table>
	       	</div>

		 </div>

  </div>
  <style>
  .card {
    font-size: 1em;
    overflow: hidden;
    padding: 0;
    border: none;
    border-radius: .28571429rem;
    box-shadow: 0 1px 3px 0 #d4d4d5, 0 0 0 1px #d4d4d5;
}

.card-block {
    font-size: 1em;
    position: relative;
    margin: 0;
    padding: 1em;
    border: none;
    border-top: 1px solid rgba(34, 36, 38, .1);
    box-shadow: none;
}
.card-title {
    font-size: 1.28571429em;
    font-weight: 700;
    line-height: 1.2857em;
}

.card-text {
    clear: both;
    margin-top: .5em;
    color: rgba(0, 0, 0, .68);
}
  </style>
  <div class="container">
     <div  class="row">
       <div class=" col-xs-12 col-md-6">
         <h4>Customer Details</h4><hr>
         <div class="card">
            <div class="box ">

               <div class="card-block">
                   <h4 class="card-title"><?php
                  echo $userphneRow['firstname']." ".$userphneRow['lastname'];

                   ?></h4>
                   <div class="card-text">
                     <?php
                    echo $useraddRow['address'];

                     ?>
                   </div>
                   <div class="card-text">
                     <?php
                    echo $useraddRow['city'];

                     ?>
                   </div>
                   <div class="card-text">
                     <?php
                    echo $useraddRow['state'];

                     ?>
                   </div>
                   <div class="card-text">
                     <?php
                    echo $useraddRow['zipcode'];

                     ?>
                   </div>
                   <div class="card-text">
                     <?php
                    echo $userphneRow['phonenumber'];

                     ?>
                   </div>
               </div>
               </div>
           </div>
       </div>


     </div>
   </div>


</div>


</body>

</html>
