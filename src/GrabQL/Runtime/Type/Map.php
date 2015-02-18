<?php
/**
 * This class defines the map type of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime\Type;

use GrabQL\Runtime\TypeFactory;

class Map extends BaseIterator
{
    /**
     * @var boolean
     * @todo Currently not used
     */
    protected $ordered;

    /**
     * @param array|mixed|null $args
     * @throws \Exception
     * @return mixed|void
     */
    protected function init($args = null)
    {
        parent::init($args);

        $this->setValues(array());
        $this->setOrdered(false);

        if (is_null($args)) {
            // NOP
        } elseif ($args instanceof Map) {
            $this->copyObject($args);
        } elseif (is_array($args)) {
            $this->initFromArray($args);
        } elseif (is_object($args)) {
            $this->initFromObject($args);
        } else {
            throw new \Exception('Invalid value for a map (only Map, array or object accepted)');
        }
    }

    /**
     * @param array $obj
     */
    protected function initFromArray($obj)
    {
        $this->clear();
        if (is_array($obj)) {
            foreach ($obj as $idx => $value) {
                if (!$value instanceof Base) {
                    $value = TypeFactory::createInstance(null, $value);
                }
                $this->atImplicit($idx, $value);
            }
        }
    }

    /**
     * @param object $obj
     */
    protected function initFromObject($obj)
    {
        $this->clear();
        if (is_object($obj)) {
            $class = new \ReflectionClass(get_class($obj));
            $properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
            $methods = $class->getMethods(\ReflectionProperty::IS_PUBLIC);

            // Add properties
            foreach ($properties as $property) {
                $propertyName = $property->getName();
                $this->atImplicit($propertyName, TypeFactory::createInstance(null, $obj->$propertyName));
            }
            // Add methods
            foreach ($methods as $method) {
                $methodName = $method->getName();
                if ($methodName == '__construct') {
                    continue;
                }
                $procedure = new Procedure($methodName, array('code' => function ($params = null) use ($obj, $methodName) {
                        return $obj->$methodName($params);
                    }));
                $this->atImplicit($methodName, $procedure);
            }
        }
    }

    /**
     * Push an element onto the end of the map
     * @param Base|mixed $obj
     * @return $this
     */
    public function push($obj)
    {
        if ($obj instanceof Base) {
            array_push($this->values, $obj);
        } else {
            array_push($this->values, TypeFactory::createInstance(null, $obj));
        }
        return $this;
    }

    /**
     * Pop the element off the end of the map
     * @return Base
     */
    public function pop()
    {
        return array_pop($this->values);
    }

    /**
     * Prepend an element to the beginning of the map
     * @param Base|mixed $obj
     * @return $this
     */
    public function unshift($obj)
    {
        if ($obj instanceof Base) {
            array_unshift($this->values, $obj);
        } else {
            array_unshift($this->values, TypeFactory::createInstance(null, $obj));
        }
        return $this;
    }

    /**
     * Shift an element off the beginning of the map
     * @return Base
     */
    public function shift()
    {
        return array_shift($this->values);
    }

    /**
     * Counts of elements of the map
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }

    /**
     * Return the element at the index, or null; equivalent of brackets []
     * @param int|string $index
     * @return Base|null
     */
    public function at($index)
    {
        return $this->offsetGet($index);
    }

    /**
     * Return the element at the index, creating it if it doesn't exist and assigning a value
     * @param int|string $index
     * @param mixed|null $value
     * @return mixed
     */
    public function atImplicit($index, $value = null)
    {
        if (!array_key_exists($index, $this->values)) {
            $this->offsetSet($index, $value);
        }
        return $this->offsetGet($index);
    }

    /**
     * @param int|string $index
     */
    public function delete($index)
    {
        return $this->offsetUnset($index);
    }

    /**
     * @param int|string $index
     * @return bool
     */
    public function has($index)
    {
        return $this->offsetExists($index);
    }

    /**
     * Return the element content in flat format
     * @return array|mixed
     */
    protected function asFlat()
    {
        $ret = array();
        foreach ($this->values as $index => $value) {
            if ($value instanceof Base) {
                $ret[$index] = $value->asFlat();
            }
        }
        return $ret;
    }

    /**
     * Return the element content as a string
     * @return string
     */
    protected function asString()
    {
        $ret = array();
        foreach ($this->values as $index => $value) {
            if ($value instanceof Base) {
                $ret[$index] = $value->toString();
            }

            if ($ret[$index] == '') {
                $ret[$index] = '""';
            }
        }
        return implode(', ', $ret);
    }

    /**
     * Copy from another object
     * @param Base $obj
     * @return mixed|void
     */
    protected function copyObject($obj)
    {
        $this->clear();
        foreach ($obj as $index => $value) {
            $this->offsetSet($index, $value->asFlat());
        }
    }

    /**
     * Force conversion to Base object of a value set
     *
     * @param mixed $offset
     * @param mixed $value
     * @return mixed|void
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Base) {
            $value = TypeFactory::createInstance(null, $value);
        }
        return parent::offsetSet($offset, $value);
    }

}