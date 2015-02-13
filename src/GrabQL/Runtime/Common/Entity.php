<?php
/**
 * This class defines an entity of GrabQL Runtime.
 *
 * @package     GrabQL
 * @subpackage  Runtime
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/grabql/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/grabql
 *
 */
namespace GrabQL\Runtime\Common;

class Entity
{
    /**
     * @param string $methodName
     * @param mixed $args
     * @return $this
     * @throws \Exception
     */
    public function __call($methodName, $args)
    {
        if (preg_match('~^(set|get|nop)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];
            if (!property_exists($this, $property)) {
                throw new \Exception('Property "' . $property . '" not exists');
            }
            if ($matches[1] == 'set') {
                $this->checkArguments($args, 1, 1, $methodName);
                return $this->set($property, $args[0]);
            } else if ($matches[1] == 'get') {
                $this->checkArguments($args, 0, 0, $methodName);
                return $this->get($property);
            }
        }
        return null;

    }

    /**
     * @param string $property
     * @return mixed
     */
    public function get($property)
    {
        return $this->$property;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function set($property, $value)
    {
        $this->$property = $value;
        return $this;
    }

    /**
     * @param array $properties
     */
    public function setProperties($properties)
    {
        if (is_array($properties)) {
            foreach ($properties as $property => $value) {
                $this->set($property, $value);
            }
        }
    }

    /**
     * @param array $args
     * @param integer $min
     * @param integer $max
     * @param string $methodName
     * @throws \Exception
     */
    protected function checkArguments(array $args, $min, $max, $methodName)
    {
        $argc = count($args);
        if ($argc < $min || $argc > $max) {
            throw new \Exception('Method ' . $methodName . ' needs minimally ' . $min . ' and maximally ' . $max . ' arguments. ' . $argc . ' arguments given.');
        }
    }
}