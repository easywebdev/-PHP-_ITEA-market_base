<?php
class Arrays {
/*
    var $TypeList = [
        'laptops' => ['processor', 'screen', 'ram'],
        'tablets' => ['processor', 'screen', 'ram'],
        'processors' => ['cpucash', 'socket'],
        'motherboards' => ['socket'],
        'graphic cards' => ['ram'],
    ];

var $TblCols = [
        'processor' => ['model', 'cores_count', 'clock_speed'],
        'screen' => ['size', 'matrix', 'matrix_type'],
        'ram' => ['memory'],
        'cpucash' => ['cash'],
        'socket' => ['socket'],
    ];

var $TagNames = [
        'processor' => [
            'model' => 'Processor Model',
            'cores_count' => 'Cores Count',
            'clock_speed' => 'Clock Speed [GHz]',
        ],
        'screen' => [
            'size' => 'Screen Size ["]',
            'matrix' => "Matrix type",
            'matrix_type' => 'Screen Type',
        ],
        'ram' => [
            'memory' => 'RAM Memory [GB]',
        ],
        'cpucash' => [
            'cash' => 'CPU cash [MB]',
        ],
        'socket' => [
            'socket' => 'Socket',
        ],
    ];
*/

// Key = type of products, Value = file neme
public $filename =[
        'laptops' => 'laptops',
        'tablets' => 'tablets',
        'processors' => 'processors',
        'motherboards' => 'motherboards',
        'graphic cards' => 'graphic_cards',
];
}

$arrays = new Arrays();