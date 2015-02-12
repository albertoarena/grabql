<?php
/**
 * This class defines the join filter of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime\Filter;

use GrabQL\Interpreter\Parser\Parser;
use GrabQL\Runtime\Type\Map;

class Join extends Filter
{
    /** @internal join glue */
    const JOIN_GLUE = ',';

    /**
     * @param mixed $mixed
     * @return string
     */
    protected function join($mixed)
    {
        if ($mixed instanceof Map) {
            return implode(self::JOIN_GLUE, $mixed->toFlat());
        } else if (is_array($mixed)) {
            $ret = array();
            foreach ($mixed as $item) {
                $ret[] = $this->join($item);
            }
            return implode(self::JOIN_GLUE, $ret);
        }
        else {
            return $mixed;
        }
    }

    /**
     * @param $mixed
     * @return mixed|string
     */
    public function apply($mixed)
    {
        return $this->join($mixed);
    }
} 