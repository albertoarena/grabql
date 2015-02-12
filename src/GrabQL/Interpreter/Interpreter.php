<?php
/**
 * This class defines the Interpreter of GrabQL.
 *
 * @package     GrabQL
 * @subpackage  Interpreter
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Interpreter;

use GrabQL\Interpreter\Parser\Parser;
use GrabQL\Interpreter\Parser\Parser\Lexer;
use GrabQL\Runtime\Runtime;
use GrabQL\Utils\Logger;

class Interpreter
{
    /** @internal Class ID */
    const ME = 'Interpreter';

    /** @var \GrabQL\Interpreter\Parser\Parser */
    protected $parser;

    /** @var \GrabQL\Runtime\Runtime  */
    protected $runtime;

    /** @var String */
    protected $source;

    /** @var String */
    protected $context;

    public function __construct()
    {
        $this->parser = new Parser();
        $this->runtime = new Runtime();
    }

    /**
     * @param string $path
     */
    protected function loadSource($path)
    {
        Logger::writePrefix(self::ME, 'Loading source: ' . $path);
        if (file_exists($path)) {
            // @todo Improve loading mechanism, don't use file_get_contents
            $this->source = file_get_contents($path);
            $this->normalise();
        } else {
            Logger::writePrefix(self::ME, 'File not found');
        }
    }

    /**
     * Normalise source
     */
    protected function normalise()
    {
        if (substr($this->source, 0, 3) === "\xEF\xBB\xBF") { // BOM
            $this->source = substr($this->source, 3);
        }
        $this->source = explode("\n", str_replace("\r\n", "\n", $this->source));
    }

    /**
     * Get help information
     * @return array
     */
    protected function help()
    {
        return array('Usage: gql.php [options] [<file>]',
            '',
            '  -f <file>        Parse and execute <file> for every input line',
            '');
    }

    /**
     * Process a CLI option
     * @param string $option
     * @param mixed $args
     * @return int
     */
    protected function processOption($option, $args)
    {
        $index = 0;

        switch ($option) {
            case 'c':
                // Code
                $this->source = array_shift($args);
                $this->normalise();
                $index++;
                break;

            case 'h':
            case '?':
                call_user_func_array(array('GrabQL\Utils\Logger', 'write'), $this->help());
                break;

            default:
                Logger::writePrefix(self::ME, 'Unknown option -' . $option);
                break;
        }

        return $index;
    }

    /**
     * Process a scalar
     *
     * @param $scalar
     * @return null
     * @throws \Exception
     */
    protected function processScalar($scalar)
    {
        $ret = null;
        $isObject = false;
        $openObject = null;
        foreach ($scalar as $v) {
            if ($v['type'] == Lexer::T_OPEN_CURLY_BRACE) {
                if ($isObject || $openObject) {
                    throw new \Exception('Invalid definition of an object: duplicated: {');
                }
                $isObject = true;
                $openObject = true;
            }
            else if ($v['type'] == Lexer::T_CLOSE_CURLY_BRACE) {
                if (!$isObject || !$openObject) {
                    throw new \Exception('Invalid definition of an object: missing }');
                }
            }
            else {

            }
        }
        return $ret;
    }

    /**
     * @param array $syntaxTree
     * @return array
     * @throws \Exception
     */
    protected function interpret($syntaxTree)
    {
        $this->context = '';

        foreach ($syntaxTree as $node) {
            $token = $node['token'];
            $data = $node['data'];

            //Logger::writePrefix(self::ME, 'interpret ' . json_encode($token) . ' --> ' . json_encode($data));

            $tokenInstance = TokenFactory::build($token['type']);
            if ($tokenInstance === null) {
                if ($token['type'] == Lexer::T_HASH_TAG) {
                    //
                }
                else {
                    throw new \Exception('Invalid definition');
                }
            }
            else {
                $tokenInstance->process($this->runtime, $token, $data);
            }
        }

        return array();
    }

    /**
     *
     */
    protected function execute()
    {
        try {

            if ($this->source !== null) {

                $this->runtime->out()->write('--- GQL Interpreter 0.1 ---');

                // Bootstrap runtime
                $this->runtime->bootstrap();

                // Parse source as a syntax tree
                $syntaxTree = $this->parser->parse($this->source);

                // Create main block
                $that = $this;
                $main = function () use ($that, $syntaxTree) {
                    $that->interpret($syntaxTree);
                };

                // Set runtime main source
                $this->runtime->setMain($main);

                // Execute runtime
                $this->runtime->execute();

                // Stop execution
                $this->runtime->stop();
            }
        } catch (\Exception $e) {
            Logger::writePrefix(self::ME, 'Exception: ' . $e->getMessage());
        }
    }

    /**
     * @param array $args
     */
    public function run($args)
    {
        for ($i = 0; $i < count($args); $i++) {

            if (substr($args[$i], 0, 1) == '-') {
                // Option
                $i += $this->processOption(substr($args[$i], 1), array_slice($args, $i + 1));
            } else {
                // Include file
                $this->loadSource($args[$i]);
            }
        }

        $this->execute();
    }

    /**
     * @return Runtime
     */
    public function runtime()
    {
        return $this->runtime;
    }

} 