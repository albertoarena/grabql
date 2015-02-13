<?php
namespace Command;

use GrabQL\Runtime\Common\Entity;

class EntityBox extends Entity
{
    protected $var;
    protected $language;

    public function __construct()
    {
        $this->var = 'dummy';
        $this->language = 'php';
    }
}

class EntityTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers GrabQL\Runtime\Common\Entity::set
     * @covers GrabQL\Runtime\Common\Entity::get
     */
    public function testSetGet()
    {
        $entity = new EntityBox();
        $this->assertEquals('dummy', $entity->get('var'));
        $this->assertEquals('php', $entity->getLanguage());
        $entity->set('var', 'hello');
        $this->assertEquals('hello', $entity->get('var'));
        $this->assertEquals('php', $entity->getLanguage());
    }

    /**
     * @covers GrabQL\Runtime\Common\Entity::__call
     * @covers GrabQL\Runtime\Common\Entity::checkArguments
     */
    public function testSetGetMagicCall()
    {
        $entity = new EntityBox();
        $this->assertEquals('dummy', $entity->getVar());
        $this->assertEquals('php', $entity->getLanguage());
        $entity->setVar('hello');
        $this->assertEquals('hello', $entity->getVar());
        $this->assertEquals('php', $entity->getLanguage());
    }

    /**
     * @covers GrabQL\Runtime\Common\Entity::set
     * @covers GrabQL\Runtime\Common\Entity::setProperties
     */
    public function testSetProperties()
    {
        $entity = new EntityBox();
        $this->assertEquals('dummy', $entity->getVar());
        $this->assertEquals('php', $entity->getLanguage());
        $entity->setProperties(array('var' => 'hello', 'language' => 'lisp'));
        $this->assertEquals('hello', $entity->getVar());
        $this->assertEquals('lisp', $entity->getLanguage());
    }

    /**
     * @covers GrabQL\Runtime\Common\Entity::__call
     * @covers GrabQL\Runtime\Common\Entity::checkArguments
     */
    public function testNotExistingProperty()
    {
        $entity = new EntityBox();
        $this->assertEquals('dummy', $entity->getVar());
        $this->assertEquals('php', $entity->getLanguage());
        $this->setExpectedException('\Exception', 'Property "invalid" not exists');
        $entity->setInvalid('hello');
    }

    /**
     * @covers GrabQL\Runtime\Common\Entity::__call
     * @covers GrabQL\Runtime\Common\Entity::checkArguments
     */
    public function testSkippingMethod()
    {
        $entity = new EntityBox();
        $entity->nopVar('hello');
    }

    /**
     * @covers GrabQL\Runtime\Common\Entity::__call
     * @covers GrabQL\Runtime\Common\Entity::checkArguments
     */
    public function testSetWrongArguments()
    {
        $entity = new EntityBox();
        $this->setExpectedException('\Exception', 'Method setVar needs minimally 1 and maximally 1 arguments. 0 arguments given.');
        $entity->setVar();
    }

    /**
     * @covers GrabQL\Runtime\Common\Entity::__call
     * @covers GrabQL\Runtime\Common\Entity::checkArguments
     */
    public function testGetWrongArguments()
    {
        $entity = new EntityBox();
        $this->setExpectedException('\Exception', 'Method getVar needs minimally 0 and maximally 0 arguments. 1 arguments given.');
        $entity->getVar('123');
    }

    /**
     * @covers GrabQL\Runtime\Common\Entity::__call
     * @covers GrabQL\Runtime\Common\Entity::checkArguments
     */
    public function testNotExistingMagicCallMethod()
    {
        $entity = new EntityBox();
        $entity->invalid('hello');
    }

} 