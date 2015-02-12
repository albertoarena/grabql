<?php
/**
 * This class defines a factory for types of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime;

class TypeFactory
{

    /**
     * Factory that creates a type based on a variable
     * @param string|null $id
     * @param mixed|null $value
     * @return Type\Base
     */
    public static function createInstance($id, $value)
    {
        if ($value instanceof Type\Base) {
            return new Type\Reference($id, $value);
        } elseif (is_array($value) || is_object($value)) {
            return new Type\Map($id, $value);
        } elseif (is_resource($value)) {
            return new Type\Resource($id, $value);
        } elseif (is_scalar($value)) {
            return new Type\Scalar($id, $value);
        } elseif (is_callable($value)) {
            return new Type\Procedure($id, $value);
        } else {
            return new Type\Nil($id, $value);
        }
    }

} 