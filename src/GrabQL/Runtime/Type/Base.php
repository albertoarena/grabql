<?php
/**
 * This class defines the base abstract type of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime\Type;

use GrabQL\Runtime\Common\Entity;
use GrabQL\Runtime\Filter\Filter;

abstract class Base extends Entity
{
    /** @var string */
    protected $id;

    /** @var Filter */
    protected $filter;

    /**
     * @param string|null $id
     * @param mixed|null $args
     */
    public function __construct($id = null, $args = null)
    {
        if ($id === null || $id == '') {
            $id = chr(rand(ord('a'), ord('z'))) . uniqid(rand(0, 7159541386));
        }
        $this->id = $id;
        $this->filter = null;
        $this->init($args);
    }

    /**
     * @param array $args
     * @return mixed
     */
    abstract protected function init($args = null);

    /**
     * @return mixed
     */
    abstract protected function asFlat();

    /**
     * @return string
     */
    public function asJSON()
    {
        return json_encode($this->asFlat());
    }

    /**
     * @return string
     */
    abstract protected function asString();

    /**
     * @return mixed
     */
    public function toFlat()
    {
        if (!is_null($this->filter)) {
            return $this->filter->apply($this->asFlat());
        }
        return $this->asFlat();
    }

    /**
     * @return mixed
     */
    public function toJSON()
    {
        if (!is_null($this->filter)) {
            return $this->filter->apply($this->asJSON());
        }
        return $this->asJSON();
    }

    /**
     * @return string
     */
    public function toString()
    {
        if (!is_null($this->filter)) {
            return $this->filter->apply($this->asFlat());
        }
        return $this->asString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param Base $obj
     * @return mixed
     */
    abstract protected function copyObject($obj);

    /**
     * @param Base $obj
     * @throws \Exception
     */
    public function copy($obj)
    {
        if (get_class($this) == get_class($obj)) {
            $this->copyObject($obj);
        }
        else {
            throw new \Exception('Unable to copy, the source object is not matching the destination object');
        }
    }

}