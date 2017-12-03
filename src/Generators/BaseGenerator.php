<?php

namespace CommentToCode\Generators;

use CommentToCode\Parsers\BaseParser;

/**
 * This is the base generator class.
 *
 * It provide the functions required by all derivatives needed to generate code. 
 */
class BaseGenerator
{
    /**
     * The parser that will go through different kinds of code.
     *
     * @var array
     */
    protected $parser;
    
    /**
     * Create a new generator instance.
     *
     * @param BaseParser $parser
     *
     * @return void
     */
    public function __construct(BaseParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return BaseParser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param BaseParser $parser
     * 
     * @return void
     */
    public function setParser(BaseParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Generate the code
     *
     * @return string
     * 
     * @throws
     */
    public function generate()
    {
        return $this->parser->getCode();
    }
    
}
