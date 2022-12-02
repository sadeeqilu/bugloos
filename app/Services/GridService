<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Traits\Configurable;

class GridService {

    use Configurable;

    const DEFAULT_PAGE_NUMBER = 1;
    const DEFAULT_ROWS_PER_PAGE = 10;


    protected $data;

    /**
     * @var bool
     */
    protected $useFilters = true;

    /**
     * @var bool
     */
    protected $strictFilters = false;

    /**
     * @var array|Request|string
     */
    protected $request;

    /**
     * @var LengthAwarePaginator $paginator
     */
    protected $paginator;

    /**
     * @var array
     */
    protected $paginatorOptions = [];

    /**
     * @var int
     */
    protected $page = self::DEFAULT_PAGE_NUMBER;

    /**
     * @var int
     */
    protected $rowsPerPage = self::DEFAULT_ROWS_PER_PAGE;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $columnFields = [];

    /**
     * Grid constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $this->loadConfig($config);
        $this->request = request();

        if (!($this->data instanceof BaseData)) {
            throw new Exception('data must be instance of '.BaseData::class);
        }
    }

    /**
     * returns grid data
     */
    public function getGrid()
    {
        $this->applyColumnsConfig();

        $this->data->selectionConditions($this->request, $this->strictFilters);

        $totalCount = $this->data->getCount();
        $pageNumber = $this->request->get($this->paginatorOptions['pageName'] ?? 'page', $this->page);

        return new LengthAwarePaginator(
            $this->data->get($this->rowsPerPage, $pageNumber),
            $totalCount,
            $this->rowsPerPage,
            $pageNumber,
            $this->paginatorOptions
        );

    }

    protected function applyColumnsConfig(): void
    {
        foreach ($this->columnFields as $key => $config) {

            $filterSubConfig = $this->useFilters ? [] : ['filter' => false];

            if (is_string($config)) {
                $config = array_merge(['attribute' => $config], $filterSubConfig);
                $this->fillColumnsObjects(new Column($config));
                continue;
            }

            if (is_array($config)) {
                $config = array_merge($config, $filterSubConfig);

                if (isset($config['class']) && class_exists($config['class'])) {
                    $this->fillColumnsObjects(new $config['class']($config));
                    continue;
                }

                $this->fillColumnsObjects(new Column($config));
            }
        }
    }

    /**
     * @param Column $columnObject
     */
    protected function fillColumnsObjects(Column $columnObject): void
    {
        $this->columnObjects = array_merge($this->columnObjects, [$columnObject]);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
