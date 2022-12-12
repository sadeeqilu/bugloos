<?php

namespace App\Traits;

use ReflectionClass;
use stdClass;

/**
 * Class Configurable
 */
trait Configurable
{
    /**
     * @param object $config
     */
    public function loadConfig(object $config)
    {
        $attributes = $this->attributes();

        foreach ($config as $key => $value) {
            if (!property_exists($attributes, $key)) {
                continue;
            }
            $this->{$key} = $value;
        }
    }

    /**
     * @return object
     */
    protected function attributes()
    {
        $class = new ReflectionClass($this);
        $names = new stdClass();
        foreach ($class->getProperties() as $property) {
            if (!$property->isStatic()) {
                $names = $property->getName();
            }
        }

        return $names;
    }
}
