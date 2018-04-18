<?php
require_once 'citem.php';
class Motherboards extends Citem
{
    protected $Tags = [
        'socket' => ['socket'],
    ];

    public $TagNames = [
        'socket' => [
            'socket' => 'Socket',
        ],
    ];

    public $formElements = [
        'socket' => [
            'title' => 'Socket',
            'type' => 'text',
            'table' => 'socket',
            'field' => 'socket',
        ],
    ];
}