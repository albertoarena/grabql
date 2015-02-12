<?php
/**
 * This class defines the base type iterator of GrabQL Runtime.
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

abstract class BaseIterator extends Base implements \Iterator, \ArrayAccess
{
    /** @var array */
    protected $values;

    /**
     * @param array|null $args
     * @return mixed|void
     */
    protected function init($args = null)
    {
        $this->values = array();
        if (is_array($args)) {
            $this->setValues($args);
        }
    }

    /**
     * @return mixed|void
     */
    public function rewind()
    {
        return reset($this->values);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->values);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->values);
    }

    /**
     * @return mixed|void
     */
    public function next()
    {
        return next($this->values);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return key($this->values) !== null;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->values[$offset]) ? $this->values[$offset] : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return mixed|void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->values[] = $value;
        } else {
            $this->values[$offset] = $value;
        }
        return $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }
} 