<?php
require_once 'citem.php';
class Graphic_cards extends Citem
{
    protected $Tags = [
        'ram' => ['memory'],
    ];

    public $TagNames = [
        'ram' => [
            'memory' => 'RAM Memory [GB]',
        ],
    ];

    public $formElements = [
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