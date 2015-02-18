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
     * @param mixed $buffer
     * @param int|null $length
     * @return mixed
     */
    public function read($buffer, $length = null)
    {
        // @todo
        return null;
    }

    /**
     * @param mixed $buffer
     * @param int|null $length
     */
    public function write($buffer, $length = null)
    {
        if (is_resource($buffer)) {
            if (is_null($length)) {
                $length = -1;
            }
            $this->stream .= stream_get_contents($buffer, $length);
        } else if ($buffer instanceof Map) {
            $i = 0;
            foreach ($buffer as $index => $value) {
                $this->stream .= $value->getValue();
                $i++;
                if ($length !== null && $i == $length) {
                    break;
                }
            }
        } else if ($buffer instanceof Base) {
            if (is_null($length)) {
                $this->stream .= substr($buffer->asString(), 0);
            }
            else {
                $this->stream .= substr($buffer->asString(), 0, $length);
            }
        } else {
            $buffer = strval($buffer);
            if (is_null($length)) {
                $this->stream .= substr($buffer, 0);
            } else {
                $this->stream .= substr($buffer, 0, $length);
            }
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