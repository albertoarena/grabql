<?php
/**
 * This class defines the select command of GrabQL Runtime.
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

use Curl\Curl;
use GrabQL\Runtime\Type\Base;
use GrabQL\Runtime\Type\Reference;
use XPathSelector\Selector;
use GrabQL\Runtime\Type\Map;
use GrabQL\Runtime\Type\Procedure;
use GrabQL\Runtime\Type\Resource;
use GrabQL\Runtime\Type\Scalar;

class Select extends Command
{
    /** @var string|Base */
    protected $what;

    /** @var string|Base */
    protected $from;

    /** @var string|Base */
    protected $to;

    /** @var string|Base */
    protected $where;

    /** @var string|array|Base */
    protected $limit;

    /**
     * @param string|Base|null $what
     * @param string|Base|null $from
     * @param string|Base|null $to
     * @param string|Base|null $where
     * @param string|Base|null $limit
     */
    public function __construct($what = null, $from = null, $to = null, $where = null, $limit = null)
    {
        $this->setWhat($what);
        $this->setFrom($from);
        $this->setTo($to);
        $this->setWhere($where);
        $this->setLimit($limit);
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $validOptions = array('what', 'from', 'to', 'where', 'limit');
        $currentOption = null;

        foreach ($options as $v)
        {
            if (in_array($v, $validOptions)) {
                $currentOption = $v;
            }
            else {
                if ($currentOption === null) {
                    // first option is always "what"
                    $this->setWhat($v);
                }
                else {
                    $this->{$currentOption} = $v;
                }
            }
        }
    }

    /**
     * @param mixed $to
     * @param array | null $args
     */
    protected function findDestination($to, $args)
    {
        if ($to !== null) {
            if (is_callable($to)) {
                call_user_func($to, $this->getResults(), $args);
            } else if ($to instanceof Procedure) {
                $to->execute(array($this->getResults(), $args));
            } else if ($to instanceof Scalar) {
                $to->setValue($this->getResults());
            } else if ($to instanceof Map) {
                $results = $this->getResults();
                $to->copy($results);
            } else if ($to instanceof Resource) {
                $to->write($this->getResults());
            } else if ($to instanceof Reference) {
                $this->findDestination($to->getReference(), $args);
            }
        }
    }

    /**
     * @param array | null $args
     * @return $this
     */
    public function execute($args = null)
    {
        if ($this->to !== null) {
            $this->findDestination($this->to, $args);
        }
        return $this;
    }

    /**
     * @return array
     */
    protected function getCurlParams()
    {
        $from = $this->getFrom();
        if ($from instanceof Base) {
            $from = $from->toFlat();
        }

        if (is_array($from)) {
            $url = $from[0];
            $params = $from[1];
        } else {
            $url = $from;
            $params = array();
        }
        if (!preg_match('/^http/', $url)) {
            $url = 'http://' . $url;
        }
        return array('url' => $url, 'params' => $params);
    }

    /**
     * @param $what
     * @param $node
     * @return array|null
     */
    protected function processNode($what, $node)
    {
        $v = null;
        if (is_array($what)) {
            $v = [];
            foreach ($what as $w) {
                $v[$w] = $this->processNode($w, $node);
            }
        } else {
            switch ($what) {
                case '*':
                    $v = $node->outerHTML();
                    break;
                case '@value':
                    $v = $node->innerHTML();
                    break;
                default:
                    $v = $node->node->getAttribute($what);
                    break;
            }
        }
        return $v;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function applyXPath($data)
    {
        $ret = array();
        $xs = Selector::loadHTML($data);

        $what = $this->getWhat();
        if ($what instanceof Base) {
            $what = $what->toFlat();
        }

        $nodes = $xs->findAll($this->getWhere());
        foreach ($nodes as $node) {
            $ret[] = $this->processNode($what, $node);
        }
        return $ret;
    }

    /**
     * @return Map
     */
    public function getResults()
    {
        $params = $this->getCurlParams();

        // Get data using Curl
        $curl = new Curl();
        $curl->get($params['url'], $params['params']);
        $data = $curl->response;
        $curl->close();

        // Apply XPath query
        $result = $this->applyXPath($data);

        // Return results as a Map
        $ret = new Map(null, $result);
        //var_dump($result, $ret); exit;
        return $ret;
    }
}