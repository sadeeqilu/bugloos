<?php

namespace App\Helpers;

use Illuminate\Http\Request;

/**
 * Class SortHelper
 */
class SortHelper
{
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    /**
     * Build sort link for model entry.
     * @param Request $request
     * @return string
     */
    public static function getSortableLink(Request $request, $column_obj): string
    {

        $sortQuery = $request->get('sort', null);

        if (is_null($sortQuery)) {
            return $request->fullUrlWithQuery([
                'sort' => $column_obj->getSort(),
            ]);
        }

        if ($sortQuery == $column_obj->getSort()) {
            return $request->fullUrlWithQuery([
                'sort' => '-' . $column_obj->getSort(),
            ]);
        }

        if ($sortQuery == ('-' . $column_obj->getSort())) {
            return $request->fullUrlWithQuery([
                'sort' => $column_obj->getSort(),
            ]);
        }

        return $request->fullUrlWithQuery([
            'sort' => $column_obj->getSort(),
        ]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public static function getSortColumn(Request $request): string
    {
        $column = $request->get('sort');

        return str_replace('-', '', $column);
    }

    /**
     * @param Request $request
     * @return string
     */
    public static function getDirection(Request $request): string
    {
        $pos = mb_strpos($request->get('sort'), '-');
        if ($pos === 0) {
            return self::SORT_DESC;
        }

        return self::SORT_ASC;
    }
}
