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
     * @param string $arguments
     * @param array $includedVariables The variables to be placed into the scope of the template
     *
     * @return string
     *
     * @throws ParserCommandException
     */
    public function evalResult($arguments, $includedVariables = [])
    {
        $result = NULL;
        $end = '';
    
        if(substr($arguments, -1) !== ';'){
            $end = ';';
        }
    
        try{
            extract($includedVariables);
            $result = eval("return $arguments $end");
        }catch(\Exception $e){
            throw new ParserCommandException("{$this->name} callback caused an error! Message: {$e->getMessage()}");
        }
        
        return $result;
    }

}
