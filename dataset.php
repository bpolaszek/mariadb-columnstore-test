<?php

namespace MariaDbTest;

function uniqid()
{
    return strtr(\uniqid('', true), ['.' => '']);
}

return [
    'key1'  => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
    'key2'  => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
    'key3'  => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
    'key4'  => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
    'key5'  => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
    'key6'  => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
    'key7'  => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
    'key8'  => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
    'key9'  => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
    'key10' => [
        uniqid(),
        uniqid(),
        uniqid(),
    ],
];