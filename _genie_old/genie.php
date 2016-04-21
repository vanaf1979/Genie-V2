<?
//	------------------------------------------------------------------------------------------------------
//	INFO
//	------------------------------------------------------------------------------------------------------
//	Created by:	Stephan (Fregl) Nijman
//	Email:		stephan@mayij.com
//	Website:	http://www.mayij.com/genie
//	Docs:		http://www.mayij.com/genie/docs
//	------------------------------------------------------------------------------------------------------
//	End >

// Check de last atlered data van onze master file.
if ( $file_type == 'js' )
{
	// Als het over js files gaat halen we de last alterd date op van de compressed js file op.
	$masterfile_last_altered_date = filemtime( '_genie/compressed_master.js' );
}
else if ($file_type == 'css')
{
	// Als het over css files gaat halen we de last alterd date op van de compressed css file op.
	$masterfile_last_altered_date = filemtime( '_genie/compressed_master.css' );
}
else
{
	// Anders gaan we op ons bek.
	echo('No filetype was given');
	exit;
}

// Om straks te checken of onze master file ouder is dan 1 dag, maken we hier een timestamp voor 24 uur geleden
// Haal de tijd van dit moment op.
$now = time();
// Trek een dag af van de hierboven gemaakte timestamp
$a_day_ago = $now - ( ( 60 * 60 ) * 24 );

// Als we aan het debuggen zijn, of als de master file meer dan een dag oud is.
if ( $debug == true || $masterfile_last_altered_date < $a_day_ago  || $force_reset == true)
{
	if ( $file_type == 'js' )
	{
		for ( $i = 0 ; $i < count($files) ; $i++ )
		{
			if ( substr( $files[$i], 0, 7 ) === "http://" || substr( $files[$i], 0, 8 ) === "https://")
			{
				$array_for_js_compression[] = file_get_contents( $files[$i] );
			}
			else if ( file_exists( $files[$i] ) )
			{
				// Haal de content op van het huidige bestand, en voeg het toe aan een nieuw array index.
				$array_for_js_compression[] = file_get_contents( $files[$i] );
			}
			// Als de file niet bestaat, gooie we een error, en stoppen we het script.
			else
			{
				// Anders gaan we op ons bek.
				echo('One of the listed files does not exist on this server, or its file name / location is not correct.');
				exit;
			}
		}
	}
	else
	{
		// Loop door alle files
		for ( $i = 0 ; $i < count($files) ; $i++ )
		{
			// Check of het huidige bestand bestaat op deze server. (Dit word gecached door de server, dus zou niet veel vertraging op mogen leveren.)

			if ( substr( $files[$i], 0, 7 ) === "http://" || substr( $files[$i], 0, 8 ) === "https://")
			{
				$array_of_all_css_content[] = file_get_contents( $files[$i] );
			}
			else if ( file_exists( $files[$i] ) )
			{
				
				//$array_of_all_css_content[] = 'found: ' . $files[$i];
				// Haal de content op van het huidige bestand, en voeg het toe aan een nieuw array index.
				$array_of_all_css_content[] = file_get_contents( $files[$i] );
			}
			// Als de file niet bestaat, gooie we een error, en stoppen we het script.
			else
			{
				// Anders gaan we op ons bek.
				echo('One of the listed files does not exist on this server, or its file name / location is not correct.');
				exit;
			}
		}
	}
	
	// Nu compressen we alle data, afhankelijk van de filetype.
	// Js files worden gecompressed met jsmin of packer, en css files door simpelweg alle whitespaces te verwijderen.
	// TODO: Checken of jsmin beter / sneller is dan p.a.c.k.e.r.
	
	// Als we te maken hebben met javascript data.
	if ( $file_type == 'js' )
	{	
		
		$all_compressed_data = '';
		
		if ( count( $array_for_js_compression ) > 0 )
		{
			$alljsmincontent = implode( "\n", $array_for_js_compression );
			require '_genie/jsmin.php';
			$all_compressed_data = JSMin::minify($alljsmincontent);
		}
		
		header("content-type: application/x-javascript");
		
	}
	// Als we te maken hebben met css data.
	else if ( $file_type == 'css' )
	{
		// Maak een string van alle content die we hierboven in een array hebben verzameld
		// Note, implode is sneller dan het continue samen voegen van strings door midden van de .= operator.
		$content_of_all_files .= implode( "\n", $array_of_all_css_content );
		// remove all comments (thanx to http://www.catswhocode.com/blog/3-ways-to-compress-css-files-using-php)
		$all_compressed_data = minify( $content_of_all_files );
	}
	
	if ($debug == true && ! in_array($_SERVER['REMOTE_ADDR'], $list_of_dev_ips) || $force_reset == true)
	{
		// Dan schrijven we alle gecompreste data naar onze master file.
		if ( $file_type == 'js' )
		{
			// Open het bestand met schrijf rechten, of ga op je bek.
			$file_to_write_to = fopen( '_genie/compressed_master.js' , 'w' ) or die( "can't open file" );
			// Schrijf de gecompreste data naar het bestand.
			fwrite( $file_to_write_to , $all_compressed_data);
			// Sluit het bestand weer netjes.
			fclose($file_to_write_to);
		}
		else if ( $file_type == 'css' )
		{
			// Open het bestand met schrijf rechten, of ga op je bek.
			$file_to_write_to = fopen( '_genie/compressed_master.css' , 'w' ) or die( "can't open file" );
			// Schrijf de gecompreste data naar het bestand.
			fwrite( $file_to_write_to , $all_compressed_data);
			// Sluit het bestand weer netjes.
			fclose($file_to_write_to);
			
			header('Content-type: text/css');
		}
	}
	
	// en echoen we de data naar de browser.
	echo( $all_compressed_data );
}
// Als we niet aan het debuggen zijn, en onze master file is niet ouder dan 24 uur, dan hoeven we allen maar zn content terug te geven.
// Include is sneller dan file_get_contents, dus gebruiken we dat maar.
else
{
	// Als het om javascript data gaat.
	if ($file_type == 'js')
	{
		header("content-type: application/x-javascript");
		include('_genie/compressed_master.js');
	}
	// Als het om css data gaat.
	else if ($file_type == 'css')
	{
		header("Content-type: text/css");
		include('_genie/compressed_master.css');
	}
	// En anders gaan we op ons bek.
	else
	{
		echo('No file type, or unknown filetype was given.');
	}
}


function minify( $css ) {
	$css = preg_replace( '#\s+#', ' ', $css );
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );
	$css = str_replace( '; ', ';', $css );
	$css = str_replace( ': ', ':', $css );
	$css = str_replace( ' {', '{', $css );
	$css = str_replace( '{ ', '{', $css );
	$css = str_replace( ', ', ',', $css );
	$css = str_replace( '} ', '}', $css );
	$css = str_replace( ';}', '}', $css );

	return trim( $css );
}
?>