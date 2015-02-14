<?php
namespace Token;

use GrabQL\Interpreter\Token\AbstractToken;
use GrabQL\Runtime\Runtime;

class AbstractTokenTest extends \PHPUnit_Framework_TestCase
{
    /** @var Runtime */
    protected $runtime;

    protected $token;

    /** @var array */
    protected $reflection;

    protected function assertPreConditions()
    {
        $this->runtime = new Runtime();

        // Make the properties runtime, token and data accessible
        $class = new \ReflectionClass('GrabQL\Interpreter\Token\AbstractToken');
        $this->reflection = array();
        $this->reflection['runtime'] = $class->getProperty('runtime');
        $this->reflection['runtime']->setAccessible(true);
        $this->reflection['token'] = $class->getProperty('token');
        $this->reflection['token']->setAccessible(true);
        $this->reflection['data'] = $class->getProperty('data');
        $this->reflection['data']->setAccessible(true);

        $this->token = $this->getMockBuilder('GrabQL\Interpreter\Token\AbstractToken')
            ->setMethods(array('internalProcess'))
            ->getMock();

        // Implement abstract method internalProcess
        $this->token->expects($this->any())
            ->method('internalProcess')
            ->will($this->returnValue(null));
    }

    /**
     * @covers GrabQL\Interpreter\Token\AbstractToken::process
     */
    public function testProcess()
    {
        $token = 'token';
        $data = 'data';
        $result = $this->token->process($this->runtime, $token, $data);
        $this->assertNull($result);

        $this->assertEquals($this->runtime, $this->reflection['runtime']->getValue($this->token));
        $this->assertEquals($token, $this->reflection['token']->getValue($this->token));
        $this->assertEquals($data, $this->reflection['data']->getValue($this->token));
    }

} 