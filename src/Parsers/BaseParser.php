<?php

namespace CommentToCode\Parsers;

use CommentToCode\Parsers\Commands\ParserCommand;
use CommentToCode\Parsers\Exceptions\ParserCommandException;
use CommentToCode\Parsers\Exceptions\ParserException;
use CommentToCode\Common\Exceptions\NotImplementedException;

/**
 * This is the base parser class.
 *
 * It decides what should be converted to code 
 */
abstract class BaseParser
{

    /**
     * Whether native code can be injected into templates and run. (does not affect 'exec' command)
     *
     * @var bool
     */
    protected $allowNativeCode;
    
    /**
     * What template file to parse
     *
     * @var string
     */
    protected $template;

    /**
     * The variables to inject into the page.
     *
     * @var array
     */
    protected $variables;

    /**
     * The parsed code
     *
     * @var string
     */
    protected $code;

    /**
     * The commands that will be available for this parser
     *
     * @var array
     */
    protected $commands;

    /**
     * The default command name for this parser, used when none is given.
     *
     * @var string
     */
    protected $defaultCommand;

    /**
     * The starting point of an annotation with commands
     *
     * @var string
     */
    protected $annotationStart;

    /**
     * The end point of an annotation with commands
     *
     * @var string
     */
    protected $annotationEnd;

    /**
     * The delimiter of an annotation with commands which is followed by arguments for the command
     *
     * @var string
     */
    protected $annotationDelimiter;

    /**
     * @var string
     */
    protected $generatedCode;
    
    /**
     * @var callable
     */
    protected $appendGeneratedCodeCallback;

    /**
     * @var int
     */
    private $lastAnnotationStartedAt;

    /**
     * @var int
     */
    private $lastAnnotationEndedAt;

    /**
     * @var int
     */
    private $lastAnnotationDelimitedAt;

    /**
     * @var string
     */
    private $lastAnnotationCommand;

    /**
     * @var string
     */
    private $lastAnnotationArguments;

    /**
     * Create a new parser instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Default values:
        $this->allowNativeCode = false;
        $this->template = NULL;
        $this->variables = [];
        $this->commands = [];
        $this->defaultCommand = NULL;
        $this->annotationStart = '/*c2c';
        $this->annotationDelimiter = ':';
        $this->annotationEnd = '*/';
    }

    /**
     * Open the template and return the original code
     *
     * @internal
     *
     * @return string
     */
    protected function openTemplate(){
        $fileHandle = fopen($this->template, "r");
        $templateCode = fread($fileHandle, filesize($this->template));
        fclose($fileHandle);
        
        return $templateCode;
    }

    /**
     * Parse the template and return the generated code
     *
     * @return string
     *
     * @throws ParserException
     * @throws ParserCommandException
     */
    public function parse()
    {
        $templateCode = $this->openTemplate();

        $codeLength = strlen($templateCode);
        
        $this->generatedCode = '';

        for($pointer = 0; $pointer < $codeLength; $pointer++) {
            $generatedPart = $this->step($templateCode, $pointer, $codeLength);
            
            $this->appendGeneratedCode($generatedPart);
        }

        return $this->generatedCode;
    }

    /**
     * Step through the code
     *
     * @TODO split this mess up into smaller parts and not have one big method
     * 
     * @param string $code
     * @param int &$pointer
     * @param int $pointerMax
     *
     * @return string
     *
     * @throws ParserException 
     * @throws ParserCommandException
     */
    public function step($code, &$pointer, $pointerMax)
    {
        $startAnnotationLength = strlen($this->annotationStart);

        // Check if we found the start of an annotation
        if(substr($code, $pointer, $startAnnotationLength) === $this->annotationStart){

            if($this->lastAnnotationEndedAt < $this->lastAnnotationStartedAt)
                throw new ParserException("New annotation started without ending previous one @ character #$pointer");

            $this->lastAnnotationStartedAt = $pointer;
            
            $pointer += $startAnnotationLength;

            // Check how long the command is before the delimiter ends
            $delimiterPosition = strpos($code, $this->annotationDelimiter, $pointer);

            if(!$delimiterPosition)
                throw new ParserException("No delimiter found for annotation @ character #$pointer");

            $this->lastAnnotationCommand = substr($code, $pointer, $delimiterPosition - $pointer);
            $this->lastAnnotationDelimitedAt = $delimiterPosition;

            // Skip after the delmiter and proceed
            $pointer = $delimiterPosition + 1;
        }
        
        // Check if there's a delimiter that has not ended yet
        if($this->lastAnnotationDelimitedAt > $this->lastAnnotationEndedAt){
            $annotationEndLength = strlen($this->annotationEnd);
            
            // Check if there is an end defined at our pointer
            if(substr($code, $pointer, $annotationEndLength) === $this->annotationEnd){
                $this->lastAnnotationEndedAt = $pointer;
                
                // Trim the command and arguments
                $command = trim($this->lastAnnotationCommand);
                $arguments = trim($this->lastAnnotationArguments);

                $pointer += $annotationEndLength - 1;
                
                // Run the command with the arguments and return the output to be put in the generated code
                $output = $this->runCommand($command, $arguments);
                
                // Reset some data
                $this->lastAnnotationCommand = '';
                $this->lastAnnotationArguments = '';
                
                return $output;
            }
            
        }
        
        $steppedChar = substr($code, $pointer, 1);
        
        // Check if we've found a delimiter but have not yet reached the end of an annotation
        if($this->lastAnnotationDelimitedAt > $this->lastAnnotationEndedAt){
            $this->lastAnnotationArguments .= $steppedChar;
            
            return '';
        }
        
        return $steppedChar;
    }

    /**
     * Step through the code
     *
     * @param string $command
     * @param string $arguments
     *
     * @return string
     *
     * @throws ParserCommandException
     */
    public function runCommand($command, $arguments)
    {
        $className = get_class($this);
        
        if(empty($command)){
            if(empty($this->defaultCommand))
                throw new ParserCommandException("No command given and no default command set in parser '{$className}'");
            
            $command = $this->defaultCommand;
        }
    
        if(!isset($this->commands[$command]))
            throw new ParserCommandException("$command command does not exist in parser '{$className}'");
        
        return $this->commands[$command]->call($arguments, $this->variables);
    }

    /**
     * @return string
     */
    public function getGeneratedCode()
    {
        return $this->generatedCode;
    }

    /**
     * @param string $generatedCode
     */
    public function setGeneratedCode($generatedCode)
    {
        $this->generatedCode = $generatedCode;
    }

    /**
     * @return callable
     */
    public function getAppendGeneratedCodeCallback()
    {
        return $this->appendGeneratedCodeCallback;
    }

    /**
     * @param callable $appendGeneratedCodeCallback
     */
    public function setAppendGeneratedCodeCallback($appendGeneratedCodeCallback)
    {
        $this->appendGeneratedCodeCallback = $appendGeneratedCodeCallback;
    }

    /**
     * @return string
     */
    public function getAnnotationStart()
    {
        return $this->annotationStart;
    }

    /**
     * @param string $annotationStart
     */
    public function setAnnotationStart($annotationStart)
    {
        $this->annotationStart = $annotationStart;
    }

    /**
     * @return string
     */
    public function getAnnotationEnd()
    {
        return $this->annotationEnd;
    }

    /**
     * @param string $annotationEnd
     *
     * @return void
     */
    public function setAnnotationEnd($annotationEnd)
    {
        $this->annotationEnd = $annotationEnd;
    }

    /**
     * @return string
     */
    public function getAnnotationDelimiter()
    {
        return $this->annotationDelimiter;
    }

    /**
     * @param string $annotationDelimiter
     *
     * @return void
     */
    public function setAnnotationDelimiter($annotationDelimiter)
    {
        $this->annotationDelimiter = $annotationDelimiter;
    }

    /**
     * Set the parsed code
     * 
     * @param string $code
     *
     * @return void
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get the parsed code
     * 
     * @return string
     * 
     * @throws ParserException
     * @throws ParserCommandException
     */
    public function getCode($fromCache = false)
    {
        $code = $this->code;
        
        if(!empty($code) && $fromCache){
            return $code;
        }
        
        return $this->code = $this->parse();
    }

    /**
     * @param array $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * @param ParserCommand $command
     */
    public function addCommands(ParserCommand $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Set whether the parser will inject native code into the template (if it is given in a variable)
     *
     * @TODO Make this escape PHP or not when it is placed into templates
     * 
     * @param bool $allow
     *
     * @return void
     */
    public function setAllowNativeCode($allow = true){
        $this->allowNativeCode = $allow;
        
        throw new NotImplementedException();
    }

    /**
     * Get whether the parser will inject native code into the template
     *
     * @return bool
     */
    public function getAllowNativeCode(){
        return $this->allowNativeCode;
    }
    
    /**
     * Set the template file to parse
     *
     * @param string $templateFile
     *
     * @return void
     */
    public function setTemplate($templateFile){
        $this->template = $templateFile;
    }

    /**
     * Get the template file to parse
     *
     * @return bool
     */
    public function getTemplate(){
        return $this->template;
    }

    /**
     * Get the variables that are to be placed in the code
     * 
     * @param array $variables
     *
     * @return void
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;
    }

    /**
     * Get the variables that are to be/have been placed in the code
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Called everytime a part of code is generated.
     * 
     * @note May be an empty string when the parser is just collecting information and not outputting anything
     *
     * @param string $part The part of code that was generated
     * 
     * @return array
     */
    public function appendGeneratedCode($part)
    {
        $this->generatedCode .= $part;
        
        if(isset($this->appendGeneratedCodeCallback)){
            ($this->appendGeneratedCodeCallback)($part);
        }
    }

}
