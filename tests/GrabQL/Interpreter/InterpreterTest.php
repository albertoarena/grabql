<?php
use \GrabQL\Interpreter\Interpreter;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;

class InterpreterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    protected $root;

    /**
     * @var vfsStreamFile
     */
    protected $file;

    /**
     * @var vfsStreamFile
     */
    protected $fileWithBOM;

    protected function setUp()
    {
        vfsStreamWrapper::register();
        $this->root = vfsStream::newDirectory('home');
        vfsStreamWrapper::setRoot($this->root);

        $this->file = vfsStream::newFile('test.gql');
        $this->file->at($this->root);
        $this->file->withContent('echo 123');

        $this->fileWithBOM = vfsStream::newFile('testBOM.gql');
        $this->fileWithBOM->at($this->root);
        $this->fileWithBOM->withContent("\xEF\xBB\xBFecho 123");
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::__construct
     * @covers \GrabQL\Interpreter\Interpreter::runtime
     */
    public function testRuntime()
    {
        $interpreter = new Interpreter;
        $this->assertInstanceOf('\\GrabQL\\Runtime\\Runtime', $interpreter->runtime());
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::run
     * @covers \GrabQL\Interpreter\Interpreter::loadSource
     * @covers \GrabQL\Interpreter\Interpreter::processOption
     * @covers \GrabQL\Interpreter\Interpreter::normalise
     */
    public function testLoadSourceRun()
    {
        $args = array(
            '-s',
            $this->file->url()
        );

        $interpreter = new Interpreter;
        $this->expectOutputString('123' . "\n");
        $interpreter->run($args);
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::run
     * @covers \GrabQL\Interpreter\Interpreter::loadSource
     * @covers \GrabQL\Interpreter\Interpreter::processOption
     */
    public function testLoadSourceFileNotFoundRun()
    {
        $args = array(
            '-s',
            '\invalid\dummy\path\notfound.gql'
        );

        $interpreter = new Interpreter;
        $this->setExpectedException('\Exception', 'File not found: \invalid\dummy\path\notfound.gql');
        $interpreter->run($args);
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::run
     * @covers \GrabQL\Interpreter\Interpreter::loadSource
     * @covers \GrabQL\Interpreter\Interpreter::processOption
     * @covers \GrabQL\Interpreter\Interpreter::normalise
     */
    public function testLoadSourceWithBOMRun()
    {
        $args = array(
            '-s',
            $this->fileWithBOM->url()
        );

        $interpreter = new Interpreter;
        $this->expectOutputString('123' . "\n");
        $interpreter->run($args);
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::run
     * @covers \GrabQL\Interpreter\Interpreter::processOption
     * @covers \GrabQL\Interpreter\Interpreter::help
     */
    public function testHelp()
    {
        $args = array(
            '-s',
            '-h'
        );
        $interpreter = new Interpreter;
        $this->expectOutputRegex('/Usage: gql\.php/');
        $interpreter->run($args);
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::run
     * @covers \GrabQL\Interpreter\Interpreter::processOption
     */
    public function testUnknownOption()
    {
        $args = array(
            '-s',
            '-Z'
        );
        $interpreter = new Interpreter;
        $this->expectOutputString('[' . Interpreter::ME . '] Unknown option -Z' . "\n");
        $interpreter->run($args);
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::run
     * @covers \GrabQL\Interpreter\Interpreter::processOption
     * @covers \GrabQL\Interpreter\Interpreter::execute
     * @covers \GrabQL\Interpreter\Interpreter::interpret
     */
    public function testSilentRun()
    {
        $args = array(
            '-s',
            '-c',
            'echo 1'
        );
        $interpreter = new Interpreter;
        $this->expectOutputString('1' . "\n");
        $interpreter->run($args);
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::run
     * @covers \GrabQL\Interpreter\Interpreter::processOption
     * @covers \GrabQL\Interpreter\Interpreter::execute
     * @covers \GrabQL\Interpreter\Interpreter::interpret
     */
    public function testNotSilentRun()
    {
        $args = array(
            '-c',
            'echo 1'
        );
        $interpreter = new Interpreter;
        $this->expectOutputString(Interpreter::CLI_DEFAULT . "\n" . '1' . "\n");
        $interpreter->run($args);
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::run
     * @covers \GrabQL\Interpreter\Interpreter::processOption
     * @covers \GrabQL\Interpreter\Interpreter::execute
     * @covers \GrabQL\Interpreter\Interpreter::interpret
     */
    public function testCommentRun()
    {
        $args = array(
            '-s',
            '-c',
            '# this is a comment'
        );
        $interpreter = new Interpreter;
        $this->expectOutputString('');
        $interpreter->run($args);
    }

    /**
     * @covers \GrabQL\Interpreter\Interpreter::run
     * @covers \GrabQL\Interpreter\Interpreter::processOption
     * @covers \GrabQL\Interpreter\Interpreter::execute
     * @covers \GrabQL\Interpreter\Interpreter::interpret
     */
    public function testInvalidDefinitionRun()
    {
        // @todo Invalid definition triggers several errors
        /*
        $args = array(
            '-s',
            '-c',
            '@ invalid'
        );
        $interpreter = new Interpreter;
        $this->setExpectedException('\Exception', 'Invalid definition');
        $interpreter->run($args);
        */
    }
} 