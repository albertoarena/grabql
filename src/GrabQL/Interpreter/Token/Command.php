<?php
/**
 * This class defines the command token of GrabQL Interpreter.
 *
 * @package     GrabQL
 * @subpackage  Interpreter
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Interpreter\Token;

use GrabQL\Interpreter\TokenFactory;
use GrabQL\Runtime\CommandFactory;

class Command extends AbstractToken
{

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function internalProcess()
    {
        //GrabQL\Utils\Logger::writePrefix('Command.internalProcess', array($this->token, $this->data));

        // Process data
        $scalarToken = TokenFactory::build(TokenFactory::SPECIAL_SCALAR);
        $scalarToken->isCommand(true);
        $scalar = $scalarToken->process($this->runtime, null, $this->data);
        //GrabQL\Utils\Logger::writePrefix('Command.internalProcess, scalar', $scalar);

        // Create an instance of the command
        $commandInstance = CommandFactory::build($this->token['value']);
        //GrabQL\Utils\Logger::writePrefix('Command.internalProcess, command', $commandInstance);

        // Execute the command
        if ($commandInstance !== null) {
            $commandInstance->setOptions($scalar);
            $commandInstance->execute($scalar);
        } else {
            throw new \Exception('Command not found: ' . $this->token['value']);
        }
    }
} 