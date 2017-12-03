<?php

namespace CommentToCode\Generators;

use CommentToCode\Parsers\BaseParser;
use Illuminate\Filesystem\Filesystem;

/**
 * This is the base generator class.
 *
 * It provide the functions required by all derivatives needed to generate code. 
 */
class FileGenerator extends BaseGenerator
{
    
    /**
     * Generate the code to a file
     *
     * @param string $filePath
     * @param bool $force
     *
     * @return void
     */
    public function generateToFile($filePath, $force = false)
    {
        if (!$force && file_exists($filePath)) {
            throw new Exceptions\FileGeneratorException("$filePath already exists! Not forcing as per request");
        }

        $directory = dirname($filePath);

        if (!file_exists($directory)) {
            if(!$force) throw new Exceptions\FileGeneratorException("$filePath parent directory does not exist! Not forcing as per request");

            mkdir($directory, 0777, true);
        }

        // Declare and open the file
        $fileHandle = fopen($filePath, 'w');
        
        // Write as we get more information
        $this->getParser()->setAppendGeneratedCodeCallback(function($part) use ($fileHandle){
            fwrite($fileHandle, $part);
        });
        
        $code = $this->generate();
        fclose($fileHandle);
    }
    
}
