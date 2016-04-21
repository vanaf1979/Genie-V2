<?
ob_start("ob_gzhandler");
header( 'Expires: '.gmdate( 'D, d M Y H:i:s \G\M\T', time() + (((60 * 60) * 24) * 31)) );
//	------------------------------------------------------------------------------------------------------
//	INFO
//	------------------------------------------------------------------------------------------------------
//	Created by:	Stephan Nijman
//	Email:		stephan@nirb.nl
//	Website:	http://www.nirb.nl
//	Docs:		http://www.nirb.nl
// 	______________________________________________________________________________________________________
//	------------------------------------------------------------------------------------------------------
//	SETTINGS:
//	------------------------------------------------------------------------------------------------------
	$debug 					= false;		// true or false.
	$file_type 				= 'js';		// js or css.
	$js_compression_type 	= 'jsmin'; 	// jsmin or none.
	$force_reset			= true;		// Force the master file to be recompiled
	$list_of_dev_ips = array
	(
		'84.104.78.57',
		'0.0.0.0'
	);
// 	______________________________________________________________________________________________________
//	------------------------------------------------------------------------------------------------------
//	LIST OF FILES: The last line in the list, may not be ended with a comma.
//	------------------------------------------------------------------------------------------------------
	$files = array
	(
		'_javascripts/_jquery/jquery.js',
		'_javascripts/_html5/modernizr.js',
		'_javascripts/_html5/html5-shiv.js',
		'http://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/jquery.form-validator.min.js',
		'_javascripts/_plugins/mmenu.js',
		'_javascripts/_sitescripts/globals.js',
		'_javascripts/_sitescripts/site.js'
	);
// 	______________________________________________________________________________________________________
//	------------------------------------------------------------------------------------------------------
//	DO NOT EDIT ANYTHING BELOW THIS LINE: Something wicked goes there! ;)
// 	------------------------------------------------------------------------------------------------------
	include('_genie/genie.php');
// 	______________________________________________________________________________________________________
?>
