<?php


namespace CommentToCode\Parsers\Commands;

use CommentToCode\Parsers\Exceptions\ParserCommandException;

class PHPExecCommand extends BasePHPParserCommand
{
    /**
     * Executes a bit of raw PHP and returns it
     *
     * @param ParserCommandCallInfo $commandInfo
     * @param array $includedVariables The variables to be placed into the scope of the template
     *
     * @return string
     * 
     * @throws ParserCommandException
     */
    public function call(ParserCommandCallInfo $commandInfo, $includedVariables = [])
    {
        return $this->evalResult($commandInfo, $commandInfo->getArguments(), $includedVariables);
    }
}