<?php
include_once 'dbconfig.php';
if(!$user->is_loggedin())
{
 $user->redirect('index.php');
}
$umail = $_SESSION['user_session'];
$stmt = $DB_con->prepare("SELECT * FROM User WHERE email=:umail");
$stmt->execute(array(":umail"=>$umail));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
if(isset($_GET['productId']) && isset($_GET['price'])){
  $prodId=$_GET['productId'];
  $price=$_GET['price'];
  $stmtcheck=$DB_con->prepare("SELECT * FROM BufCArt Where productId=:prodId AND email=:email");
  $stmtcheck->bindparam(":prodId",$prodId);
  $stmtcheck->bindparam(":email",$umail);
  $stmtcheck->execute();
  $prodRow=$stmtcheck->fetch(PDO::FETCH_ASSOC);
  if($prodRow["productId"]!=null){
    $newQuantity=$prodRow["quantity"]+1;
    $stmtpr = $DB_con->prepare("UPDATE BufCArt SET quantity =:quantity WHERE productId=:prodId AND email=:email");
    $stmtpr->bindparam(":quantity", $newQuantity);
    $stmtpr->bindparam(":prodId", $prodId);
    $stmtpr->bindparam(":email", $umail);
    $stmtpr->execute();
  }else{
  $datead=date("Y-m-d");
  $qun=1;
  $stmtpr = $DB_con->prepare("INSERT INTO BufCArt(productId,email,price,dateAdded,quantity) VALUES(:prodId,:email,:price,:dataAdded,:quantity)");
  $stmtpr->bindparam(":prodId", $prodId);
  $stmtpr->bindparam(":email", $umail);
  $stmtpr->bindparam(":price", $price);
  $stmtpr->bindparam(":dataAdded",$datead );
  $stmtpr->bindparam(":quantity", $qun);
  $stmtpr->execute();
}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<title>Welcome, <?php print($userRow['firstname']); ?></title>
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


<style type="text/css">
.glyphicon { margin-right:5px; }
.thumbnail
{
    margin-bottom: 20px;
    padding: 0px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    border-radius: 0px;
}

.item.list-group-item
{
    float: none;
    width: 100%;
    background-color: #fff;
    margin-bottom: 10px;
}
.item.list-group-item:nth-of-type(odd):hover,.item.list-group-item:hover
{
    background: #428bca;
}

.item.list-group-item .list-group-image
{
    margin-right: 10px;
}
.item.list-group-item .thumbnail
{
    margin-bottom: 0px;
}
.item.list-group-item .caption
{
    padding: 9px 9px 0px 9px;
}
.item.list-group-item:nth-of-type(odd)
{
    background: #eeeeee;
}

.item.list-group-item:before, .item.list-group-item:after
{
    display: table;
    content: " ";
}

.item.list-group-item img
{
    float: left;
}
.item.list-group-item:after
{
    clear: both;
}
.list-group-item-text
{
    margin: 0 0 11px;
}
/* snackbar*/
#snackbar {
    visibility: hidden; /* Hidden by default. Visible on click */
    min-width: 250px; /* Set a default minimum width */
    margin-left: -125px; /* Divide value of min-width by 2 */
    background-color: #333; /* Black background color */
    color: #fff; /* White text color */
    text-align: center; /* Centered text */
    border-radius: 2px; /* Rounded borders */
    padding: 16px; /* Padding */
    position: fixed; /* Sit on top of the screen */
    z-index: 1; /* Add a z-index if needed */
    left: 50%; /* Center the snackbar */
    bottom: 30px; /* 30px from the bottom */
}

/* Show the snackbar when clicking on a button (class added with JavaScript) */
#snackbar.show {
    visibility: visible; /* Show the snackbar */

/* Add animation: Take 0.5 seconds to fade in and out the snackbar.
However, delay the fade out process for 2.5 seconds */
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

/* Animations to fade the snackbar in and out */
@-webkit-keyframes fadein {
    from {bottom: 0; opacity: 0;}
    to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
    from {bottom: 0; opacity: 0;}
    to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
    from {bottom: 30px; opacity: 1;}
    to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
    from {bottom: 30px; opacity: 1;}
    to {bottom: 0; opacity: 0;}
}
</style>
<script>
function myFunction() {
    var x = document.getElementById("snackbar")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 6000);
}
</script>

<div class="content">
  <?php
echo '<h1> Welcome, '.$userRow['firstname'].' </h1>';
?>

<div id="container">
  <div id="products" class="row list-group-item">
<?php
$stmtp = $DB_con->query('SELECT * FROM Product');
while($row = $stmtp->fetch(PDO::FETCH_ASSOC)) {
?>


        <div class="item  col-xs-4 col-lg-3">
             <div class="thumbnail">
              <?php
                 echo '<img src="data:image;base64,'. $row['productImage'].'"/>';
                 ?>

                 <div class="caption">
                     <h4 class="group inner list-group-item-heading">
                          <?php
                            echo $row['productname'];
                          ?>
                       </h4>
                     <p class="group inner list-group-item-text">
                       <?php
                         echo $row['description'];
                       ?>
                     </p>
                     <div class="row">
                         <div class="col-xs-12 col-md-6">
                             <p class="lead">
                               <?php
                                 echo $row['productprice']."$";
                               ?>
                             </p>
                         </div>
                         <div class="col-xs-12 col-md-6">
                           <?php
                              echo '<button class="btn btn-primary" onclick="myFunction()"><a style="color:white;" href="userhome.php?productId='.$row["productId"].'&price='.$row["productprice"].'">Add to cart</a></button>';
                             ?>
                         </div>
                     </div>
                 </div>
             </div>
</div>
<?php
}
?>
</div>
</div>
</div>
<div id="snackbar">Added to cart Successfully</div>
</body>

</html>
