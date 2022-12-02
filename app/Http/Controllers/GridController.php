<?php

namespace App\Http\Controllers;

use App\Services\ArrayData;
use Illuminate\Http\Request;

class GridController extends BaseController
{

    public function getGrid(Request $request)
    {
        $arrayData = [
            'id' => 1,
            'active' => 'active'
        ];
        $data = 
            [
                'data' => $arrayData,
                'paginatorOptions' => [ 
                    'pageName' => 'p'
                ],
                'rowsPerPage' => 5, 
                'columnFields' => [
                    [
                        'attribute' => 'id', 
                        'label' => 'ID', 
                        'filter' => false, 
                    ],
                    [
                        'label' => 'Active', 
                        'value' => 'active',
                        'filter' => [
                            'class' => App\Services\Filter::class, 
                            'name' => 'active', 
                            'data' => [ 
                                0 => 'Inactive',
                                1 => 'Active',
                            ]
                        ],
                        'sort' => 'active' 
                    ],
                    'created_at', 
                ]
            ];

        $result = new ArrayData($data);
        $ret = [
            'count' => $result->getCount(),
            'paginatedData' => $result->get(5)
        ];
            
        return $this->successfulResponse($ret,"success");
    }


}
