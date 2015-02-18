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
     * @param mixed $value
     * @throws \Exception
     */
    protected function setValue($value)
    {
        if (is_scalar($value)) {
            if (is_bool($value)) {
                $value = intval($value);
            }
            $this->value = $value;
        } else if (is_null($value)) {
            //
        } else if (is_array($value) && count($value) == 0) {
            //
        } else {
            throw new \Exception('Invalid value for scalar');
        }
    }

    /**
     * @return mixed
     */
    protected function asFlat()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    protected function asString()
    {
        return (string)$this->value;
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