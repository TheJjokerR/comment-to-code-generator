# C2C: Comment to Code Generator

Generate code based of templates with comments in them that describe variables (or class properties) and other templates

## Usage

This project is mostly designed for development use. Using it in production is not recommended (due to unoptimized code and the use of `eval` for the exec command).

### The code

#### Most important classes and methods for using this

|Type   | Name  | Example | Description
|------ | ------| --------------| -----------
| class  | PHPParser | `$parser = new PHPParser()` | Will parse a when it's parse method is called (usually by a BaseGenerator)
| method | setTemplate | `$parser->setTemplate(__DIR__ . '/templates/JustSomeClass.php.template')` | Give this a filepath to use as a template
| method | setVariables | `$parser->setVariables(['shared_variable' => 'Just a string'])` | The variables that will be made available to the scope of the template
| class | BaseGenerator | `$generator = new BaseGenerator()` | Will call to the parser to generate code to a string. There is also FileGenerator which does the same, except also writes it to a file while the parser is running.
| method | generate | `$generatedCode = $generator->generate()` | Returns a string with all the variables made available by `PHPParser->setVariables` put in the placeholders annotations

#### Making your own parser for another language

The BaseParser is designed in such a way that it should work for most languages, but if you really want to extend it's functionality you can:
1. Create a class which inherits an existing parser (PHPParser or BaseParser)
2. Change the following properties to what you need (in the constructor for example):
    ```php
        $this->annotationStart = '/*/>'; // What the placeholder annotation starts with
        $this->annotationDelimiter = ':'; // What seperates the command with it's arguments
        $this->annotationEnd = '/*/'; // What ends the annotation
    ```
3. Add some commands by:
    1. creating instances of a class derived from ParserCommand (giving the command used in templates to the constructor):
        ```php
        $this->addCommands(new PHPTestCommand('test'));
        ```
    2. creating it with a callback function:
        ```php 
        $this->addCommands(new ParserCommand('test', function(ParserCommandCallInfo $commandCallInfo, $includedVariables = []){
            return 'add this text to the generated code when the test command is encountered';
        }));
        ```
4. (Optionally) If you're not going to use very many commands in the templates it doesn't really make sense to have to type the command names all the time. You can just use the start and end annotations with arguments in between if you set this:
    ```php    
    $this->defaultCommand = 'test';
    ```

### The Templates

Templates have comment annotations in them that describe what they must be replaced with. The annotations usually set a command name to be used. Commands can be attached to a parser using the `BaseParser->addCommands(ParserCommand $command)` method.

Depending on what parser class you are using annotations may start, end and be delimited differntly. By default annotations look like this:
```php 
/*/>commandName: arguments go here/*/
```

Currently you can find some examples in the tests folder.
