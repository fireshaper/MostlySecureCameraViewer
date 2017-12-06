# Mostly Secure Camera Viewer

## Requirements
* A web server with PHP installed

*To install*
1. Download ZIP
1. Extract files to a folder on your server
1. Open index.php and edit the following lines:
	* Line 6: $secretusername = 'username';
	* Line 7: $secretpassword = 'password';
1. Save the file and close it.
1. Open viewer.php and edit the following lines:
	* Line 8: $_camUser = "guest";
	* Line 9: $_camPass = "password";
	* Edit the Camera IPs:
		* Line 14: $_cam1IPi = "0.0.0.0:80";
		* Line 15: $_cam1IPe = "domain.com:80";
1. Save the file and close it.

You can now visit the directory that you extracted the files into on your server and log in to start viewing your cameras.
