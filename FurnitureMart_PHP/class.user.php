<?php
class USER
{
    private $db;

    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

    public function register($fname,$lname,$gender,$birthdate,$phone,$umail,$upass)
    {
       try
       {
           $new_password = password_hash($upass, PASSWORD_DEFAULT);

           $stmt = $this->db->prepare("INSERT INTO User(email,firstname,lastname,phonenumber,password,birthdate,gender)
                                                       VALUES(:umail, :ufname, :ulname,:uphone,:upass,:ubirth,:ugender)");

           $stmt->bindparam(":umail", $umail);
           $stmt->bindparam(":ufname",$fname );
           $stmt->bindparam(":ulname",$lname );
           $stmt->bindparam(":uphone", $phone);
           $stmt->bindparam(":upass", $new_password);
           $stmt->bindparam(":ubirth", $birthdate);
           $stmt->bindparam(":ugender", $gender);
           $stmt->execute();

           return $stmt;
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
    }

    public function login($umail,$upass)
    {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM User WHERE  email=:umail LIMIT 1");
          $stmt->execute(array(':umail'=>$umail));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
             if(password_verify($upass, $userRow['password']))
             {
                $_SESSION['user_session'] = $userRow['email'];
                return true;
             }
             else
             {
                return false;
             }
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
   public function login_admin($umail,$upass)
   {
      try
      {
         $stmt = $this->db->prepare("SELECT * FROM Admin WHERE  email=:umail AND password=:password LIMIT 1");
         $stmt->execute(array(':umail'=>$umail,':password'=>$upass));
         $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
         if($stmt->rowCount() > 0)
         {
           $_SESSION['user_session'] = $userRow['email'];
           return true;

         }  else
           {
              return false;
           }
      }
      catch(PDOException $e)
      {
          echo $e->getMessage();
      }
  }
  public function logincheckadmin($umail)
  {
     try
     {
        $stmt = $this->db->prepare("SELECT * FROM Admin WHERE  email=:umail LIMIT 1");
        $stmt->execute(array(':umail'=>$umail));
        $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() > 0)
        {
          $_SESSION['user_session'] = $userRow['email'];
          return true;

        }  else
          {
             return false;
          }
     }
     catch(PDOException $e)
     {
         echo $e->getMessage();
     }
 }
 public function logincheckuser($umail)
 {
    try
    {
       $stmt = $this->db->prepare("SELECT * FROM User WHERE  email=:umail LIMIT 1");
       $stmt->execute(array(':umail'=>$umail));
       $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
       if($stmt->rowCount() > 0)
       {
         $_SESSION['user_session'] = $userRow['email'];
         return true;

       }  else
         {
            return false;
         }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
   public function is_loggedin()
   {
      if(isset($_SESSION['user_session']))
      {
         return true;
      }
   }

   public function redirect($url)
   {
       header("Location: $url");
   }

   public function logout()
   {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
   }
}
?>
