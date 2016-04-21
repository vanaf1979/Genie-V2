<?

error_reporting(E_ALL);
ini_set("display_errors", 1);

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
      $this->yesterday = $this->now - ( ( 60 * 60 ) * 24 );
      $this->lastWeek = $this->now - ( ( ( 60 * 60 ) * 24 ) * 7 );

      ob_start("ob_gzhandler");
      header( 'Expires: '.gmdate( 'D, d M Y H:i:s \G\M\T', time() + (((60 * 60) * 24) * 31)) );
    }

    /*
    * Convert an object to an array
    *
    * @param    string  $cacheFile  Name of the output cachefile
    * @param    string  $type       Js or class
    * @param    bool    $debug      Force cachefile rebuilding
    * @return   nothing
    */
    function init( $cacheFile , $type , $debug = false  )
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

    // Private functions

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


    private function minifyJsData( $data )
    {
        echo dirname(__FILE__) . '_genie/jsmin.php';
        require dirname(__FILE__) . '_genie/jsmin.php';
        return JSMin::minify( implode( "\n", $data ) );
    }


    private function minifyCssData( $data )
    {
        // thanx to http://www.catswhocode.com/blog/3-ways-to-compress-css-files-using-php
        $data = implode( "\n", $data );
      	$data = preg_replace( '#\s+#', ' ', $data );
      	$data = preg_replace( '#/\*.*?\*/#s', '', $data );
      	$data = str_replace( '; ', ';', $data );
      	$data = str_replace( ': ', ':', $data );
      	$data = str_replace( ' {', '{', $data );
      	$data = str_replace( '{ ', '{', $data );
      	$data = str_replace( ', ', ',', $data );
      	$data = str_replace( '} ', '}', $data );
      	$data = str_replace( ';}', '}', $data );
        return trim( $data );
    }


    private function writeCacheFile()
    {
        //echo $this->cacheFile;
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
            //echo dirname( dirname(__FILE__) ) . '/' .  $file;
            if( file_exists( dirname( dirname(__FILE__) ) . '/' .  $file ) )
            {
                return file_get_contents( dirname( dirname(__FILE__) ) . '/' .  $file );
            }
            else
            {
              // Do some error stuff
            }
        }
    }


    private function fileGetContentsAndCache( $file )
    {
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
