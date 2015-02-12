<?php
/**
 * This class defines the reference type of GrabQL Runtime.
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
     */
    public function setReference($obj)
    {
        if ($obj instanceof Base) {
            $this->reference = $obj;
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
     * @param Base $obj
     * @return mixed|void
     */
    protected function copyObject($obj)
    {
        $this->setReference($obj);
    }

}