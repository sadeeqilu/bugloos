<?php

namespace App\Services;

use App\Helpers\SortHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EloquentData.
 */
class EloquentData extends BaseData
{
    /**
     * @var Builder
     */
    protected $query;

    /**
     * EloquentData constructor.
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = clone $query;
    }

    /**
     * @param int $perPage
     * @param int $page
     * @return Collection
     */
    public function get(int $perPage = GridService::DEFAULT_ROWS_PER_PAGE, int $page = GridService::DEFAULT_PAGE_NUMBER): Collection
    {
        return $this->query->offset(($page - 1) * $perPage)->limit($perPage)->get() ?? new Collection();
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
        }

        if (!is_null($request->filters)) {
            foreach ($request->filters as $column => $value) {
                if (is_null($value)) {
                    continue;
                }

                if ($strictFilters) {
                    $this->query->where($column, '=', $value);
                } else {
                    $this->query->where($column, 'like', '%' . $value . '%');
                }
            }
        }
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->query->count();
    }
}
