<?php

require './Validator.php';

$schema = [
    'name' => [
        'required' => true,
        'type' => 'string',
        'min' => 3,
        'max' => 50
    ],
    'email' => [
        'required' => true,
        'type' => 'string',
        'regex' => '/^.+@.+\..+$/'
    ],
    'age' => [
        'required' => true,
        'type' => 'integer',
        'min' => 18,
        'max' => 120
    ],
    'address' => [
        'required' => false,
        'type' => 'string',
        'min' => 5,
    ],
    'email' => [
        'required' => true,
        'type' => 'string',
        'is_email' => true
    ]
];

$validator = new Validator($schema);

$data = [
    'name' => 'John Doe',
    'age' => 25,
    'address' => '1234 Elm Street',
    'email' => 'john@doe.com',

];

if ($validator->validate($data)) {
    echo 'Data is valid';
} else {
    echo 'Data is invalid<br>';
    print_r($validator->getErrors());
}