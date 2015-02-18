<?php
/**
 * This class defines the reference type of GrabQL Runtime.
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

class Reference extends Base
{
    /** @var Base */
    protected $reference;

    /**
     * @param null $args
     * @return mixed|void
     */
    protected function init($args = null)
    {
        $this->setReference($args);
    }

    /**
     * Set a reference to another existing object
     * @param $obj
     * @throws \Exception
     */
    public function setReference($obj)
    {
        if ($obj instanceof Base) {
            $this->reference = $obj;
        } else if ($obj !== null) {
            throw new \Exception('Invalid reference');
        }
    }

    /**
     * Get the current referenced object
     * @return Base
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return mixed|null
     */
    protected function asFlat()
    {
        if ($this->reference !== null) {
            return $this->reference->asFlat();
        }
        return null;
    }

    /**
     * @return string
     */
    protected function asString()
    {
        if ($this->reference !== null) {
            return $this->reference->asString();
        }
        return '';
    }

    /**
     * Overwrite copy to allow copying a reference of the same type
     *
     * @param Base $obj
     * @throws \Exception
     */
    public function copy($obj)
    {
        if (is_null($this->reference) || get_class($this->reference) == get_class($obj)) {
            $this->copyObject($obj);
        }
        else {
            throw new \Exception('Unable to copy, the source object is not matching the destination object');
        }
    }

    /**
     * @param Base $obj
     * @return mixed|void
     */
    protected function copyObject($obj)
    {
        $this->setReference($obj);
    }

}