<?
require('_genie/genie.class.php');

$genie = new genie();
$genie->init( 'footer-css-cache' , 'css' , false );
$genie->addFile( '_stylesheets/global.css' );
genie->grandWish();
?>
