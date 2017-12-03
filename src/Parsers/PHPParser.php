<?php

namespace CommentToCode\Parsers;
use CommentToCode\Parsers\Commands\ParserCommand;
use CommentToCode\Parsers\Commands\ParserCommandCallInfo;
use CommentToCode\Parsers\Commands\PHPExecCommand;
use CommentToCode\Parsers\Commands\PHPIncludeCommand;
use CommentToCode\Parsers\Commands\PHPCSVCommand;
use CommentToCode\Parsers\Commands\PHPCSVPrependCommand;
use CommentToCode\Parsers\Commands\PHPCSVAppendCommand;

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
        
        $this->annotationStart = '/*/>';
        $this->annotationDelimiter = ':';
        $this->annotationEnd = '/*/';

        $this->addCommands(new PHPExecCommand('exec'));
        $this->addCommands(new PHPIncludeCommand('include'));
        $this->addCommands(new PHPCSVCommand('csv'));
        $this->addCommands(new PHPCSVPrependCommand('csv-prepend'));
        $this->addCommands(new PHPCSVAppendCommand('csv-append'));
        
        // An example of how to create a command without having to make a class.
        $this->addCommands(new ParserCommand('string', function(ParserCommandCallInfo $commandCallInfo, $includedVariables = []){
            extract($includedVariables);
            return "'" . eval("return addcslashes(\"{$commandCallInfo->getArguments()}\", '\'\\\\');") . "'";
        }));
        
        $this->defaultCommand = 'string';
    }

}
