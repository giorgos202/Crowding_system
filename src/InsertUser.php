<!DOCTYPE html>
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
session_start();
$conn=mysqli_connect("localhost","root","","test1");
mysqli_query($conn,"set names 'utf8'");

function encrypt($string, $key) {
	
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = "1234567812345678";
	//$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($string, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
	return base64_encode( $iv.$hmac.$ciphertext_raw );
	
}

if(isset($_POST['email']))
{
	$password = mysqli_real_escape_string($conn,$_POST["pwd"]);
	$email = mysqli_real_escape_string($conn,$_POST["email"]);
	$userid = encrypt($email,$password);
	$password = password_hash($password, PASSWORD_DEFAULT);

	$sql = "INSERT INTO user(id,fname,lname,username,userid,password) VALUES (NULL ,'$_POST[fname]', '$_POST[lname]', '$_POST[usernameu]', '$userid', '$password')";
	
	if ($conn->query($sql) === TRUE) {
		
		$_SESSION['username'] = $_POST[usernameu];
		header("location:WelcomePage.php");  
					
	} 
	else {
					
		echo "<div class='container'><div class=\"alert alert-danger\">Error due to connection failure.</div></div>";
					
	}
	
}