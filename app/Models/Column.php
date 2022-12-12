<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Configurable as TraitsConfigurable;
use Exception;
use App\Models\Formatter;
use App\Models\Filter;

class Column extends Model
{
    use HasFactory;

    use TraitsConfigurable;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $column_attribute;

    /**
     * @var bool|null|string
     */
    protected $sort;

    /**
     * @var string $value
     */
    protected $value;

    /**
     * @var bool|null|string|Filter
     */
    protected $filter;

    /**
     * @var string|Formattable
     */
    protected $format;

    /**
     * Column constructor.
     * @param object $config
     */
    public function __construct(object $config)
    {
        $this->loadConfig($config);
        $this->buildFilter();
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Render row column_attribute value.
     * @param $row
     * @return mixed
     */
    public function render($row)
    {
        return $this->formatTo($this->getValue($row));
    }

    /**
     * Format value with formatter.
     * @param $value
     * @return mixed
     */
    public function formatTo($value)
    {
        return $this->format->format($value);
    }

    /**
     * Get title for grid head.
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label ?? ucfirst($this->column_attribute);
    }

    /**
     * Get column_attribute.
     * @return string|null
     */
    public function getColumnAttribute()
    {
        return $this->column_attribute;
    }

    /**
     * @return bool|null|string
     */
    public function getSort()
    {
        if (is_null($this->sort) || $this->sort === true) {
            return is_null($this->column_attribute) ? false : $this->column_attribute;
        }
        return $this->sort;
    }

    /**
     * @return Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param Filter $filter
     */
    protected function setFilter(Filter $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * @return void
     */
    protected function buildFilter(): void
    {
        if (is_null($this->filter)) {
            $this->filter = new Filter([
                'name' => $this->getColumnAttribute(),
            ]);

        } else if (is_array($this->filter)) {
            if (isset($this->filter['class']) && class_exists($this->filter['class'])) {
                $this->setFilter(
                    new $this->filter['class'](array_merge($this->filter, empty($this->filter['name']) ? [
                            'name' => $this->getColumnAttribute()
                        ] : [])
                    )
                );
            }
        }
    }

    /**
     * @param Formattable $formatter
     */
    protected function setFormatter(Formatter $formatter): void
    {
        $this->format = $formatter;
    }

    /**
     * @throws Exception
     * @return void
     */
    protected function buildFormatter(): void
    {
        if (is_array($this->format)) {
            if (isset($this->format['class']) && class_exists($this->format['class'])) {
                $this->setFormatter(new $this->format['class']($this->format));
            }
        }
    }
}
