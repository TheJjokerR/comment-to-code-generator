<?php


namespace CommentToCode\Parsers\Commands;

use CommentToCode\Parsers\Exceptions\ParserCommandException;

class PHPCSVCommand extends BasePHPParserCommand
{
    /**
     * Shorthand to return an array as a string of comma separated values
     * 
     * @note If they array is empty it returns an empty string.
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
        $arr = $this->evalResult($commandInfo, $commandInfo->getArguments(), $includedVariables);
        
        if(empty($arr)){
            return '';
        }

        /** @noinspection PhpParamsInspection */
        return implode(', ', $arr);
    }
}