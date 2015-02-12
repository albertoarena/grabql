<?php
/**
 * This class defines a factory for commands of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime;

class CommandFactory
{

    /** @internal */
    const PREFIX = 'GrabQL\\Runtime\\Command\\';

    /** @internal @var array */
    protected static $commands = array(
        'select' => 'Select',
        'echo' => 'Echoo',
    );

    /**
     * @param string $cmd
     * @param mixed|null $args
     * @return \GrabQL\Runtime\Command\Command|null
     */
    public static function build($cmd, $args = null)
    {
        if (array_key_exists($cmd, self::$commands)) {
            $className = self::PREFIX . self::$commands[$cmd];
            return new $className($args);
        }
        return null;
    }

}
