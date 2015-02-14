<?php
namespace Command;

use GrabQL\Runtime\Command\Command;

class CommandBox extends Command
{
    protected $first, $second;

    public function execute($args = null)
    {
    }
}

class CommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Command\Command::setOptions
     */
    public function testExecute()
    {
        $command = new CommandBox();
        $command->setOptions(array('first' => '1', 'second' => '2'));
        $this->assertEquals('1', $command->getFirst());
        $this->assertEquals('2', $command->getSecond());
        $this->setExpectedException('\Exception', 'Property "third" not exists');
        $this->assertNull($command->getThird());
    }

} 