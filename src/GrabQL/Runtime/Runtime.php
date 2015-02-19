<?php
/**
 * This class defines the Runtime of GrabQL.
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

use GrabQL\Runtime\Common\Executable;
use GrabQL\Runtime\Type\Procedure;

class Runtime implements Executable
{
    /** @internal idle status */
    const STATUS_IDLE = 'idle';

    /** @internal bootstrap status */
    const STATUS_BOOTSTRAP = 'bootstrap';

    /** @internal run status */
    const STATUS_RUN = 'run';

    /** @internal stop status */
    const STATUS_STOP = 'stop';

    /** @internal error status */
    const STATUS_ERROR = 'error';

    /** @var string */
    protected $status;

    /** @var \GrabQL\Runtime\Collection */
    protected $symbols;

    /** @var \GrabQL\Runtime\Collection */
    protected $procedures;

    /** @var \GrabQL\Runtime\Io\Out */
    protected $out;

    /** @var \GrabQL\Runtime\Io\In */
    protected $in;

    /** @var callable */
    protected $main;

    public function __construct()
    {
        // Create lists of procedures and symbols
        $this->procedures = new Collection('GrabQL\Runtime\Type\Procedure');
        $this->symbols = new Collection('GrabQL\Runtime\Type\Scalar');
        $this->out = new Io\Out();
        $this->in = new Io\In();
        $this->main = null;
        $this->status = self::STATUS_IDLE;
    }

    /**
     * @param string $status
     */
    protected function setStatus($status)
    {
        $this->status = $status;

        // @todo callback in case of status change ..
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Collection
     */
    public function procedures()
    {
        return $this->procedures;
    }

    /**
     * @return Collection
     */
    public function symbols()
    {
        return $this->symbols;
    }

    protected function loadDefaultLibraries()
    {
        // @todo load commong.gql and other registered default libraries
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setMain($callback)
    {
        if ($callback instanceof Procedure || is_callable($callback)) {
            $this->main = $callback;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function bootstrap()
    {
        $this->setStatus(self::STATUS_BOOTSTRAP);
        $this->loadDefaultLibraries();
        return $this;
    }

    /**
     * @param mixed $args
     * @return $this
     */
    public function execute($args = null)
    {
        $this->setStatus(self::STATUS_RUN);
        if ($this->main !== null) {
            if ($this->main instanceof Procedure) {
                $this->main->execute($args);
            } else {
                call_user_func($this->main, $args);
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function stop()
    {
        $this->setStatus(self::STATUS_STOP);
        return $this;
    }

    /**
     * @param String $description
     * @return $this
     */
    public function error($description)
    {
        // @todo Handle errors
        $this->out()->write('Error: ' . $description);
        $this->setStatus(self::STATUS_ERROR);
        return $this;
    }

    /**
     * @return Io\Out
     */
    public function out()
    {
        return $this->out;
    }

    /**
     * @return Io\In
     */
    public function in()
    {
        return $this->in;
    }
}