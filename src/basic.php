<?php
session_start();
$conn=mysqli_connect("localhost","root","","test1");
mysqli_query($conn,"set names 'utf8'");
$error=0;

function encrypt($string, $key) {
	
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = "1234567812345678";
	//$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($string, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
	return base64_encode( $iv.$hmac.$ciphertext_raw );
	
}

if(isset($_POST['email'])) {
	$email = mysqli_real_escape_string($conn, $_POST["email"]);
	$password = mysqli_real_escape_string($conn, $_POST["pwd"]);
	$userid = encrypt($email,$password);
	$sql = "SELECT * FROM user WHERE userid = '$userid'";
	$res = mysqli_query($conn,$sql);
	if (mysqli_num_rows($res)>0)
	{
		$row = mysqli_fetch_array($res);
		$_SESSION['typos'] = "user";
		$_SESSION['uid'] = $row['id'];
		$_SESSION['userid'] = $row['userid'];
		$error=0;
		header("location:ActivityDetails.php");  
	}
	
	else 
		{
			$_SESSION['typos']='';
			$error=1;

		}
}
	
if(isset($_POST['username'])) {
	
	$sql="SELECT * FROM  admin WHERE  username =  '$_POST[username]' AND  password =  '$_POST[pwd]'";
	$res=mysqli_query($conn,$sql);
	if (mysqli_num_rows($res)>0) {
		$row=mysqli_fetch_array($res);
		$_SESSION['typos']="admin";
		$_SESSION['adid']=$row['id'];
		$error=0;
		header("location:Graphs.php");  
	}
	else {
		$_SESSION['typos']='';
		$error=2;
	}
}

?>

<!DOCTYPE html>
<style>
body{
 font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
</style> 
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<?php
if (@$_SESSION['typos']=='')
{
?>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="index.php">CrowdSourcedP</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="SignUp.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
        <li><a href="UserLogin.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<?php
}

if (@$_SESSION['typos']=='admin')
{
?>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" class="no-click" >CrowdSourcedP</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
	  	<li><a href="Graphs.php">Data Dashboard</a></li>
		<li><a href="HeatMap.php">HeatMap</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="logoutA.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<?php
}


if (@$_SESSION['typos']=='user')
{
?>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="index.php">CrowdSourcedP</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
	  	<li><a href="ActivityDetails.php">Activity Details</a></li>
		<li><a href="ActivityAnalysis.php">Activity Analysis</a></li>
	    <li><a href="ImportData.php">Upload Data</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<?php
}


if ($error==1)
{
	echo "<div class='container'><div class=\"alert alert-danger\">Please type a <b>valid</b> <b><i>email</b></i> and/or <b><i>password</b></i>.</div></div>";
	
	
}

if ($error==2)
{
	echo "<div class='container'><div class=\"alert alert-danger\">Please type a <b>valid</b> <b><i>username</b></i> and/or <b><i>password</b></i>.</div></div>";
		
}

?>