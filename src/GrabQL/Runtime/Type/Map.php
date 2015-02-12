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
     * @param array|null $args
     * @return mixed|void
     */
    protected function init($args = null)
    {
        $this->setValues(array());
        $this->setOrdered(false);
        if ($args instanceof Map) {
            $this->copyObject($args);
        } else if (is_array($args)) {
            foreach ($args as $idx => $arg) {
                if (!$arg instanceof Base) {
                    $arg = TypeFactory::createInstance(null, $arg);
                }
                $this->atImplicit($idx, $arg);
            }
        } else if (is_object($args)) {

            $class = new \ReflectionClass(get_class($args));
            $properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
            $methods = $class->getMethods(\ReflectionProperty::IS_PUBLIC);

            //var_dump($methods); exit;

            // Add properties
            foreach ($properties as $property) {
                $this->atImplicit($property, $args->{$property});
            }
            // Add methods
            foreach ($methods as $method) {
                if ($method == '__construct') {
                    continue;
                }
                $proc = new Procedure($method->name, array('code' => function ($params) use ($args, $method) {
                        return $args->{$method->name}($params);
                    }));
                $this->offsetSet($method->name, $proc);
            }
        }
    }

    /**
     * Push an element onto the end of the map
     * @param Base $obj
     * @return $this
     */
    public function push($obj)
    {
        if ($obj instanceof Base) {
            array_push($this->values, $obj);
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
     * @param Base $obj
     * @return $this
     */
    public function unshift($obj)
    {
        if ($obj instanceof Base) {
            array_unshift($this->values, $obj);
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
     * @param string $index
     * @return Base|null
     */
    public function at($index)
    {
        return $this->offsetGet($index);
    }

    /**
     * Return the element at the index, creating it if it doesn't exist and assigning a value
     * @param $index
     * @param mixed|null $value
     * @return mixed
     */
    public function atImplicit($index, $value = null)
    {
        if (!array_key_exists($index, $this->values)) {
            $this->values[$index] = $value;
        }
        return $this->values[$index];
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
            else {
                $ret[$index] = $value;
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
            else {
                $ret[$index] = $value;
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
        foreach ($obj as $index => $value) {
            $this->offsetSet($index, $value->asFlat());
        }
    }

}