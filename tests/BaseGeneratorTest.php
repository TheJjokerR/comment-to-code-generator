<?php

use CommentToCode\Generators\FileGenerator;
use CommentToCode\Parsers\PHPParser;

/*
 * TODO: Write proper assertions
 */
class BaseGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testCompleteGenerator(){        
        $variables = [
            'normal_text' => 'some regular ol text',
            'long_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec malesuada, nisl posuere accumsan lobortis, tortor enim sagittis dolor, vitae porta massa enim ut dui. Curabitur felis dui, posuere ut sagittis et, maximus vel sapien. Quisque et ligula maximus, gravida libero sed, laoreet lectus. Nunc faucibus condimentum turpis. Morbi enim dui, laoreet vitae mi eu, sollicitudin molestie purus. Phasellus sem metus, cursus vel hendrerit vitae, ultricies at leo. Nulla cursus dui in vehicula viverra. Cras varius egestas libero, quis semper ligula facilisis sed.Nullam quis felis dui. Aenean lobortis mattis ipsum, quis molestie risus scelerisque eu. Suspendisse id sollicitudin felis. Vestibulum accumsan molestie diam quis consectetur. Nunc convallis libero quis commodo condimentum. Maecenas aliquet tempus nisl in auctor. Ut at nulla leo.Praesent scelerisque, massa vel volutpat venenatis, arcu dolor malesuada dui, quis euismod sapien ligula non odio. Nullam tincidunt dictum volutpat. Donec non massa semper, finibus purus a, tempus lorem. Nullam et pretium lacus, vel euismod neque. Nulla et purus ac nunc bibendum viverra. Morbi tellus est, laoreet eu congue eget, bibendum vitae lorem. Curabitur imperdiet sem ullamcorper diam condimentum elementum. Duis ornare condimentum quam, id pharetra dolor vulputate vel. Quisque condimentum libero id lacus ultrices convallis. Nam volutpat maximus semper. Nam bibendum neque eu eros semper condimentum.Morbi placerat laoreet molestie. Nullam vitae massa bibendum, faucibus nisl et, venenatis massa. Etiam consectetur ornare dui. Donec lacinia, magna non vestibulum finibus, nunc sem consectetur orci, non accumsan felis eros ut mi. Morbi dictum lacus sed tincidunt placerat. Quisque scelerisque interdum nunc, et posuere ex accumsan id. Praesent vehicula tincidunt velit id varius. Duis ante dui, ullamcorper vitae urna quis, placerat bibendum odio. Vestibulum ac orci lacus. Donec id arcu nec erat fringilla efficitur vitae quis massa. Curabitur in aliquam nulla.Duis eu diam tortor. Donec lorem augue, varius id dolor a, commodo accumsan libero. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed sed lectus diam. Maecenas tempus a quam volutpat cursus. Donec accumsan id libero sit amet semper. Fusce vitae ipsum quis erat molestie scelerisque in id massa.',
            'normal_class_name' => 'SomeClassName',
            'lower_class_name' => 'car information',
            'invalid_class_name' => '1InvalidClassName',
            'traits' => ['ExtraTrait', 'AnotherExtraTrait'],
            'php_code_which_should_be_escaped' => '<?php echo "Finally found my mistake\'s,sorry for getting so mad neighbours."',
            'runtime_trait' => 'RuntimeAddedTrait.php.template'
        ];

        $parser = new PHPParser();
        $parser->setTemplate(__DIR__ . '/templates/JustSomeClass.php.template');
        $parser->setVariables($variables);
        
        $generator = new FileGenerator($parser);
        $generator->generateToFile(__DIR__ . '/generated/CarInformation.php', true);
    }
}