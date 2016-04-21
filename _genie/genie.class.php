<?
/*
Class Name: 	Genie
Description: 	Combine, Minify and Cache both local and remote static js/css files.
Author: 		  Vanaf1979
Author URI: 	http://vanaf1979.nl/
Version: 		  2.0
*/

class genie
{
    var $cacheFile = '';
    var $fileType = '';
    var $debug = false;
    var $files;
    var $rawData;
    var $minData = '';
    var $now = 0;
    var $yesterday = 0;
    var $lastWeek = 0;


    function __construct()
    {
      $this->now = time();
      $this->yesterday = $this->now - 86400;
      $this->lastWeek = $this->now - 604800;

      ob_start("ob_gzhandler");
      header( 'Expires: '.gmdate( 'D, d M Y H:i:s \G\M\T', time() + 2678400) );
    }


    function init( $cacheFile , $type , $debug = false )
    {
        $this->cacheFile = dirname(__FILE__) . '/output-cache/' . $cacheFile . '.genie';
        $this->type = $type;
        $this->debug = $debug;
    }


    function addFile( $filePath )
    {
        $this->files[] = $filePath;
    }


    function grandWish()
    {
        if ( ! $this->debug and $this->cacheFileExists() and $this->cacheFile24() )
        {
            $this->outputCacheFile();
        }
        else
        {
            $this->doMagic();
        }
    }


    private function cacheFileExists()
    {
        if( file_exists( $this->cacheFile ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function cacheFile24( )
    {
        $cashFileDate = filemtime( $this->cacheFile );

        if( $cashFileDate > $this->yesterday )
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    private function cacheFileWeek( $file )
    {
      $cashFileDate = filemtime( $file );

      if( $cashFileDate > $this->lastWeek )
      {
          return true;
      }
      else
      {
          return false;
      }
    }


    private function doMagic()
    {
        foreach( $this->files as $file )
        {
            $this->rawData[] = $this->getFile( $file );
        }

        if( $this->fileType == 'js' )
        {
            $this->minData = $this->minifyJsData( $this->rawData );
        }
        else
        {
            $this->minData = $this->minifyCssData( $this->rawData );
        }

        $this->rawData = '';

        $this->writeCacheFile();

        $this->outputCacheFile();

    }


    /*
    * Class at https://github.com/rgrove/jsmin-php
    * Have to find another sollution, jsmin is out of date.
    */
    private function minifyJsData( $data )
    {
        require dirname(__FILE__) . '_genie/jsmin.php';
        return JSMin::minify( implode( "\n", $data ) );
    }


    private function minifyCssData( $data )
    {
        $data = implode( "\n", $data );
        // remove comments
      	$data = preg_replace( '#\s+#', ' ', $data );
      	$data = preg_replace( '#/\*.*?\*/#s', '', $data );
        // remove whitespace
        $what = array( '; ' , ': ' , ' {', '{ ', ', ' , '} ' , ';}' );
        $with = array( ';' , ':' , '{', '{', ',' , '}' , '}' );
      	$data = trim( str_replace( $what, $with, $data );
        return $data;
    }


    private function writeCacheFile()
    {
        $fileToWrite = fopen( $this->cacheFile , 'w' ) or die( "can't open file" );
        fwrite( $fileToWrite , $this->minData );
        fclose( $fileToWrite );
    }


    private function getFile( $file )
    {
        if ( substr( $file, 0, 8 ) == "https://" || substr( $file, 0, 7 ) == "http://" )
        {
            return $this->fileGetContentsAndCache( $file );
        }
        else
        {
            if( file_exists( dirname( dirname(__FILE__) ) . '/' .  $file ) )
            {
                return file_get_contents( dirname( dirname(__FILE__) ) . '/' .  $file );
            }
        }
    }


    private function fileGetContentsAndCache( $file )
    {
        /* This can create very long filenames. Must look for a better way */
        $remoteFileName = base64_encode( basename( $file ) );
        $localFileName = dirname(__FILE__) . '/remote-cache/' . $remoteFileName . '.genie';

        if( file_exists( $localFileName ) and $this->cacheFileWeek( $localFileName ) )
        {
            return file_get_contents( $localFileName ) ;
        }
        else
        {
          $remoteData = file_get_contents( $file );

          $fileToWrite = fopen( $localFileName , 'w' ) or die( "can't open file" );
          fwrite( $fileToWrite , $remoteData );
          fclose( $fileToWrite );

          return $remoteData;
        }
    }


    private function outputCacheFile( )
    {
        $this->setHeader();

        if( $this->minData > '' )
        {
            echo $this->minData;
        }
        else
        {
            include( $this->cacheFile );
        }
        $this->killGenie();
    }


    private function setHeader()
    {
        if ( $this->fileType = 'css' )
        {
            header('Content-type: text/css');
        }
        else
        {
            header("content-type: application/x-javascript");
        }
    }


    private function killGenie()
    {
        unset( $this );
    }

}
?>
