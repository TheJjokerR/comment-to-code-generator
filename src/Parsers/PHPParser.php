<?php

namespace CommentToCode\Parsers;
use CommentToCode\Parsers\Commands\ParserCommand;
use CommentToCode\Parsers\Commands\PHPExecCommand;
use CommentToCode\Parsers\Commands\PHPIncludeCommand;

/**
 * This is the PHP parser class.
 *
 * It decides how PHP should be converted to code
 */
class PHPParser extends BaseParser
{

    /**
     * Create a new parser instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->annotationStart = '/*c2c';
        $this->annotationDelimiter = ':';
        $this->annotationEnd = '*/';

        $this->addCommands(new PHPExecCommand('-exec'));
        $this->addCommands(new PHPIncludeCommand('-include'));
        $this->addCommands(new ParserCommand('-string', function($command, $arguments, $includedVariables = []){
            extract($includedVariables);
            return "'" . eval("return addcslashes(\"$arguments\", '\'\\\\');") . "'";
        }));
        
        $this->defaultCommand = '-string';
    }

}
