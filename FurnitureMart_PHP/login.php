<?php
require_once 'dbconfig.php';

if($user->is_loggedin()!="")
{
 $user->redirect('userhome.php');
}

if(isset($_POST['btn-login']))
{

 $umail = $_POST['useremail'];
 $upass = $_POST['userpassword'];

 if($user->login($umail,$upass))
 {
  $user->redirect('userhome.php');
}else if($user->login_admin($umail,$upass)){
   $user->redirect('adminhome.php');
}
 else
 {
  $error = "Wrong Details !";
 }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login </title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>
<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">Furniture Mart</a>
      </div>
      <ul class="nav navbar-nav">

        <li style="float:right;" ><a href="signup.php">Sign Up</a></li>

      </ul>
     </div>
  </nav>
<div class="container">
     <div class="form-container">
        <form class="form-horizontal"  method="post">
            <h2>Happy to see you , Please Login here</h2><hr />
            <?php
            if(isset($error))
            {
                  ?>
                  <div class="alert alert-danger">
                      <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
                  </div>
                  <?php
            }
            ?>
            <div class="form-group">
              <label class="control-label col-sm-2" for="useremail">Email:</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="useremail"  required /><br>
             </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="userpassword">Password:</label>
              <div class="col-sm-6">
             <input type="password" class="form-control" name="userpassword" required /><br>
           </div>
            </div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-2">
             <button type="submit" name="btn-login" class="btn btn-block btn-primary">
                SIGN IN
                </button>
              </div>
            </div>
            <br />
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-4">
            <label>Don't have account yet ! <a href="signup.php">Sign Up</a></label>
          </div>
        </div>
        </form>
       </div>
</div>

</body>
</html>
