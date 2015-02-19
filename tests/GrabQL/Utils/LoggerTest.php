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
        $this->expectOutputString('[prefix] 123' . Logger::CR);
        Logger::writePrefix('prefix', '123');
    }

    /**
     * @covers \GrabQL\Utils\Logger::setActive
     * @covers \GrabQL\Utils\Logger::write
     */
    public function testWriteSetActive()
    {
        $this->expectOutputString('123' . Logger::CR . '789' . Logger::CR);
        Logger::write('123');
        Logger::setActive(false);
        Logger::write('456');
        Logger::setActive(true);
        Logger::write('789');
    }

    /**
     * @covers \GrabQL\Utils\Logger::setActive
     * @covers \GrabQL\Utils\Logger::writePrefix
     */
    public function testWritePrefixSetActive()
    {
        $this->expectOutputString('[prefix] 123' . Logger::CR . '[prefix] 789' . Logger::CR);
        Logger::writePrefix('prefix', '123');
        Logger::setActive(false);
        Logger::writePrefix('prefix', '456');
        Logger::setActive(true);
        Logger::writePrefix('prefix', '789');
    }

} 