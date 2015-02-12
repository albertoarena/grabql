<?php
/**
 * This class defines the Logger of GrabQL.
 *
 * @package     GrabQL
 * @subpackage  Utils
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Utils;

class Logger
{
    /** @internal carriage return */
    const CR = "\n";

    /** @var bool */
    protected static $active = true;

    /**
     * @param bool $active
     */
    public static function setActive($active)
    {
        self::$active = ($active ? true : false);
    }

    /**
     * @param mixed $args
     */
    public static function write($args)
    {
        if (!self::$active) {
            return;
        }

        foreach (func_get_args() as $arg) {
            print_r($arg);
            echo self::CR;
        }
    }

    /**
     * @param string $prefix
     * @param mixed $args
     */
    public static function writePrefix($prefix, $args)
    {
        if (!self::$active) {
            return;
        }

        print_r('[' . $prefix . '] ');
        forward_static_call_array(array('GrabQL\\Utils\\Logger', 'write'), array_slice(func_get_args(), 1));
    }
} 