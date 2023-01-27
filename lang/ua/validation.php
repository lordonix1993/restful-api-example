<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Мовні лінії перевірки
    |--------------------------------------------------------------------------
    |
    | Наступні мовні рядки містять стандартні повідомлення про помилки, які використовує
    | клас валідатора. Деякі з цих правил мають кілька таких версій
    | за правилами розміру. Ви можете налаштувати кожне з цих повідомлень тут.
    |
    */

    'accepted' => ':attribute повинен бути прийнятий.',
    'accepted_if' => ':attribute повинен бути прийнятий коли :other є :value.',
    'active_url' => ':attribute не є дійсною URL-адресою.',
    'after' => ':attribute має бути датою після :date.',
    'after_or_equal' => ':attribute має бути після або дорівнювати :date.',
    'alpha' => ':attribute має містити лише літери.',
    'alpha_dash' => ':attribute має містити лише літери, числа, тире та підкреслення.',
    'alpha_num' => ':attribute має містити лише літери або числа.',
    'array' => ':attribute має бути тільки масивом.',
    'ascii' => ':attribute має тільки містити однобайтові буквено-цифрові символи та символи.',
    'before' => ':attribute має бути датою перед :date.',
    'before_or_equal' => ':attribute має бути датою перед або дорівнювати :date.',
    'between' => [
        'array' => ':attribute має бути між :min та :max елементами.',
        'file' => ':attribute має бути між :min та :max кілобайтами.',
        'numeric' => ':attribute має бути між :min та :max.',
        'string' => ':attribute має бути між :min та :max символів.',
    ],
    'boolean' => ':attribute поле повинно бути true чи false.',
    'confirmed' => ':attribute підтвердження не збігається.',
    'current_password' => 'Пароль неправильний.',
    'date' => ':attribute не є дійсною датою.',
    'date_equals' => ':attribute має дорівнювати даті :date.',
    'date_format' => ':attribute не відповідає формату :format.',
    'decimal' => ':attribute має мати :decimal знаків після коми.',
    'declined' => ':attribute має бути відхилено.',
    'declined_if' => ':attribute має бути відхилено коли :other є :value.',
    'different' => ':attribute та :other має бути різним.',
    'digits' => ':attribute має бути :digits цифрами.',
    'digits_between' => ':attribute має бути між :min та :max цифрами.',
    'dimensions' => ':attribute має недійсні розміри зображення.',
    'distinct' => ':attribute поле має повторюване значення.',
    'doesnt_end_with' => ':attribute не може закінчуватися одним із таких: :values.',
    'doesnt_start_with' => ':attribute може не починатися з одного з наступного: :values.',
    'email' => ':attribute має бути дійсною електронною адресою.',
    'ends_with' => ':attribute має закінчуватися одним із таких: :values.',
    'enum' => 'Вибране :attribute є недійсним.',
    'exists' => 'Вибране :attribute є недійсним.',
    'file' => ':attribute має бути файлом.',
    'filled' => ':attribute поле повинно мати значення.',
    'gt' => [
        'array' => ':attribute має бути більше ніж :value елементів.',
        'file' => ':attribute має бути більше ніж :value кілобайтів.',
        'numeric' => ':attribute має бути більше ніж :value.',
        'string' => ':attribute має бути більше ніж :value символів.',
    ],
    'gte' => [
        'array' => ':attribute має мати :value елементів або більше.',
        'file' => ':attribute має бути більше або дорівнювати :value кілобайтів.',
        'numeric' => ':attribute повинно бути більше або дорівнювати :value.',
        'string' => ':attribute має бути більше або дорівнювати :value символів.',
    ],
    'image' => ':attribute має бути картинкою.',
    'in' => 'Вибране :attribute є недійсним.',
    'in_array' => ':attribute поле не існує в :other.',
    'integer' => ':attribute має бути цілим числом.',
    'ip' => ':attribute має бути дійсним IP-адресою.',
    'ipv4' => ':attribute має бути дійсним IPv4 адресою.',
    'ipv6' => ':attribute має бути дійсним IPv6 адресою.',
    'json' => ':attribute має бути дійсним рядком JSON.',
    'lowercase' => ':attribute має бути в нижньому регістрі.',
    'lt' => [
        'array' => ':attribute має мати менше ніж :value елементів.',
        'file' => ':attribute має бути менше ніж :value кілобайтів.',
        'numeric' => ':attribute має бути менше ніж :value.',
        'string' => ':attribute має бути менше ніж :value символів.',
    ],
    'lte' => [
        'array' => ':attribute не повинно бути більше ніж :value елементів.',
        'file' => 'The :attribute має бути менше або дорівнювати :value кілобайтів.',
        'numeric' => 'The :attribute має бути менше або дорівнювати :value.',
        'string' => 'The :attribute має бути менше або дорівнювати :value символів.',
    ],
    'mac_address' => ':attribute має бути дійсною MAC-адресою.',
    'max' => [
        'array' => ':attribute не повинно бути більше ніж :max елементів.',
        'file' => ':attribute не має бути більше ніж :max кілобайтів.',
        'numeric' => ':attribute не має бути більше ніж :max.',
        'string' => ':attribute не має бути більше ніж :max символів.',
    ],
    'max_digits' => ':attribute не повинно бути більше ніж :max цифр.',
    'mimes' => ':attribute має бути файл типу: :values.',
    'mimetypes' => ':attribute має бути файл типу: :values.',
    'min' => [
        'array' => ':attribute повинен мати принаймні :min елементів.',
        'file' => ':attribute повинно бути принаймні :min кілобайтів.',
        'numeric' => ':attribute повинно бути принаймні :min.',
        'string' => ':attribute повинно бути принаймні :min символів.',
    ],
    'min_digits' => ':attribute повинен мати принаймні :min цифр.',
    'multiple_of' => ':attribute має бути кратним :value.',
    'not_in' => 'Вибране :attribute не дійсне.',
    'not_regex' => ':attribute формат не дійсний.',
    'numeric' => ':attribute має бути числом.',
    'password' => [
        'letters' => ':attribute має містити хоча б одну букву.',
        'mixed' => ':attribute має містити принаймні одну велику та одну малу літери.',
        'numbers' => ':attribute має містити хоча б одне число.',
        'symbols' => ':attribute має містити хоча б один символ.',
        'uncompromised' => 'Дане :attribute з\'явився в результаті витоку даних. Виберіть інше :attribute.',
    ],
    'present' => ':attribute має бути присутнім поле.',
    'prohibited' => ':attribute поле заборонено.',
    'prohibited_if' => ':attribute поле заборонено, коли :other є :value.',
    'prohibited_unless' => ':attribute поле заборонено, якщо тільки :other є в :values.',
    'prohibits' => ':attribute поле забороняє :other від присутності.',
    'regex' => ':attribute формат недійсний.',
    'required' => ':attribute поле обовязкове',
    'required_array_keys' => ':attribute поле має містити записи для: :values.',
    'required_if' => ':attribute поле є обов’язковим, коли :other є :value.',
    'required_if_accepted' => ':attribute поле є обов’язковим, коли :other прийнято.',
    'required_unless' => ':attribute поле є обов’язковим, якщо не :other в :values.',
    'required_with' => ':attribute поле є обов’язковим, коли :values присутній.',
    'required_with_all' => ':attribute поле є обов’язковим, коли :values присутні.',
    'required_without' => ':attribute поле є обов’язковим, коли :values немає.',
    'required_without_all' => ':attribute поле є обов’язковим, якщо немає жодного з :values присутні.',
    'same' => ':attribute та :other повинні відповідати.',
    'size' => [
        'array' => ':attribute має містити :size елементів.',
        'file' => ':attribute має бути :size кілобайтів.',
        'numeric' => ':attribute має бути :size.',
        'string' => ':attribute має бути :size символами.',
    ],
    'starts_with' => ':attribute має починатись одним з наступних: :values.',
    'string' => ':attribute має бути рядком.',
    'timezone' => ':attribute має бути дійсний часовий пояс.',
    'unique' => ':attribute вже існує.',
    'uploaded' => ':attribute не вдалося завантажити.',
    'uppercase' => ':attribute не відвідайте завантажити.',
    'url' => ':attribute має бути дійсною URL-адресою.',
    'ulid' => ':attribute має бути дійсним ULID.',
    'uuid' => ':attribute має бути дійсним UUID.',

    /*
    |--------------------------------------------------------------------------
    | Спеціальні рядки мови перевірки
    |--------------------------------------------------------------------------
    |
    | Тут ви можете вказати спеціальні повідомлення перевірки для атрибутів за допомогою
    | конвенція "attribute.rule", щоб назвати рядки. Це дозволяє швидко
    | вказати особливий мовний рядок для даного правила атрибута.
    |
    */

    'custom' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Спеціальні атрибути перевірки
    |--------------------------------------------------------------------------
    |
    | Наступні мовні рядки використовуються для заміни заповнювача атрибутів
    | з чимось більш зручним для читання, наприклад, "Адреса електронної пошти".
    | "електронної пошти". Це просто допомагає нам зробити наше повідомлення більш виразним.
    |
    */

    'attributes' => [],

];
