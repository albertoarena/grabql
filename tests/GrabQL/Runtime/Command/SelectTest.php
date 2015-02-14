<?php
namespace Command;

use Curl\Curl;
use GrabQL\Runtime\Command\Select;
use GrabQL\Runtime\Type\Map;
use GrabQL\Runtime\Type\Procedure;
use GrabQL\Runtime\Type\Reference;
use GrabQL\Runtime\Type\Resource;
use GrabQL\Runtime\Type\Scalar;

class CurlMock extends Curl
{
    protected $mockResponse;

    public function __construct()
    {
        $this->mockResponse = '<html><head></head><body><h1 rel="something">title</h1><p>content</p></body></html>';
    }

    public function get($url, $data = array())
    {
        $this->response = $this->mockResponse;
    }
}

class SelectTest extends \PHPUnit_Framework_TestCase
{
    protected $options;

    protected $curl;

    protected function setUp()
    {
        $this->options = array(
            '@value',
            'from', 'albertoarena.co.uk',
            'to', null,
            'where', '//h1',
            'limit', null
        );

        $this->curl = '';
    }

    /**
     * @return Select
     */
    protected function createSelect()
    {
        $select = new Select();
        $select->setCurlClass('Command\\CurlMock');
        $select->setOptions($this->options);
        return $select;
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     */
    public function testSetOptions()
    {
        $select = $this->createSelect();
        $this->assertEquals($this->options[0], $select->getWhat());
        $this->assertEquals($this->options[2], $select->getFrom());
        $this->assertEquals($this->options[4], $select->getTo());
        $this->assertEquals($this->options[6], $select->getWhere());
        $this->assertEquals($this->options[8], $select->getLimit());
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testExecute()
    {
        $select = $this->createSelect();
        $select->execute(null);

        // @todo asserts
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testFromIsBase()
    {
        $from = new Scalar('from', 'albertoarena.co.uk');

        $select = $this->createSelect();
        $select->setFrom($from);
        $select->execute();
        $results = $select->getResults();
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Map', $results);
        $this->assertEquals('title', $results->at(0)->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testFromIsArray()
    {
        $from = array('albertoarena.co.uk', array());

        $select = $this->createSelect();
        $select->setFrom($from);
        $select->execute();
        $results = $select->getResults();
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Map', $results);
        $this->assertEquals('title', $results->at(0)->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testWhatIsBase()
    {
        $what = new Scalar('what', '@value');

        $select = $this->createSelect();
        $select->setWhat($what);
        $select->execute();
        $results = $select->getResults();
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Map', $results);
        $this->assertEquals('title', $results->at(0)->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testWhatIsArray()
    {
        $what = array('rel', '@value', '*');

        $select = $this->createSelect();
        $select->setWhat($what);
        $select->execute();
        $results = $select->getResults();
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Map', $results);
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Map', $results->at(0));
        $this->assertEquals('title', $results->at(0)->at('@value')->getValue());
        $this->assertEquals('something', $results->at(0)->at('rel')->getValue());
        $this->assertEquals('<h1 rel="something">title</h1>' . "\n", $results->at(0)->at('*')->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testToIsCallableExecute()
    {
        $var = 1;
        $callback = function () use (&$var) {
            $var = 2;
        };

        $select = $this->createSelect();
        $select->setTo($callback);
        $select->execute(null);
        $this->assertEquals(2, $var);
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testToIsProcedureExecute()
    {
        $var = 1;
        $callback = new Procedure('callback',
            array('code' => function () use (&$var) {
                    $var = 2;
                }
            )
        );

        $select = $this->createSelect();
        $select->setTo($callback);
        $select->execute(null);
        $this->assertEquals(2, $var);
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testToIsScalarExecute()
    {
        $scalar = new Scalar('scalar');
        $scalar->setValue('dummy');
        $this->assertEquals('dummy', $scalar->getValue());

        $select = $this->createSelect();
        $select->setTo($scalar);
        $select->execute(null);
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Map', $scalar->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $scalar->getValue()->at(0));
        $this->assertEquals('title', $scalar->getValue()->at(0)->getValue());
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     * @covers GrabQL\Runtime\Type\Map::init
     * @covers GrabQL\Runtime\Type\Map::initFromArray
     * @covers GrabQL\Runtime\Type\Map::at
     */
    public function testToIsMapExecute()
    {
        $map = new Map('map', array('dummy' => '123'));
        $this->assertEquals('123', $map->at('dummy'));

        $select = $this->createSelect();
        $select->setTo($map);
        $select->execute(null);
        $this->assertEquals('title', $map->at('0'));
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testToIsResourceExecute()
    {
        $resource = new Resource('resource');

        $select = $this->createSelect();
        $select->setTo($resource);
        $select->execute(null);
        $this->assertEquals('title', $resource->getStream());
    }

    /**
     * @covers GrabQL\Runtime\Command\Select::__construct
     * @covers GrabQL\Runtime\Command\Select::setOptions
     * @covers GrabQL\Runtime\Command\Select::execute
     * @covers GrabQL\Runtime\Command\Select::findDestination
     * @covers GrabQL\Runtime\Command\Select::getResults
     * @covers GrabQL\Runtime\Command\Select::getCurlParams
     * @covers GrabQL\Runtime\Command\Select::processNode
     * @covers GrabQL\Runtime\Command\Select::applyXPath
     * @covers GrabQL\Runtime\Command\Select::setCurlClass
     */
    public function testToIsReferenceExecute()
    {
        $scalar = new Scalar('scalar');
        $scalar->setValue('dummy');
        $this->assertEquals('dummy', $scalar->getValue());

        $reference = new Reference('reference');
        $reference->setReference($scalar);

        $select = $this->createSelect();
        $select->setTo($reference);
        $select->execute(null);
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Map', $scalar->getValue());
        $this->assertInstanceOf('GrabQL\\Runtime\\Type\\Scalar', $scalar->getValue()->at(0));
        $this->assertEquals('title', $scalar->getValue()->at(0)->getValue());
    }
} 