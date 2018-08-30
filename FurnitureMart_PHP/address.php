<?php
include_once 'dbconfig.php';
if(!$user->is_loggedin())
{
 $user->redirect('index.php');
}
$umail = $_SESSION['user_session'];
$stmt = $DB_con->prepare("SELECT * FROM Address WHERE email=:umail");
$stmt->execute(array(":umail"=>$umail));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['address'])){

  if($userRow!=null){
    $stmtpr = $DB_con->prepare("UPDATE Address SET address=:address,city=:city,state=,:state,zipcode=:zipcode
                                      Where email=,:email");
    $stmtpr->bindparam(":address", $_POST['address']);
    $stmtpr->bindparam(":city", $_POST['city']);
    $stmtpr->bindparam(":state",$_POST['state'] );
    $stmtpr->bindparam(":zipcode", $_POST['zipcode']);
    $stmtpr->bindparam(":email", $umail);
    if($stmtpr->execute()){
          ?>
            <script>
                alert("updated successfully");
                window.location.href=(userhome.php);

            </script>
          <?php
          $user->redirect('userhome.php');
    }
  }else{
  $stmtpr = $DB_con->prepare("INSERT INTO Address(address,city,state,zipcode,email)
                                    VALUES(:address,:city,:state,:zipcode,:email)");
  $stmtpr->bindparam(":address", $_POST['address']);
  $stmtpr->bindparam(":city", $_POST['city']);
  $stmtpr->bindparam(":state",$_POST['state'] );
  $stmtpr->bindparam(":zipcode", $_POST['zipcode']);
  $stmtpr->bindparam(":email", $umail);
  if($stmtpr->execute()){
        ?>
          <script>
              alert("updated successfully");
              window.location.href=(userhome.php);

          </script>
        <?php
        $user->redirect('userhome.php');
  }
}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<title>Address</title>
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
       <div class="form-container">
          <form class="form-horizontal" method="post">
              <h2>Please provide us your location</h2><hr />
              <div class="form-group">
                  <label class="control-label col-sm-2" for="address">Address: </label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="address"  value="<?php echo $userRow['address']?>" /><br>
                </div>
              </div>
              <div class="form-group">
      			      <label class="control-label col-sm-2" for="city">City: </label>
      	  			  <div class="col-sm-3">
      				        <input type="text" class="form-control" name="city" value="<?php echo $userRow['city']?>"/><br>
      				   </div>
  			      </div>
  			    <div class="form-group">
    			      <label class="control-label col-sm-2" for="state">State: </label>
    	  			  <div class="col-sm-3">
    				        <input type="text" class="form-control" name="state" value="<?php echo $userRow['state']?>"/><br>
    				   </div>
  			    </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="zipcode">Zipcode: </label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="zipcode" value="<?php echo $userRow['zipcode']?>"/><br>
               </div>
            </div>


                <div class="clearfix"></div><hr />
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-2">

                 <button type="submit" class="btn btn-block btn-primary" name="signup">
                    Update
                    </button>
                  </div>
              </div>
              <br>

          </form>
         </div>
  </div>


</body>

</html>
