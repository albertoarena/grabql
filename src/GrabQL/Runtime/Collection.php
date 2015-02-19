<?php
/**
 * This class defines a collection of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime;

use GrabQL\Runtime\Type\Base;

class Collection implements \IteratorAggregate
{
    /** @var string */
    protected $class;

    /** @var array */
    protected $list;

    /**
     * @param String $class
     * @throws \Exception
     */
    public function __construct($class)
    {
        // Try to instantiate a new $class
        try {
            if (!class_exists($class)) {
                throw new \Exception('');
            }
            $test = new $class(null, array());
            if (!$test instanceof Base) {
                throw new \Exception('[GrabQL\Runtime\Collection] Class ' . $class . ' not valid');
            }
            $this->class = $class;
        } catch (\Exception $e) {
            throw new \Exception('[GrabQL\Runtime\Collection] Class ' . $class . ' not available');
        }
        $this->list = array();
    }

    /**
     * Add a new element
     * @param Base $object
     * @return $this
     */
    public function add($object)
    {
        if ($object instanceof $this->class) {
            $this->list[$object->getId()] = $object;
        }
        return $this;
    }

    /**
     * Get an element in the collection by ID
     * @param string $id
     * @return null
     */
    public function get($id)
    {
        if (array_key_exists($id, $this->list)) {
            return $this->list[$id];
        }
        return null;
    }

    /**
     * Get the number of elements in the collection
     * @return int
     */
    public function count()
    {
        return count($this->list);
    }

    /**
     * Delete an element of the collection by ID
     * @param string $id
     * @return $this
     */
    public function delete($id)
    {
        if (array_key_exists($id, $this->list)) {
            unset($this->list[$id]);
        }
        return $this;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->list);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}