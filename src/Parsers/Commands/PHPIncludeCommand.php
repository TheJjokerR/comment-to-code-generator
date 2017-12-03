<?php


namespace CommentToCode\Parsers\Commands;

use CommentToCode\Parsers\Exceptions\ParserCommandException;

class PHPIncludeCommand extends BasePHPParserCommand
{
    /**
     * Includes a template and also parses it
     * 
     * @TODO Make this work and derive this from a base command as it will mostly be the same for other languages
     *
     * @param string $arguments
     *
     * @return string
     * 
     * @throws ParserCommandException
     */
    public function call($arguments, $includedVariables = [])
    {
        $fileToInclude = $this->evalResult("\"$arguments\"", $includedVariables);
        
        return $fileToInclude;
    }
}