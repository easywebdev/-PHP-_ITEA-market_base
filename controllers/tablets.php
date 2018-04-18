<?php
require_once 'citem.php';
class Tablets extends Citem
{
    protected $Tags = [
        'processor' => ['model', 'cores_count', 'clock_speed'],
        'screen' => ['size', 'matrix', 'matrix_type'],
        'ram' => ['memory'],
    ];

    public $TagNames = [
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
    ];

    public $formElements = [
        'processor' => [
            'title' => 'Processor',
            'type' => 'select',
            'table' => 'products_processors',
            'values' => [
                'table' => 'processor',
                'title' => 'model',
                'value' => 'id',
            ],
            'field' => 'processor_id',
        ],
        'screen' => [
            'title' => 'Screen',
            'type' => 'select',
            'table' => 'products_screens',
            'values' => [
                'table' => 'screen',
                'title' => 'size',
                'value' => 'id'
            ],
            'field' => 'screen_id',
        ],
        'ram' => [
            'title' => 'Memory',
            'type' => 'number',
            'table' => 'ram',
            'field' => 'memory',
            'min' => 0,
            'step' => 1,
            'value' => 1,
        ],
    ];
}