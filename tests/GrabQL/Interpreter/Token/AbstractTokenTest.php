<?php
namespace Token;

use GrabQL\Interpreter\Token\AbstractToken;
use GrabQL\Runtime\Runtime;

class TestToken extends AbstractToken
{
    public function internalProcess()
    {
        return null;
    }

    public function getRuntime()
    {
        return $this->runtime;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getData()
    {
        return $this->data;
    }
}

class AbstractTokenTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Interpreter\Token\AbstractToken::process
     */
    public function testProcess()
    {
        $tokenObj = new TestToken();
        $runtime = new Runtime();
        $token = 'token';
        $data = 'data';

        $result = $tokenObj->process($runtime, $token, $data);
        $this->assertEquals(null, $result);

        $this->assertEquals($runtime, $tokenObj->getRuntime());
        $this->assertEquals($token, $tokenObj->getToken());
        $this->assertEquals($data, $tokenObj->getData());
    }

} 