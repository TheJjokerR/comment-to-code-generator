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
     * @param ParserCommandCallInfo $commandInfo
     * @param array $includedVariables
     *
     * @return string
     * 
     * @throws ParserCommandException
     */
    public function call(ParserCommandCallInfo $commandInfo, $includedVariables = [])
    {
        $parser = $this->getParser();
        
        $fileToInclude = $this->evalResult($commandInfo, "\"{$commandInfo->getArguments()}\"", $includedVariables);
        $dirOfParent = dirname($parser->getTemplate());
        $pathToTemplate = "$dirOfParent/$fileToInclude";
        
        if(! file_exists($pathToTemplate))
            throw new ParserCommandException($commandInfo, "file '$pathToTemplate' was not found");
        
        if($parser->getTemplate() === $pathToTemplate)
            throw new ParserCommandException($commandInfo, 'self inclusion detected (the sub-template is the same as the parent-template)');
        
        $subTemplateParser = $parser->createSubParser();
        $subTemplateParser->setTemplate($pathToTemplate);
        
        $generatedCode = $subTemplateParser->parse();
        
        return $generatedCode;
    }
}