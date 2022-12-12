<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Configurable as TraitsConfigurable;

class Filter extends Model
{
    use HasFactory;

    use TraitsConfigurable;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $data = [];

    /**
     * Filter constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->loadConfig($config);
        $this->setValue();
    }

    public function setValue(): void
    {
        $this->value = request()->input('filters.' . $this->getName(), $this->value);
    }

    /**
     * @return mixed
     */
    protected function getValue()
    {
        return $this->value ?? null;
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    protected function getData()
    {
        return $this->data;
    }
}
