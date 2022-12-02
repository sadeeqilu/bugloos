<?php

namespace App\Services;

use App\Helpers\SortHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class ArrayData.
 */
class ArrayData extends BaseData
{
    /**
     * @var array
     */
    protected $array;

    /**
     * ArrayData constructor.
     * @param Builder $query
     */
    public function __construct(array $data)
    {
        $this->array = $data;
    }

    /**
     * @param int $perPage
     * @param int $page
     * @return Collection
     */
    public function get(int $perPage = GridService::DEFAULT_ROWS_PER_PAGE, int $page = GridService::DEFAULT_PAGE_NUMBER): Collection
    {
        // return $this->query->offset(($page - 1) * $perPage)->limit($perPage)->get() ?? new Collection();
        $newArray = array_chunk($this->data, $perPage, true);
        return new Collection($newArray[$page]);
    }

    /**
     * @param Request $request
     * @param bool $strictFilters
     * @return void
     */
    public function selectionConditions(Request $request, bool $strictFilters = false): void
    {
        if ($request->get('sort', null)) {
            $this->query->orderBy(SortHelper::getSortColumn($request), SortHelper::getDirection($request));
            $sort_field = array_column($this->data,SortHelper::getSortColumn($request));
            array_multisort($sort_field, SortHelper::getDirection($request), $this->data);
        }

        if (!is_null($request->filters)) {
            foreach ($request->filters as $column => $value) {
                if (is_null($value)) {
                    continue;
                }

                $results = array();
                foreach ($column as $val) {
                    if (strpos($val, $value) !== false) 
                    { 
                        $results[] = $value; 
                    }
                }

                $this->data = $results;
            }
        }
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->array);
    }
}
