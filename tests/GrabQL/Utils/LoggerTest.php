<?php
use \GrabQL\Utils\Logger;

class LoggerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \GrabQL\Utils\Logger::write
     */
    public function testWrite()
    {
        $this->expectOutputString('123' . Logger::CR .
            '345' . Logger::CR .
            'Array' . Logger::CR . '(' . Logger::CR . ')' . Logger::CR . Logger::CR
        );
        Logger::write('123');
        Logger::write(345);
        Logger::write(array());
    }

    /**
     * @covers \GrabQL\Utils\Logger::writePrefix
     */
    public function testWritePrefix()
    {
        $this->expectOutputString('prefix' . Logger::CR . '123' . Logger::CR);
        Logger::write('prefix', '123');
    }

    /**
     * @covers \GrabQL\Utils\Logger::setActive
     */
    public function testSetActive()
    {
        $this->expectOutputString('123' . Logger::CR . '789' . Logger::CR);
        Logger::write('123');
        Logger::setActive(false);
        Logger::write('456');
        Logger::setActive(true);
        Logger::write('789');
    }

} 