<?php

namespace CommentToCode\Parsers\Exceptions;

use CommentToCode\Parsers\Commands\ParserCommandCallInfo;
use Throwable;

class ParserCommandException extends \Exception {
    
    public function __construct(ParserCommandCallInfo $commandInfo, $message = "", $code = 0, Throwable $previous = null)
    {
        $prepend = "empty command failed: $message";
        
        if(!empty($commandInfo)){
            $name = $commandInfo->getName();
            $fileName = $commandInfo->getFile();
            $arguments = $commandInfo->getArguments();
            $characterNum = $commandInfo->getCharacterNum();

            $prepend = "'$name' with arguments: '$arguments' ! Error message: $message @ $fileName:$characterNum: ";
        }
        
        parent::__construct("{$prepend}", $code, $previous);
    }
    
}