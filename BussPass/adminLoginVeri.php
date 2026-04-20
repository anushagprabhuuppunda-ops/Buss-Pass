<?php
session_start();
if(isset($_POST['lbtn']))
{
    $_SESSION['login']=TRUE;
	$usn=$_POST['admin_id'];
	$pswd=$_POST['password'];
    $error=array();
	if(empty($usn))
        $error[]="Username is empty";
    if(empty($pswd))
        $error[]="Password is required";
    if(empty($error))
    {
        $cn=mysqli_connect('localhost','root','','buspass');
        $qry="select * from admin where (email='".$usn."' or username='".$usn."') and password='".$pswd."'";//hacker//username or emailid
        $r=mysqli_query($cn,$qry);
        $rc=mysqli_num_rows($r);
        if($rc==0){
            echo "<script>alert('Unknown Admin');
            window.location.href='adminLogin.php';</script>";
        }
        else{
            $res=$r->fetch_assoc();
            $_SESSION['admin_em'] = $res['email'];
            echo "<script>window.location.href='adminHome.php';</script>";
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
	
	