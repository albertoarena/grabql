<?php
/**
 * This class defines the scalar type of GrabQL Runtime.
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

class Scalar extends Base
{
    /** @var mixed */
    protected $value;

    /**
     * @param mixed|null $args
     * @return mixed|void
     */
    protected function init($args = null)
    {
        $this->setValue($args);
    }

    /**
     * @return mixed
     */
    protected function asFlat()
    {
        if ($this->value instanceof Base) {
            return $this->value->asFlat();
        }
        else {
            return $this->value;
        }
    }

    /**
     * @return string
     */
    protected function asString()
    {
        if ($this->value instanceof Base) {
            return $this->value->toString();
        }
        else {
            return (string) $this->value;
        }
    }

    /**
     * @param Base $obj
     * @return mixed|void
     */
    protected function copyObject($obj)
    {
        $this->setValue($obj->getValue());
    }

}