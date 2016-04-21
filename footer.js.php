<?
require('_genie/genie.class.php');

$genie = new genie();
$genie->init( 'footer-js-cache' , 'js' , false );
$genie->addFile( '_javascripts/_sitescripts/globals.js' );
$genie->grandWish();
?>
