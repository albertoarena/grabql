<?php
/**
 * This class defines the executable interface of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime\Common;

interface Executable
{

    /**
     * @param array|null $args
     * @return mixed
     */
    public function execute($args = null);

} 