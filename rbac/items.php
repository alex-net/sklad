<?php
return [
    'admin' => [
        'type' => 1,
        'description' => 'Админ',
        'children' => [
            'users-manage',
        ],
    ],
    'resident' => [
        'type' => 1,
        'description' => 'Житель',
    ],
    'operator' => [
        'type' => 1,
        'description' => 'Оператор',
    ],
    'inspector' => [
        'type' => 1,
        'description' => 'Ревизор',
    ],
    'respons' => [
        'type' => 1,
        'description' => 'Ответственный',
    ],
    'users-manage' => [
        'type' => 2,
        'description' => 'Управление юзерями. Удаление,добавление, редактирование любого юзера',
    ],
];
