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
if(isset($_POST['prodname'])){


  if(getimagesize($_FILES['profile']['tmp_name'])==FALSE){
    echo "please select an image";
  }else{
    $image=addslashes($_FILES['profile']['tmp_name']);
    $name=addslashes($_FILES['profile']['name']);
    $image=file_get_contents($image);
    $image=base64_encode($image);
    $stmtpr = $DB_con->prepare("INSERT INTO Product(productId,description,productname,productprice,quantity,productImage)
                                      VALUES(:productId,:description,:productname,:productprice,:quantity,:productImage)");
    $prId=mt_rand(10,10000);
    $stmtpr->bindparam(":productId", $prId);
    $stmtpr->bindparam(":description", $_POST['desc']);
    $stmtpr->bindparam(":productname", $_POST['prodname']);
    $stmtpr->bindparam(":productprice",$_POST['price'] );
    $stmtpr->bindparam(":quantity", $_POST['quantity']);
    $stmtpr->bindparam(":productImage", $image);
    if($stmtpr->execute()){
          ?>
            <script>
                alert("added successfully");
                window.location.href=(adminhome.php);

            </script>
          <?php
    }
  }
}
if(isset($_POST['actionremove'])){
  $prodreId=$_POST['productId'];
  $stmtrem = $DB_con->prepare("Delete from Product where productId=:productId");
  $stmtrem->bindparam(":productId", $prodreId);

  if($stmtrem->execute()){
        ?>
          <script>
              alert("deleted successfully");
              window.location.href=(adminhome.php);

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
<title>Home</title>
</head>
<body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
  $(document).ready(function(){
    // Add smooth scrolling to all links
    $("a").on('click', function(event) {
      if (this.hash !== "") {

        event.preventDefault();
        var hash = this.hash;
        $('html, body').animate({
          scrollTop: $(hash).offset().top
        }, 800, function(){

          window.location.hash = hash;
        });
      }
    });
  });
  </script>
  <div  class="container">
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <ul class="nav navbar-nav">
        <li>  <a class="navbar-brand" href="adminhome.php">Home</a></li>
          <li  ><a href="#add" style="float:right;" >Add</a></li>
        <li  ><a href="custorders.php" style="float:right;">Orders</a></li>
        <li  ><a href="logout.php" style="float:right;">Logout</a></li>
      </ul>
     </div>
  </nav>
</div>
<div  class="container" style="padding:48px 16px">
<div class="form-container">
<form action="adminhome.php" method="post"  class="form-horizontal"  enctype="multipart/form-data">
    <div  class="container">
      <?php
      echo '<h1> Welcome, '.$userRow['firstname'].' </h1>';
      ?>
      <h4 id="add">Add Products </h4>
      <hr>
        	<div   class="row">

                  <div class="form-group">
        	        <div class=" col-sm-4 col-md-24">
            			     <input type="text" class="form-control"  name="prodname" placeholder="Name">
            			</div>
                </div>
                <div class="form-group">
                   <div class=" col-sm-4 col-md-24">
                      <textarea rows="5" class="form-control" name="desc" placeholder="Description" ></textarea>
                   </div>
                 </div>
                  <div class="form-group">
            			  <div class=" col-sm-4 col-md-24">
            			       <input type="number" class="form-control" name="price" placeholder="Price">
            			</div>
                </div>
                  <div class="form-group">
            			  <div class=" col-sm-4 col-md-24">
            			       <input type="number" class="form-control" name="quantity" placeholder="Quantity" >
            			</div>
                </div>
                  <div class="form-group">
            			  <div class=" col-sm-4 col-md-24">
            			       <input type="file" name="profile" size="50" placeholder="Upload Your Image"  >
            			</div>
                </div>
            			  <div class=" col-sm-2 col-md-24">
            			       <input type="hidden" name="action" value=add>
            			          <input type="submit" class="btn btn-primary btn-block" value="ADD" >
            			 </div>
           </div>
<hr>
        </div>
</form>
</div>

<form action="adminhome.php" method="post">
	<div class="container">
	   <div  class="row">
	        <div class=" col-xs-12 col-md-6">
	        	<h4>Added Products</h4>
	       	</div>
		 </div>

 <?php
 $stmtp = $DB_con->query('SELECT * FROM Product');
 while($row = $stmtp->fetch(PDO::FETCH_ASSOC)) {
 ?>

	    <div  class="row">
	        <div class=" ">
	            <div class="thumbnail">
    	            	<div class="col-xs-12 col-md-6" style="align:center">
	                    <div class="caption" style="align:right">
  	                         <h4 class="list-group-item-heading">
                               <?php
                                 echo $row['productname'];
                               ?>
                          </h4>
  	                         <p class="list-group-item-text">
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
  	                    </div>
	                     </div>
    	             </div>
                   <?php
                      echo '<img style="align:left;width:200px; height:200px"
                      src="data:image;base64,'.$row['productImage'].'"/>';

                      ?>
                      </form>
	               	 <form action="editproduct.php" method="post">
        				    		<input type="hidden" name="actionedit" value="<?php
                           echo $row['productId'];
                         ?>">

        				    		<input type="submit" value="EDIT" class="btn btn-primary btn-block" style="width:10%" ><br>
    				   		 </form>

    					    <form action="adminhome.php" method="post">
      				    		<input type="hidden" name="actionremove" value="remove">
      					        <input type="hidden" name="productId" value="<?php
                           echo $row['productId'];
                         ?>">
      				    		<input type="submit" value="DELETE" class="btn btn-primary btn-block" style="width:10%" >
    					    </form>

	            </div>

	        </div>
	    </div>
<?php
}
 ?>
  </div>
</div>


</body>

</html>
