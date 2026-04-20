<?php
session_start();
if(isset($_POST['lbtn']))
{
    $_SESSION['login']=TRUE;
	$usn=$_POST['username'];
	$pswd=$_POST['password'];
    $error=array();
	if(empty($usn))
        $error[]="Username is empty";
    if(empty($pswd))
        $error[]="Password is required";
    if(empty($error))
    {
        $cn=mysqli_connect('localhost','root','','buspass');
        $qry="select * from User where (email='".$usn."' or username='".$usn."') and password='".$pswd."'";//hacker//username or emailid
        $r=mysqli_query($cn,$qry);
        $rc=mysqli_num_rows($r);
        if($rc==0){
            echo "<script>alert('Username or password is incorrect or doesnt exist');
            window.location.href='loginFront.php';</script>";
        }
        else{
            $res=$r->fetch_assoc();
            $_SESSION['user_id'] = $res['userid'];
            echo "<script>window.location.href='homeyyy.php';</script>";
        }
    }   
    else{
        $msg="";
        foreach($error as $er)
            $msg=$msg."\\n".$er;
		echo "<script>alert('$msg');
        window.location.href='loginFront.php';</script>";
    }
}

?>
	
	