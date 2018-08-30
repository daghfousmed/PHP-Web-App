<?php
require_once 'dbconfig.php';

if($user->is_loggedin()!="")
{
  $umail = $_SESSION['user_session'];
  if($user->logincheckuser($umail)){
 $user->redirect('userhome.php');
}else if($user->logincheckadmin($umail)){
$user->redirect('adminhome.php');
}
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<title>Home</title>
</head>
<body >
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
<div class="jumbotron text-center" style="margin-bottom: 0px;
    opacity: 0.6;
    color: #fff;
    background: #000 ;" >

	  <h1>Furniture Mart</h1>
	  <p>Its home for furniture products</p>

     <form class="form-inline" >
     <div class="input-group">
	        <div class="form-group">
	        <div class="col-sm-offset-2 col-sm-10">

				<a href="login.php"><button type="button"  class="btn btn-primary btn-lg" >Login</button></a>
			</div>
			</div>
      </div>

  </form>
</div>

</body>
</html>
