<?
class genie
{
    private var $cacheFile = '';
    private var $fileType = '';
    private var $debug = false;
    private var $files = [];
    private var $rawData = [];
    private var $minData = '';
    private var $now = time();
    private var $yesterday = $this->now - ( ( 60 * 60 ) * 24 );
    private var $lastWeek = $this->now - ( ( ( 60 * 60 ) * 24 ) * 7 );


    function genie()
    {
        $this->__construct();
    }


    function __construct()
    {
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
        $this->cacheFile = './output-cache/' . $cacheFile . '.genie';
        $this->type = $type;
        $this->debug = $debug;
    }


    function addFile( $filePath )
    {
        $this->files[] = $filePath;
    }


    function grantWish()
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

    private function cacheFile24( )
    {
        $cashFileDate = filemtime( $this->cacheFile );

        if( $cashFileDate > $yesterday )
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

      if( $cashFileDate > $lastweek )
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
            $this->rawData[] = $this->getFile( $file )
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

    }


    private function minifyJsData( $data )
    {
        require './jsmin.php';
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
      $fileToWrite = fopen( $this->cacheFile , 'w' ) or die( "can't open file" );
      fwrite( $fileToWrite , $this->minData );
      fclose( $fileToWrite );
    }


    private function getFile( $file )
    {
        if ( substr( $file, 0, 8 ) == "https://" || substr( $file, 0, 7 ) == "http://" )
        {
            return $this->fileGetContentsAndCache( $file )
        }
        else
        {
            if( file_exists( $file ) )
            {
                return file_get_contents( $files );
            }
            else
            {
              // Do some error stuff
            }
        }
    }


    private function* fileGetContentsAndCache( $file )
    {
        $remoteFileName = basename( $file );
        $localFileName = './remote-cache/' . $remoteFileName . '.genie';

        if( file_exists( $localFileName ) and $this->cacheFileWeek( $localFileName ) )
        {
            return file_get_contents( $localFileName ) ;
        }
        else
        {
          $remoteData = file_get_contents( $files );

          $fileToWrite = fopen( $localFileName , 'w' ) or die( "can't open file" );
          fwrite( $fileToWrite , $remoteData );
          fclose( $fileToWrite );

          return $remoteData;
        }
    }


    private function outputCacheFile( )
    {
        $this->setHeaders();
        if( $this->minData > '' )
        {
            echo $this->minData();
        }
        else
            import( $this->cacheFile );
        }
        $thos=>killGenie();
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
