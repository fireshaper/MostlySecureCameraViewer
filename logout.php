<?php
session_start();
session_destroy();
?>

<html>
<head>
	<link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>

<body>
	<div style="margin: auto;width: 50%;padding: 10px;margin-top: 200px;text-align: center;">
	<?php
		echo 'You have been logged out. <br /><a href="index.php">Go back</a>'; 
	?>
	</div>
</body>
</html>