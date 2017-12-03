<?php

namespace CommentToCode\Parsers\Commands;

use CommentToCode\Parsers\Exceptions\ParserCommandException;

/**
 * This is the base parser command class.
 *
 * It specifies how certain commands are interpreted 
 */
class ParserCommand
{

    /**
     * The callback for the command
     *
     * @var callable
     */
    protected $callback;
    
    /**
     * The name of the command, deciding how it will be called from a template
     *
     * @var string
     */
    protected $name;

    /**
     * Create a new parser command instance.
     *
     * @return void
     */
    public function __construct($name, $callback = null)
    {
        $this->name = $name;
        $this->callback = $callback;
    }

    /**
     * Calls the command and does something
     *
     * @note You may override this function to implement callback actions in a class rather than through a callback
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
        if(isset($this->callback)) {
            return ($this->callback)($this->name, $arguments, $includedVariables);
        }

        throw new ParserCommandException("{$this->name} callback could not be called as it did not exist and the call method was not properly overriden!");
    }

    /**
     * @param callable $callback
     *
     * @return void
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param string $name
     * 
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}
