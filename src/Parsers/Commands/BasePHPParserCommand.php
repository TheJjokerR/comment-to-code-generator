<?php

namespace CommentToCode\Parsers\Commands;

use CommentToCode\Parsers\Exceptions\ParserCommandException;

/**
 * This is the PHP parser command class.
 *
 * It specifies how certain commands are interpreted 
 */
class BasePHPParserCommand extends ParserCommand
{

    /**
     * Returns the evaluated result (raw executed PHP returned)
     *
     * @param ParserCommandCallInfo $commandInfo
     * @param string $toEval
     * @param array $includedVariables The variables to be placed into the scope of the template
     *
     * @return string
     *
     * @throws ParserCommandException
     */
    public function evalResult(ParserCommandCallInfo $commandInfo, $toEval, $includedVariables = [])
    {
        $result = NULL;
        $end = '';
    
        if(substr($toEval, -1) !== ';'){
            $end = ';';
        }
    
        try{
            extract($includedVariables);
            $result = eval("return $toEval $end");
        }catch(\ParseError $e) {
            throw new ParserCommandException($commandInfo, "callback caused a syntax with message: {$e->getMessage()}");
        }catch(\Exception $e){
            throw new ParserCommandException($commandInfo, "callback caused an exception with message: {$e->getMessage()}");
        }
        
        return $result;
    }

}
