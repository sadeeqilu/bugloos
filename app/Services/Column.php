<?php

namespace App\Services;

use App\Traits\Configurable as TraitsConfigurable;
use Exception;
use App\Services\Formatter;
use App\Services\Filter;

/**
 * Class Column
 */
abstract class Column
{
    use TraitsConfigurable;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var bool|null|string
     */
    protected $sort;

    /**
     * @var string $value
     */
    protected $value;

    /**
     * @var bool|null|string|BaseFilter
     */
    protected $filter;

    /**
     * @var string|Formattable
     */
    protected $format;

    /**
     * BaseColumn constructor.
     * @param array $config
     */
    public function __construct(array $config)
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
     * Render row attribute value.
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
        return $this->label ?? ucfirst($this->attribute);
    }

    /**
     * Get attribute.
     * @return string|null
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @return bool|null|string
     */
    public function getSort()
    {
        if (is_null($this->sort) || $this->sort === true) {
            return is_null($this->attribute) ? false : $this->attribute;
        }
        return $this->sort;
    }

    /**
     * @return BaseFilter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param BaseFilter $filter
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
                'name' => $this->getAttribute(),
            ]);

        } else if (is_array($this->filter)) {
            if (isset($this->filter['class']) && class_exists($this->filter['class'])) {
                $this->setFilter(
                    new $this->filter['class'](array_merge($this->filter, empty($this->filter['name']) ? [
                            'name' => $this->getAttribute()
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
