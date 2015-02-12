<?php
/**
 * This class defines the JSON filter of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime\Filter;

use GrabQL\Runtime\Type\Base;

class Json extends Filter
{
    /**
     * @param mixed $mixed
     * @return mixed|string
     */
    public function apply($mixed)
    {
        if ($mixed instanceof Base) {
            return $mixed->toJSON();
        }
        else {
            return json_encode($mixed);
        }
    }
} 