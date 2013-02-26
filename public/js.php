<?php
// set the cache time seconds,minutes,hours,days
$offset = 60*60*24*1; 
$file = str_replace("../", "", $_GET['x']); // clean up file to avoid traversal attack
$path = explode('/', $file);
if ($path[0] == 'js') {
	array_shift($path);
}
$file = implode('/', $path);
$missing = true;
$fileNameKey = false;
$lastModifiedTime = false;
$output = array();

// enable gzip
ob_start ("ob_gzhandler");

// function to test if a file should be 304 or not.
function caching_headers ($file, $timestamp) {
	$is_304 = false;
	if (!$timestamp) {
		$timestamp = time();
	}
	$gmt_mtime = gmdate('r', $timestamp);
	header('ETag: "' . md5($timestamp . $file) .'"');
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
		if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime) || (isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5($timestamp.$file))) {
			header('HTTP/1.1 304 Not Modified');
			$is_304 = true;
		}
	}
	return array(
		'time_stamp' => $gmt_mtime,
		'is_304' => $is_304
	);
}

if (file_exists($file)) {
	$fileNameKey = $file;
	$lastModifiedTime = filemtime($file);
	$fileContent =  file_get_contents($file);
	$output[] = $fileContent;
	$missing = false;
}

if($missing) {
	header("HTTP/1.1 404 Not Found");
} else {
	$lm = caching_headers($fileNameKey, $lastModifiedTime);
	header("Content-type: text/javascript");
	header('Last-Modified: ' . $lm['time_stamp']);
	header("Cache-Control: public, max-age=" . $offset); 
	header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT"); 
	// send output if we don't have a 304
	if (!$lm || !$lm['is_304']) {
		echo implode('', $output);
	}
}

exit();