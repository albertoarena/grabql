<?php
/**
 * This class defines the resource type of GrabQL Runtime.
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

// @todo Mock class, it should manage a true stream
class Resource extends Base
{
    /** @var resource */
    protected $resource;

    /** @var string */
    protected $stream;

    /**
     * @param null $args
     * @return mixed|void
     */
    protected function init($args = null)
    {
        $this->stream = '';
    }

    /**
     * @return string
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * @param resource $buffer
     * @param int|null $length
     */
    public function read($buffer, $length = null)
    {
        // @todo
    }

    /**
     * @param resource|string $buffer
     * @param int|null $length
     */
    public function write($buffer, $length = null)
    {
        if (is_resource($buffer)) {
            $this->stream .= stream_get_contents($buffer, $length);
        }
        else if ($buffer instanceof Map) {
            // @todo Improve streaming of Map, currently limited to value
            foreach ($buffer as $index => $value) {
                $this->stream .= $value->getValue();
            }
        }
        else if ($buffer instanceof Base) {
            $this->stream .= substr($buffer->asString(), 0, $length);
        }
        else {
            $this->stream .= substr($buffer, 0, $length);
        }
    }

    /**
     * @return mixed|string
     */
    protected function asFlat()
    {
        return '[resource]';
    }

    /**
     * @return string
     */
    protected function asString()
    {
        return '[resource]';
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