<?php
require_once 'dbconfig.php';

if($user->is_loggedin()!="")
{
    $user->redirect('userhome.php');
}

if(isset($_POST['signup']))
{

   $umail = trim($_POST['useremail']);
   $upass = trim($_POST['userpass']);
   $fname= trim($_POST['userfName']);
   $lname= trim($_POST['userlName']);
   $gender= trim($_POST['gender']);
   $birthdate= trim($_POST['DOB']);
   $phone= trim($_POST['phonenumber']);


   if(!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
      $error[] = 'Please enter a valid email address !';
   }
   else if($upass=="") {
      $error[] = "provide password !";
   }
   else if(strlen($upass) < 6){
      $error[] = "Password must be atleast 6 characters";
   }
   else
   {
      try
      {
         $stmt = $DB_con->prepare("SELECT email FROM User");
         $stmt->execute(array( ':umail'=>$umail));
         $row=$stmt->fetch(PDO::FETCH_ASSOC);

         if($row['email']==$umail) {
            $error[] = "sorry email id already taken !";
         }
         else
         {
            if($user->register($fname,$lname,$gender,$birthdate,$phone,$umail,$upass))
            {
                $user->redirect('signup.php?joined');
            }
         }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sign up</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">Furniture Mart</a>
      </div>
      <ul class="nav navbar-nav">

        <li style="float:right;" ><a href="login.php">Login</a></li>

      </ul>
     </div>
  </nav>
<div class="container">
     <div class="form-container">
        <form class="form-horizontal" method="post">
            <h2>You are almost near, Please sign up here</h2><hr />
            <?php
            if(isset($error))
            {
               foreach($error as $error)
               {
                  ?>
                  <div class="alert alert-danger">
                      <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                  </div>
                  <?php
               }
            }
            else if(isset($_GET['joined']))
            {
                 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                 </div>
                 <?php
            }
            ?>
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">Email: </label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="useremail"  value="<?php if(isset($error)){echo $uname;}?>" /><br>
              </div>
            </div>
            <div class="form-group">
    			      <label class="control-label col-sm-2" for="firstName">First Name: </label>
    	  			  <div class="col-sm-3">
    				        <input type="text" class="form-control" name="userfName"/><br>
    				   </div>
			      </div>
			    <div class="form-group">
  			      <label class="control-label col-sm-2" for="LastName">Last Name: </label>
  	  			  <div class="col-sm-3">
  				        <input type="text" class="form-control" name="userlName"/><br>
  				   </div>
			    </div>
			    <div class="form-group">
  			     <label class="control-label col-sm-2" for="Gender">Gender: </label>
  	  			  <div class="col-sm-4">
  	  			  		 <div class="col-sm-4">
  				        <input type="radio" name = "gender" value = "M" label = "Male" />Male
  				        </div>
  				        <div class="col-sm-4">
                          <input type="radio" name = "gender" value = "F" label = "Female" />Female
  						</div>
  				   </div>
			    </div>
			    <div class="form-group">
  			     <label class="control-label col-sm-2" for="DOB">Date of Birth: </label>
  	  			  <div class="col-sm-3">
  				        <input type="date" class="form-control" name="DOB"/><br>
  				   </div>
			    </div>

			    <div class="form-group">
  			      <label class="control-label col-sm-2" for="phonenumber">Phone number: </label>
  	  			  <div class="col-sm-3">
  				        <input type="number" class="form-control" name="phonenumber"/><br>
  				   </div>
			    </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="userpass">Password: </label>
    	  			  <div class="col-sm-3">
               <input type="password" class="form-control" name="userpass" placeholder="Enter Password" />
             </div>
              </div>
              <div class="clearfix"></div><hr />
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-2">
               <button type="submit" class="btn btn-block btn-primary" name="signup">
                  SIGN UP
                  </button>
                </div>
            </div>
            <br>

        </form>
       </div>
</div>

</body>
</html>
