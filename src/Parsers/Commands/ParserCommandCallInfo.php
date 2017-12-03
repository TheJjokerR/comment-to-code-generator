<?php


namespace CommentToCode\Parsers\Commands;


class ParserCommandCallInfo
{
    /**
     * The name of the command called
     *
     * @var string
     */
    protected $name;

    /**
     * The arguments supplied to the command
     *
     * @var string
     */
    protected $arguments;

    /**
     * The file the command was called from
     *
     * @var string
     */
    protected $file;

    /**
     * The character number of the annotation that called the command 
     *
     * @var int
     */
    protected $characterNum;

    /**
     * Creates a new instance of this class
     *
     * @param string $name
     * @param string $arguments
     * @param string $file
     * @param int $characterNum
     */
    public function __construct($name, $arguments, $file, $characterNum)
    {
        $this->name = $name;
        $this->file = $file;
        $this->arguments = $arguments;
        $this->characterNum = $characterNum;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     * 
     * @return void
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return int
     */
    public function getCharacterNum()
    {
        return $this->characterNum;
    }

    /**
     * @param int $characterNum
     *
     * @return void
     */
    public function setCharacterNum($characterNum)
    {
        $this->characterNum = $characterNum;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $arguments
     *
     * @return void
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    
}