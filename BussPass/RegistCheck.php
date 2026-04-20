<?php
if(isset($_POST['rbtn']))
{
	$usn=$_POST['username'];
    $eid=$_POST['email_id'];
	$pswd=$_POST['password'];
    $confirm = $_POST['confirm_password'] ?? '';
    $error=array();
	if(empty($usn))
        $error[]="User ID is empty";
    if(empty($eid))
        $error[]="Email ID is required";
    if(empty($pswd))
        $error[]="Password is required";
    if(empty($confirm))
        $error[]="Confirm password is required";
    if ($pswd !== $confirm) {
    $error[]="Passwords do not match!";
    }

// hash password before storing (VERY important)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// then insert into database
    if(empty($error))
    {
        $cn=mysqli_connect('localhost','root','','buspass');
        $qry="select * from user where Email='".$eid."'";//hacker//username or emailid
        $r=mysqli_query($cn,$qry);
        $rc=mysqli_num_rows($r);
        if($rc==0){
            $qry_in="insert into user(Username,Email,Password) values('".$usn."','".$eid."','".$pswd."')";//vulnerable
            $r=mysqli_query($cn,$qry_in);
            echo "<script>window.location.href='homeyyy.php';</script>";
        }
        else{
            echo "<script>alert('User already exist.You have to login.');
            window.location.href='loginFront.php';</script>";
            
        }
    }   
    else{
        $msg="";
        foreach($error as $er)
            $msg=$msg."\\n".$er;
		echo "<script>alert('$msg');
        window.location.href='Regist.html';</script>";
    }
}

?>