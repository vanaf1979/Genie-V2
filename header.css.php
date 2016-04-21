<?
require('_genie/genie.class.php');

$genie = new genie();
$genie->init( 'header-css-cache' , 'css' , false );
$genie->addFile( 'https://fonts.googleapis.com/css?family=Titillium+Web:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900' );
$genie->addFile( '_stylesheets/global.css' );
$genie->addFile( '_stylesheets/main.css' );

$genie->grandWish();
?>
