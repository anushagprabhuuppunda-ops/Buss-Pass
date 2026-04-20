 <html>
    <body>
     <form method="POST">
        <label>New Password:</label>
        <input type="pswd" name="pswd" required>
        <button type="submit" name="ubtn">Update</button>
    </form>
    </body>
</html>
<?php

  $cn=mysqli_connect('localhost','root','','buspass');
    $qry="select * from user where Email='".$eid."'";
    $r=mysqli_query($cn,$qry);
    $rc=mysqli_num_rows($r);
    if($rc!=0){
         $uqry="update user set Password='".$pswd."' where Email='".$eid."'";
        echo "<script>alert('Password updated');
        window.location.href='loginFront.php';</script>";
        }
    else{
         echo"eroor";
    }
