<?php


namespace CommentToCode\Parsers\Commands;

use CommentToCode\Parsers\Exceptions\ParserCommandException;

class PHPCSVPrependCommand extends PHPCSVCommand
{
    /**
     * Shorthand to prepend an array as a string of comma separated values
     * 
     * @note This ends with a comma if there are any values in the given array. If they array is empty it returns an empty string.
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
        $csv = parent::call($commandInfo, $includedVariables);
        
        if(empty($csv)){
            return '';
        }

        /** @noinspection PhpParamsInspection */
        return $csv . ', ';
    }
}