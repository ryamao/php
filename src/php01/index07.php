<?php

$people = array('Taro', 'Jiro', 'Saburo');

var_dump($people);

echo '<hr />';

echo $people[0];

echo '<hr />';

$people = array(
    'person1' => 'Taro',
    'person2' => 'Jiro',
    'person3' => 'Saburo'
);

var_dump($people);

echo '<hr />';

$people = [
    'person1' => 'taro',
    'person2' => 'jiro',
];

echo $people['person1'];

echo '<hr />';

$people = [
    [
        'last_name' => '山田',
        'first_name' => '太郎',
        'age' => 29,
        'gender' => '男性'
    ],
    [
        'last_name' => '鈴木',
        'first_name' => '次郎',
        'age' => 25,
        'gender' => '男性'
    ],
    [
        'last_name' => '佐藤',
        'first_name' => '花子',
        'age' => 20,
        'gender' => '女性'
    ]
];

echo $people[0]['last_name'];

echo '<hr />';

$people = array('Taro', 'Jiro', 'Saburo');

foreach ($people as $person) {
    echo $person;
    echo '<br />';
}

echo '<hr />';

$people = array(
    'person1' => 'Taro',
    'person2' => 'Jiro',
    'person3' => 'Saburo'
);

foreach ($people as $person => $name) {
    print $person . 'は' . $name . 'です' . '<br />';
}

echo '<hr />';

$people = [
    [
        'name' => 'Taro',
        'age' => 25,
        'gender' => 'mem'
    ],
    [
        'name' => 'Jiro',
        'age' => 20,
        'gender' => 'mem'
    ],
    [
        'name' => 'hanako',
        'age' => 16,
        'gender' => 'women'
    ]
];

foreach ($people as $person) {
    print $person['name'] . '(' . $person['age'] . '歳' . $person['gender'] . ')' . '<br />';
}
