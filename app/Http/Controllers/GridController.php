<?php

namespace App\Http\Controllers;

use App\Services\ArrayData;
use Illuminate\Http\Request;
use App\Contracts\BaseDataInterface;

class GridController extends BaseController
{

    protected $dataService;

    public function __construct(BaseDataInterface $dataService)
    {
        $this->dataService = $dataService;
    }

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

        $this->dataService = new ArrayData($data);
        // $result = new ArrayData($data);
        $ret = [
            'count' => $this->dataService->getCount(),
            'paginatedData' => $this->dataService->get(5)
        ];
            
        return $this->successfulResponse($ret,"success");
    }


}
