<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Configurable as TraitsConfigurable;

class Formatter extends Model
{
    use HasFactory;

    use TraitsConfigurable;

    /**
     * @var null|string
     */
    protected $allowableTags = null;

    /**
     * Formatter constructor.
     * @param object $config
     */
    public function __construct($config)
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
