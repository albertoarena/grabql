<?php
/**
 * This class defines an abstract command of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime\Command;

use GrabQL\Runtime\Common\Entity;
use GrabQL\Runtime\Common\Executable;

abstract class Command extends Entity implements Executable
{
    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        foreach ($options as $key => $value)
        {
            $this->{"set${key}"}($value);
        }
    }
} 