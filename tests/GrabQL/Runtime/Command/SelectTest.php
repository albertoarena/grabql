<?php
namespace Command;

use GrabQL\Runtime\Command\Select;

class SelectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    protected function getOptions() {
        return array(
            '//h1',
            'from', 'www.google.com',
            'to', 'variable',
            'where', 'true',
            'limit', 'x'
        );
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::setOptions
     */
    public function testSetOptions()
    {
        $options = $this->getOptions();
        $select = new Select();
        $select->setOptions($options);

        $this->assertEquals($options[0], $select->getWhat());
        $this->assertEquals($options[2], $select->getFrom());
        $this->assertEquals($options[4], $select->getTo());
        $this->assertEquals($options[6], $select->getWhere());
        $this->assertEquals($options[8], $select->getLimit());
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::execute
     */
    public function testExecute()
    {
        $options = $this->getOptions();
        $select = new Select();
        $select->setOptions($options);

        $select->execute(null);
    }

} 