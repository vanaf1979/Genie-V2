<?
require('_genie/genie.class.php');

$genie = new genie();
$genie->init( 'header-js-cache' , 'js' , false );
$genie->addFile( 'https://cdnjs.cloudflare.com/ajax/libs/F2/1.4.0/f2.min.js' );
$genie->addFile( '_javascripts/_html5/html5-shiv.js' );
$genie->addFile( '_javascripts/_html5/modernizer.js' );
$genie->addFile( '_javascripts/_jquery/jquery.js' );
$genie->addFile( '_javascripts/_html5/modernizer.js' );
$genie->addFile( '_javascripts/_sitescripts/globals.js' );
$genie->grandWish();
?>
