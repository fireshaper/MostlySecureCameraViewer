<?php
error_reporting(0); // Disable error reporting
session_start();	// Start PHP session handling

// Set your username and password here.
$secretusername = 'username';
$secretpassword = 'password';

// If the user already has a session, then it passes them on to the viewer page.
// Otherwise, it prompts them for the username/password and compares those
// to the ones you set above.
if ($_SESSION['authenticated'] == true) {
   header('Location: viewer.php');
} else {
   $error = null;
   if (!empty($_GET)) {
       $username = empty($_GET['username']) ? null : $_GET['username'];
       $password = empty($_GET['password']) ? null : $_GET['password'];

       if ($username == $secretusername && $password == $secretpassword) {
           $_SESSION['authenticated'] = true;
           // Redirect to your secure location
           header('Location: viewer.php');
           return;
       } else {
           $error = 'Incorrect username or password';
       }
   }
   echo $error;
   ?>
   
<html>
<head>
	<link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>

<body>
	<div class="login">
		<h1>Mostly Secure Camera Viewer</h1>
		<form action="index.php">
			Username: <input type="text" name="username" /><br />
			Password: <input type="password" name="password" /><br /><br />
			<input type="submit" value="login" style="background-color: grey;border: none;color: white;text-align: center;padding: 15px 32px;text-decoration: none;display: inline-block;font-size: 16px;" />
		</form>
	</div>
</body>
<?php
} ?>