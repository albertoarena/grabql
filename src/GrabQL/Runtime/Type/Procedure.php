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
     * @param mixed $code
     * @return bool
     */
    protected function isValidCode($code)
    {
        return (is_callable($code) || $code instanceof \Closure || (is_object($code) && get_class($code) == 'Closure'));
    }

    /**
     * @param callable|array|null $args
     * @throws \Exception
     * @return mixed|void
     */
    protected function init($args = null)
    {
        $this->setParams(array());
        $this->setCode(null);
        if (is_array($args)) {
            if (array_key_exists('code', $args)) {
                $this->setCode($args['code']);
            }
            if (array_key_exists('params', $args) && is_array($args['params'])) {
                $this->setParams($args['params']);
            }
        }
        else if ($this->isValidCode($args)) {
            $this->setCode($args);
        }
        else if (!is_null($args)) {
            throw new \Exception('Invalid callback, cannot initialise a procedure');
        }
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
     * @param callable $code
     */
    public function setCode($code)
    {
        if ($this->isValidCode($code)) {
            $this->debugLog('Procedure::setCode');
            $this->code = $code;
        }
    }

    /**
     * @return mixed|string
     */
    protected function asFlat()
    {
        return json_encode(array(
            'procedure' => array(
                'params' => $this->params,
                'code' => ($this->code != null) ? '[Closure]' : null
            )
        ));
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