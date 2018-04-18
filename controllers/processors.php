<?php
require_once 'citem.php';
class Processors extends Citem
{
    protected $Tags = [
        'cpucash' => ['cash'],
        'socket' => ['socket'],
    ];

    public $TagNames = [
        'cpucash' => [
            'cash' => 'CPU cash [MB]',
        ],
        'socket' => [
            'socket' => 'Socket',
        ],
    ];

    public $formElements = [
        'cpucash' => [
            'title' => 'CPU Cash',
            'type' => 'number',
            'table' => 'cpucash',
            'field' => 'cash',
            'min' => 0,
            'step' => 1,
            'value' => 1,
        ],

        'socket' => [
            'title' => 'Socket',
            'type' => 'text',
            'table' => 'socket',
            'field' => 'socket',
        ],
    ];
}