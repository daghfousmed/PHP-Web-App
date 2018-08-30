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
if(isset($_POST['action'])){


  if(getimagesize($_FILES['profile']['tmp_name'])==FALSE){
    $stmtpr = $DB_con->prepare("UPDATE Product SET description =:description,productname=:productname,
                                        productprice=:productprice,quantity=:quantity  WHERE productId=:prodId");
    $prodedId=$_POST['action'];
    $stmtpr->bindparam(":prodId", $prodedId);
    $stmtpr->bindparam(":description", $_POST['description']);
    $stmtpr->bindparam(":productname", $_POST['productname']);
    $stmtpr->bindparam(":productprice",$_POST['productprice'] );
    $stmtpr->bindparam(":quantity", $_POST['quantity']);

    if($stmtpr->execute()){
          ?>
            <script>
                alert("updated successfully");
                window.location.href=(adminhome.php);

            </script>
          <?php
          $user->redirect('index.php');
    }
  }else{
    $image=addslashes($_FILES['profile']['tmp_name']);
    $name=addslashes($_FILES['profile']['name']);
    $image=file_get_contents($image);
    $image=base64_encode($image);

    $stmtpr = $DB_con->prepare("UPDATE Product SET description =:description,productname=:productname,
                                        productprice=:productprice,quantity=:quantity,productImage=:productImage  WHERE productId=:prodId");

    $stmtpr->bindparam(":prodId", $prId);
    $stmtpr->bindparam(":description", $_POST['description']);
    $stmtpr->bindparam(":productname", $_POST['productname']);
    $stmtpr->bindparam(":productprice",$_POST['productprice'] );
    $stmtpr->bindparam(":quantity", $_POST['quantity']);
    $stmtpr->bindparam(":productImage", $image);
    if($stmtpr->execute()){
          ?>
            <script>
                alert("added successfully");
                window.location.href=(adminhome.php);

            </script>

          <?php
          $user->redirect('index.php');
    }
  }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <div  class="container">
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <ul class="nav navbar-nav">
        <li>  <a class="navbar-brand" href="adminhome.php">Home</a></li>

        <li  ><a href="orders.php" style="float:right;">Orders</a></li>
        <li  ><a href="logout.php" style="float:right;">Logout</a></li>
      </ul>
     </div>
  </nav>
</div>
<div  class="container" style="padding:48px 16px">
<div class="form-container">
<form action="editproduct.php" method="post"  class="form-horizontal"  enctype="multipart/form-data">
    <div  class="container">
      <h4 id="add">Edit Products </h4>
      <hr>
      <div   class="row">
        <?php
        if(isset($_POST['actionedit'])){
        $prodId=$_POST['actionedit'];
        $stmtcheck=$DB_con->prepare("SELECT * FROM Product Where productId=:prodId");
        $stmtcheck->bindparam(":prodId",$prodId);
        $stmtcheck->execute();
        $prodRow=$stmtcheck->fetch(PDO::FETCH_ASSOC);

        ?>
        <div class="form-group">
        <div class=" col-sm-4 col-md-24">
             <input type="text" class="form-control" name="productname" value="<?php echo $prodRow['productname']; ?>" placeholder="Name">
        </div>
        </div>
        <div class="form-group">
         <div class=" col-sm-4 col-md-24">
            <textarea rows="5" class="form-control" name="description"  value="<?php echo $prodRow['description']; ?>" placeholder="<?php echo $prodRow['description']; ?>" ></textarea>
         </div>
        </div>
        <div class="form-group">
          <div class=" col-sm-4 col-md-24">
               <input type="number" class="form-control" name="productprice"  value="<?php echo $prodRow['productprice']; ?>" placeholder="Price">
        </div>
        </div>
        <div class="form-group">
          <div class=" col-sm-4 col-md-24">
               <input type="number" class="form-control" name="quantity" value="<?php echo $prodRow['quantity']; ?>" placeholder="Quantity" >
        </div>
        </div>

        <?php
           echo '<img style="align:left;width:200px; height:200px"
           src="data:image;base64,'.$prodRow['productImage'].'"/>';

           ?>
        <div class="form-group">
          <div class=" col-sm-4 col-md-24">
               <input type="file" name="profile" size="50" placeholder="Upload Your Image"  >
        </div>
        </div>
          <div class=" col-sm-2 col-md-24">
               <input type="hidden" name="action" value="<?php
                  echo $prodRow['productId'];
                ?>">
                  <input type="submit" class="btn btn-primary btn-block" value="update" >
         </div>
<?php
}
?>

           </div>
<hr>
        </div>
</form>
</div>

</body>

</html>
