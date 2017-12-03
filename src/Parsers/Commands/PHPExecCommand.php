<?php


namespace CommentToCode\Parsers\Commands;

use CommentToCode\Parsers\Exceptions\ParserCommandException;

class PHPExecCommand extends BasePHPParserCommand
{
    /**
     * Executes a bit of raw PHP and returns it
     *
     * @param string $arguments
     * @param array $includedVariables The variables to be placed into the scope of the template
     *
     * @return string
     * 
     * @throws ParserCommandException
     */
    public function call($arguments, $includedVariables = [])
    {
        return $this->evalResult($arguments, $includedVariables);
    }
}