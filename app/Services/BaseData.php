<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class BaseData
 */
interface BaseData
{
    /**
     * @param int $perPage
     * @param int $page
     * @return Collection
     */
    public function get(int $perPage, int $page): Collection;

    /**
     * @param Request $request
     * @param bool $strictFilters
     * @return void
     */
    public function selectionConditions(Request $request, bool $strictFilters = false): void;

    /**
     * @return int
     */
    public function getCount(): int;
}
