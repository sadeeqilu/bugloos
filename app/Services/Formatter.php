<?php

namespace App\Services;

use App\Traits\Configurable as TraitsConfigurable;

/**
 * Class Formatter
 */
class Formatter
{
    use TraitsConfigurable;

    /**
     * @var null|string
     */
    protected $allowableTags = null;

    /**
     * Formatter constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->loadConfig($config);
    }

    /**
     * Format value as simple text without html tags.
     * @param $value
     * @return mixed
     */
    public function format($value)
    {
        return strip_tags($value, $this->allowableTags);
    }

}