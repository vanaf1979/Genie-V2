## Genie V2.0 by vanaf1979.nl - vanaf1979@gmail.com

## PURPOS:
Combine, Minify and Cache both local and remote static js/css files.

## CHANGES FROM V1:
  - Made the whole thing class based.
  - Added remote files.
  - Added local caching of remote files.
  - Made it open-source under GNU GENERAL PUBLIC LICENSE

## BENEFITS:
  - Compress all the data and Gzip for less bandwidth.
  - Reduce the number of http requests. (Both browser and server-side)
  - Reduce the number of Dns lookups. (Fetch and Cache remote files server-side)
  - Optimize browser and cdn caching.

## USAGE:
  - Include the genie.class.php file.
  - Create a new genie instance.
  - Provide a unique name for the output.
  - Provide the content type. (js/css)
  - Provide the debug setting. (true/false defualts to false)

## EXAMPLE:
  - $genie = new Genie( 'cache-file.js' , 'js' , false );
  - $genie->init( 'cache-file.js' , 'js' , false );
  - $genie->addFile( 'path or local file 1' );
  - $genie->addFile( 'path or local file 2' );
  - $genie->addFile( 'path or remote file 1' );
  - $genie->addFile( 'path or remote file 2' );
  - $genie->grandWish();

## PROCESS:
  - Check if the debug mode is false.
    - if so, Check if a cache-file already exist.
      - if so, check to see if it has been made within the past 24 hours.
        - if so, return the contents of the cache-file.

  - If not and one of the test fails.
    - loop through all the files, and grab there name, size and content.
      - If a file is a remote file (requested over http/https).
        - Sanitize the filename.
        - Check if we already made a local copy that is less then a week old.
          - If not, create or update a local version.

      - If a file is local, grab its content.

    - Minify all the data using a js or css minify method.
    - Check to see if the cache-file already exist.
      - If not, create it.
    - write all the compressed data to the cache-file and save/close it.
    - Send the appropriate headers for js or css.
    - Send the data to the browser.
    - Send the genie back to its bottle destroy the class instance.

## INSTALLATION
  - Just grab the code, add it to your themes or site files root.
  - Check the demo files ( demo.php and header.css.php etc ) its very simple.
  - Make sure your /output-cache and /remote-cache folder are writable.

## TODO
  - Preserve .js and .css filenames in html source (through .htaccess?)
  - Find alternative for jsmin.
  - Add workflow for browser checks.


## Questions & Answers
**Q:** I need help?  
**A:** open a issue.
