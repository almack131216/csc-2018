<?php
//require_once('control/inc/db-connect.php');
session_start();

$filename = $_GET['file'];
$ctype = '';
// required for IE, otherwise Content-disposition is ignored
if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression', 'Off');

// addition by Jorg Weske
$file_extension = strtolower(substr(strrchr($filename,"."),1));

if( $filename == "" ) 
{
  echo "<html><title>Download Script</title><body>ERROR: download file NOT SPECIFIED.</body></html>";
  exit;
} elseif ( !file_exists( $filename ) ) 
{
  echo "<html><title>Download Script</title><body>ERROR: File not found.";
	echo '<br>FILE: '.$filename;
	echo '<br>IMG: <img src="'.$filename.'">';
	echo "</body></html>";
  exit;
};
switch( $file_extension )
{
  case "pdf": $ctype="application/pdf"; break;
  case "exe": $ctype="application/octet-stream"; break;
  case "zip": $ctype="application/zip"; break;
  case "doc": $ctype="application/msword"; break;
  case "xls": $ctype="application/vnd.ms-excel"; break;
  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
  case "gif": $ctype="image/gif"; break;
  case "png": $ctype="image/png"; break;
  case "txt": $ctype="image/text"; break;
  case "jpeg":
  case "jpg": $ctype="image/jpg"; break;
  case "mp3": $ctype="audio/mpeg3"; break;
}

if($ctype){
	
	/*
	// insert log
	$db = new dbConnect();
	
	// check if file already logged
	$sql = 'SELECT * FROM tbl_downloads WHERE fileName = \''.$filename .'\'';
	$result = $db->query($sql);
	$total = $db->getNumRows($result);
	
	if($total > 0)
	{	// update current log		
		$sql = 'UPDATE tbl_downloads SET totalDownloads = totalDownloads+1, lastDownloaded = \''.date( 'Y-m-d H:i:s', time() ).'\', lastUser = '.$_SESSION["userid"].' WHERE fileName = \''.mysql_real_escape_string($filename) .'\'';
	} else 
	{	// insert current file into log
		$sql = 'INSERT INTO tbl_downloads(fileName, totalDownloads, lastDownloaded, lastUser) VALUES (\''.mysql_real_escape_string($filename) .'\',1,\''.date( 'Y-m-d H:i:s', time() ).'\', '.$_SESSION["userid"].')';
	}
	
	$result = $db->query($sql);
	
	// Update Users Download Count
	$sqlUser = 'UPDATE tbl_members SET downloads = downloads+1 WHERE Id = \''.$_SESSION["userid"].'\'';
	$result = $db->query($sqlUser);
	*/
	
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
	header("Content-Type: $ctype");
	// change, added quotes to allow spaces in filenames, by Rajkumar Singh
	header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($filename));
	readfile("$filename");
	exit();
}
?>