<?php
/**
 * This class defines the procedure type of GrabQL Runtime.
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

use GrabQL\Runtime\Common\Executable;

class Procedure extends Base implements Executable
{
    /** @var array */
    protected $params;

    /** @var array|null */
    protected $code;

    /**
     * @param null $args
     * @return mixed|void
     */
    protected function init($args = null)
    {
        $this->setParams(array());
        $this->setCode(null);
        $this->setProperties($args);
    }

    /**
     * @param array $a
     * @param array $b
     * @return array
     */
    protected function arrayCombine($a, $b)
    {
        $size = count($a);
        $a = array_slice($a, 0, $size);
        $b = array_slice($b, 0, $size);
        return array_combine($a, $b);
    }

    /**
     * @param null $args
     * @param null $context
     * @return mixed|null
     */
    public function execute($args = null, $context = null)
    {
        if (($code = $this->getCode()) !== null) {
            if (!is_array($args)) {
                $args = array($args);
            }
            $callArgs = $this->arrayCombine($this->getParams(), $args);
            return call_user_func($code, $callArgs);
        }
        return null;
    }

    /**
     * @return mixed|string
     */
    protected function asFlat()
    {
        return '[procedure]';
    }

    /**
     * @return string
     */
    protected function asString()
    {
        return '[procedure]';
    }

    /**
     * @param Base $obj
     * @return mixed|void
     */
    protected function copyObject($obj)
    {
        // @todo
    }
}