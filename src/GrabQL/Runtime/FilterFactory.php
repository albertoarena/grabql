<?php
/**
 * This class defines a factory for filters of GrabQL Runtime.
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

class FilterFactory
{

    /** @internal */
    const PREFIX = 'GrabQL\\Runtime\\Filter\\';

    /** @var array */
    protected static $filters = array(
        'join' => 'Join',
        'json' => 'Json',
    );

    /**
     * @param string $cmd
     * @param array $args
     * @return \GrabQL\Runtime\Filter\Filter|null
     */
    public static function build($cmd, $args = null)
    {
        if (array_key_exists($cmd, self::$filters)) {
            $className = self::PREFIX . self::$filters[$cmd];
            return new $className($args);
        }
        return null;
    }

}
