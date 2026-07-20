<?php

return [
    'accepted' => 'Поле :attribute должно быть принято.',
    'array' => 'Поле :attribute должно быть массивом.',
    'boolean' => 'Поле :attribute должно быть true или false.',
    'confirmed' => 'Подтверждение поля :attribute не совпадает.',
    'email' => 'Поле :attribute должно быть корректным email-адресом.',
    'exists' => 'Выбранное значение для :attribute некорректно.',
    'integer' => 'Поле :attribute должно быть целым числом.',
    'max' => [
        'array' => 'Поле :attribute не должно содержать больше :max элементов.',
        'file' => 'Файл :attribute не должен быть больше :max КБ.',
        'numeric' => 'Поле :attribute не должно быть больше :max.',
        'string' => 'Поле :attribute не должно быть длиннее :max символов.',
    ],
    'min' => [
        'array' => 'Поле :attribute должно содержать не меньше :min элементов.',
        'file' => 'Файл :attribute должен быть не меньше :min КБ.',
        'numeric' => 'Поле :attribute должно быть не меньше :min.',
        'string' => 'Поле :attribute должно быть не короче :min символов.',
    ],
    'required' => 'Поле :attribute обязательно для заполнения.',
    'string' => 'Поле :attribute должно быть строкой.',
    'unique' => 'Такое значение поля :attribute уже существует.',

    'attributes' => [
        'name' => 'имя',
        'email' => 'email',
        'password' => 'пароль',
        'password_confirmation' => 'подтверждение пароля',
        'title' => 'заголовок',
        'content' => 'содержимое',
        'visibility' => 'видимость',
        'tags' => 'теги',
        'message' => 'сообщение',
        'status' => 'статус',
    ],
];
