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
//
//	To combine multiple media css files, use the @media object in you css files.
//	@media print {
//	/* style sheet for print goes here */
//	}
// 	______________________________________________________________________________________________________
//	------------------------------------------------------------------------------------------------------
//	SETTINGS:
//	------------------------------------------------------------------------------------------------------
	$debug 					= true;		// true or false.
	$file_type 				= 'css';		// js or css.
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
		'_stylesheets/_plugins/_html5/reset.css',
		'http://yui.yahooapis.com/pure/0.6.0/pure-min.css',
		//'http://yui.yahooapis.com/pure/0.6.0/grids-responsive-old-ie-min.css',
		'http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css',
		'https://fonts.googleapis.com/css?family=Titillium+Web:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900',
		'_stylesheets/_plugins/_mmenu/mmenu.css',
		'_stylesheets/_plugins/_mmenu/mmenu.positioning.css',
		'_stylesheets/_plugins/_mmenu/mmenu.themes.css',
		'_stylesheets/main.css'
	);
// 	______________________________________________________________________________________________________
//	------------------------------------------------------------------------------------------------------
//	DO NOT EDIT ANYTHING BELOW THIS LINE: Something wicked goes there! ;)
// 	------------------------------------------------------------------------------------------------------
	include('_genie/genie.php');
// 	______________________________________________________________________________________________________
?>
