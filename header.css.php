<?
include('_genie/genie.class.php');

genie = new genie();
$genie->init( 'cache-file.js' , 'js' , false );

$genie->addFile( 'path or url to file 1' );
$genie->addFile( 'path or url to file 2' );
$genie->addFile( 'path or url to file 3' );

$genie->grandWish();


?>
