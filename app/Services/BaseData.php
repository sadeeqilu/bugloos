<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class BaseData
 */
abstract class BaseData
{
    /**
     * @param int $perPage
     * @param int $page
     * @return Collection
     */
    abstract public function get(int $perPage, int $page): Collection;

    /**
     * @param Request $request
     * @param bool $strictFilters
     * @return void
     */
    abstract public function selectionConditions(Request $request, bool $strictFilters = false): void;

    /**
     * @return int
     */
    abstract public function getCount(): int;
}
