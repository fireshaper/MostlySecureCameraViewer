<?php 
error_reporting(0); // Disable error reporting
session_start();	// Start PHP session handling

// Set your username for the cameras here. Remember, if anyone else gets access
// to the page they will see this in the HTML. You may want to create a generic username
// for this page to use like "guest" or "camviewer".
$_camUser = "guest";
$_camPass = "password";

// I keep track of the camera URLs up here, both for internal and external.
// By default I only have 3 cameras set up, but you can use more or less, just make sure
// that you add or remove the buttons you need/don't need.
$_cam1IPi = "0.0.0.0:80";
$_cam1IPe = "domain.com:80";

$_cam2IPi = "0.0.0.0:80";
$_cam2IPe = "domain.com:80";

$_cam3IPi = "0.0.0.0:80";
$_cam3IPe = "domain.com:80";

// This function checks to see if the user connecting to the camera is on the same network
// as the camera. The whitelist array should contain any IP addresses that the camera
// could be accessed from internally. Wildcards are accepted.
function isAllowed($ip){
    $whitelist = array('127.0.0.1', '::1', '10.0.0.*', '192.168.1.*');

    // If the ip is matched, return true
    if(in_array($ip, $whitelist))
        return true;
    else{
        foreach($whitelist as $i){
            $wildcardPos = strpos($i, "*");
            // Check if the ip has a wildcard
            if($wildcardPos !== false && substr($_SERVER['REMOTE_ADDR'], 0, $wildcardPos) . "*" == $i)
                return true;
        }
    }

    return false;
}

// This function checks to see if the camera is connected or not and returns it to the button style
// so that it can set the correct color. Thanks to Zerconian for this.
function isConnected($camIP){
	
	$camURL = explode(':', $camIP);
	$camHost = $camURL[0];
	$camPort = $camURL[1];
	
	$waitTimeoutInSeconds = 1; 
	if($fp = @fsockopen($camHost,$camPort,$errCode,$errStr,$waitTimeoutInSeconds)){ 
		return true;
		fclose($fp);
	} else {
		return false;
	}	
}

// This function creates the stream URL.
// It needs the camera name, an integer to denote the user's location, and the size of the video width in pixels.
function concatURL($whichCam, $location, $size){
	
	global $_camUser, $_camPass;
	global $_cam1IPi, $_cam1IPe, $_cam2IPi, $_cam2IPe, $_cam3IPi, $_cam3IPe; 
	
	if($location == 0){ // Internal user
		// MJPEG stream
		if($whichCam == "Camera1"){
			$streamURL = $_cam1IPi . '/snapshot.cgi?user=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		} else if($whichCam == "Camera2"){
			$streamURL = $_cam2IPi . '/snapshot.cgi?user=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		} else if($whichCam == "Camera3"){
			$streamURL = $_cam3IPi . '/snapshot.cgi?user=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		}
		
		// H.264 stream
		/*
		if($whichCam == "Camera1"){
			$streamURL = $_cam1IPi . '/CGIProxy.fcgi?cmd=snapPicture2&usr=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		} else if($whichCam == "Camera2"){
			$streamURL = $_cam2IPi . '/CGIProxy.fcgi?cmd=snapPicture2&usr=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		} else if($whichCam == "Camera3"){
			$streamURL = $_cam3IPi . '/CGIProxy.fcgi?cmd=snapPicture2&usr=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		}
		*/
	} else { // External user
		// MJPEG stream
		if($whichCam == "Camera1"){
			$streamURL = $_cam1IPe . '/snapshot.cgi?user=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		} else if($whichCam == "Camera2"){
			$streamURL = $_cam2IPe . '/snapshot.cgi?user=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		} else if($whichCam == "Camera3"){
			$streamURL = $_cam3IPe . '/snapshot.cgi?user=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		}
		
		// H.264 stream
		/*
		if($whichCam == "Camera1"){
			$streamURL = $_cam1IPe . '/CGIProxy.fcgi?cmd=snapPicture2&usr=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		} else if($whichCam == "Camera2"){
			$streamURL = $_cam2IPe . '/CGIProxy.fcgi?cmd=snapPicture2&usr=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		} else if($whichCam == "Camera3"){
			$streamURL = $_cam3IPe . '/CGIProxy.fcgi?cmd=snapPicture2&usr=' . $_camUser . '&pwd=' . $_camPass . '&t=" width="' . $size . '" onload=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 1000)\' onerror=\'setTimeout(function() {src = src.substring(0, (src.lastIndexOf("t=")+2))+(new Date()).getTime()}, 5000)\' alt=\'\' />';
		}
		*/
	}
		
	return $streamURL;
}

// Checking if the user has arrived from the login page.
// If not, send them back to the login page.
if ($_SESSION['authenticated'] == false) {
	header('Location: index.php');
} else
	$cam = $_GET['camera'];
?>

<html>
  <head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
	<title>Mostly Secure Camera Viewer</title>
  </head>

  <body>
    <h1>Mostly Secure Camera Viewer</h1>
    
    <div class="sidebar">
      <ul>
			<form action="viewer.php">
			<li><input type="submit" name="camera" value="All" style="background-color: white;border: none;text-align: center;padding: 15px 32px;text-decoration: none;display: inline-block;font-size: 16px;"/></li>
            <li><input type="submit" name="camera" value="Camera1" style="
			<?php 
				if(isConnected($_cam1IPi) || isConnected($_camIPe))
					echo 'background-color: green;border: none;color: white;text-align: center;padding: 15px 32px;text-decoration: none;display: inline-block;font-size: 16px;';
				else
					echo 'background-color: red;border: none;color: white;text-align: center;padding: 15px 32px;text-decoration: none;display: inline-block;font-size: 16px;';
			?>
			"/></li>
			<li><input type="submit" name="camera" value="Camera2" style="
			<?php 
				if(isConnected($_cam2IPi) || isConnected($_cam2IPe))
					echo 'background-color: green;border: none;color: white;text-align: center;padding: 15px 32px;text-decoration: none;display: inline-block;font-size: 16px;';
				else
					echo 'background-color: red;border: none;color: white;text-align: center;padding: 15px 32px;text-decoration: none;display: inline-block;font-size: 16px;';
			?>
			"/></li>
			<li><input type="submit" name="camera" value="Camera3" style="
			<?php 
				if(isConnected($_cam3IPi) || isConnected($_cam3IPe))
					echo 'background-color: green;border: none;color: white;text-align: center;padding: 15px 32px;text-decoration: none;display: inline-block;font-size: 16px;';
				else
					echo 'background-color: red;border: none;color: white;text-align: center;padding: 15px 32px;text-decoration: none;display: inline-block;font-size: 16px;';
			?>
			"/></li>
			</form>
      </ul>
	  <h2><a href="logout.php">Log Out</a></h2>
    </div>
    <div class="content">
	<?php 
		if ($cam == "Camera1"){
			if (isAllowed($_SERVER['REMOTE_ADDR'])) // If the user is local to the camera.
				echo '<img src="http://' . concatURL($cam, 0, 640);
			else 									// If the user is outside the LAN.
				echo '<img src="http://' . concatURL($cam, 1, 640);
		} else if ($cam == "Camera2"){
			if (isAllowed($_SERVER['REMOTE_ADDR'])) 
				echo '<img src="http://' . concatURL($cam, 0, 640);
			else 
				echo '<img src="http://' . concatURL($cam, 1, 640);
		} else if ($cam == "Camera3"){ /* 			// Third camera not set up yet.
			if (isAllowed($_SERVER['REMOTE_ADDR'])) 
				echo '<img src="http://' . concatURL($cam, 0, 640);
			else 
				echo '<img src="http://' . concatURL($cam, 1, 640);
			*/
		} else if ($cam == NULL || "All"){
			if (isAllowed($_SERVER['REMOTE_ADDR'])){
				echo '<img src="http://' . concatURL("Camera1", 0, 480);
				echo '<img src="http://' . concatURL("Camera2", 0, 480);
				//echo '<img src="http://' . concatURL("Camera3", 0, 480);
			} else {
				echo '<img src="http://' . concatURL("Camera1", 1, 480);
				echo '<img src="http://' . concatURL("Camera2", 1, 480);
				//echo '<img src="http://' . concatURL("Camera3", 1, 480);	
			}
		} else
			echo 'Error finding camera.';
	?>
      
    </div>
  </body>
</html>
