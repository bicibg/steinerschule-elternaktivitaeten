<?php

return [
    'accepted' => ':attribute muss akzeptiert werden.',
    'active_url' => ':attribute ist keine gültige Internet-Adresse.',
    'after' => ':attribute muss ein Datum nach dem :date sein.',
    'after_or_equal' => ':attribute muss ein Datum nach dem :date oder gleich dem :date sein.',
    'alpha' => ':attribute darf nur aus Buchstaben bestehen.',
    'alpha_dash' => ':attribute darf nur aus Buchstaben, Zahlen, Binde- und Unterstrichen bestehen.',
    'alpha_num' => ':attribute darf nur aus Buchstaben und Zahlen bestehen.',
    'array' => ':attribute muss ein Array sein.',
    'before' => ':attribute muss ein Datum vor dem :date sein.',
    'before_or_equal' => ':attribute muss ein Datum vor dem :date oder gleich dem :date sein.',
    'between' => [
        'numeric' => ':attribute muss zwischen :min & :max liegen.',
        'file' => ':attribute muss zwischen :min & :max Kilobyte groß sein.',
        'string' => ':attribute muss zwischen :min & :max Zeichen lang sein.',
        'array' => ':attribute muss zwischen :min & :max Elemente haben.',
    ],
    'boolean' => ':attribute muss wahr oder falsch sein.',
    'confirmed' => 'Die :attribute Bestätigung stimmt nicht überein.',
    'date' => ':attribute muss ein gültiges Datum sein.',
    'date_equals' => ':attribute muss ein Datum gleich :date sein.',
    'date_format' => ':attribute entspricht nicht dem gültigen Format für :format.',
    'different' => ':attribute und :other müssen sich unterscheiden.',
    'digits' => ':attribute muss :digits Stellen haben.',
    'digits_between' => ':attribute muss zwischen :min und :max Stellen haben.',
    'dimensions' => ':attribute hat ungültige Bildabmessungen.',
    'distinct' => ':attribute beinhaltet einen bereits vorhandenen Wert.',
    'email' => ':attribute muss eine gültige E-Mail-Adresse sein.',
    'exists' => 'Der gewählte Wert für :attribute ist ungültig.',
    'file' => ':attribute muss eine Datei sein.',
    'filled' => ':attribute muss ausgefüllt werden.',
    'gt' => [
        'numeric' => ':attribute muss größer als :value sein.',
        'file' => ':attribute muss größer als :value Kilobyte sein.',
        'string' => ':attribute muss länger als :value Zeichen sein.',
        'array' => ':attribute muss mehr als :value Elemente haben.',
    ],
    'gte' => [
        'numeric' => ':attribute muss größer oder gleich :value sein.',
        'file' => ':attribute muss größer oder gleich :value Kilobyte sein.',
        'string' => ':attribute muss mindestens :value Zeichen lang sein.',
        'array' => ':attribute muss mindestens :value Elemente haben.',
    ],
    'image' => ':attribute muss ein Bild sein.',
    'in' => 'Der gewählte Wert für :attribute ist ungültig.',
    'in_array' => 'Der gewählte Wert für :attribute existiert nicht in :other.',
    'integer' => ':attribute muss eine ganze Zahl sein.',
    'ip' => ':attribute muss eine gültige IP-Adresse sein.',
    'ipv4' => ':attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6' => ':attribute muss eine gültige IPv6-Adresse sein.',
    'json' => ':attribute muss ein gültiger JSON-String sein.',
    'lt' => [
        'numeric' => ':attribute muss kleiner als :value sein.',
        'file' => ':attribute muss kleiner als :value Kilobyte sein.',
        'string' => ':attribute muss kürzer als :value Zeichen sein.',
        'array' => ':attribute muss weniger als :value Elemente haben.',
    ],
    'lte' => [
        'numeric' => ':attribute muss kleiner oder gleich :value sein.',
        'file' => ':attribute muss kleiner oder gleich :value Kilobyte sein.',
        'string' => ':attribute darf maximal :value Zeichen lang sein.',
        'array' => ':attribute darf maximal :value Elemente haben.',
    ],
    'max' => [
        'numeric' => ':attribute darf maximal :max sein.',
        'file' => ':attribute darf maximal :max Kilobyte groß sein.',
        'string' => ':attribute darf maximal :max Zeichen haben.',
        'array' => ':attribute darf maximal :max Elemente haben.',
    ],
    'mimes' => ':attribute muss den Dateityp :values haben.',
    'mimetypes' => ':attribute muss den Dateityp :values haben.',
    'min' => [
        'numeric' => ':attribute muss mindestens :min sein.',
        'file' => ':attribute muss mindestens :min Kilobyte groß sein.',
        'string' => ':attribute muss mindestens :min Zeichen lang sein.',
        'array' => ':attribute muss mindestens :min Elemente haben.',
    ],
    'not_in' => 'Der gewählte Wert für :attribute ist ungültig.',
    'not_regex' => ':attribute hat ein ungültiges Format.',
    'numeric' => ':attribute muss eine Zahl sein.',
    'password' => 'Das Passwort ist falsch.',
    'present' => ':attribute muss vorhanden sein.',
    'regex' => ':attribute Format ist ungültig.',
    'required' => ':attribute muss ausgefüllt werden.',
    'required_if' => ':attribute muss ausgefüllt werden, wenn :other den Wert :value hat.',
    'required_unless' => ':attribute muss ausgefüllt werden, außer :other hat den Wert :values.',
    'required_with' => ':attribute muss ausgefüllt werden, wenn :values vorhanden ist.',
    'required_with_all' => ':attribute muss ausgefüllt werden, wenn :values vorhanden sind.',
    'required_without' => ':attribute muss ausgefüllt werden, wenn :values nicht vorhanden ist.',
    'required_without_all' => ':attribute muss ausgefüllt werden, wenn :values nicht vorhanden sind.',
    'same' => ':attribute und :other müssen übereinstimmen.',
    'size' => [
        'numeric' => ':attribute muss gleich :size sein.',
        'file' => ':attribute muss :size Kilobyte groß sein.',
        'string' => ':attribute muss :size Zeichen lang sein.',
        'array' => ':attribute muss genau :size Elemente haben.',
    ],
    'starts_with' => ':attribute muss mit einem der folgenden Werte beginnen: :values',
    'string' => ':attribute muss ein String sein.',
    'timezone' => ':attribute muss eine gültige Zeitzone sein.',
    'unique' => ':attribute ist bereits vergeben.',
    'uploaded' => ':attribute konnte nicht hochgeladen werden.',
    'url' => ':attribute muss eine URL sein.',
    'uuid' => ':attribute muss eine UUID sein.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'email' => [
            'unique' => 'Diese E-Mail-Adresse ist bereits registriert.',
        ],
        'password' => [
            'confirmed' => 'Die Passwörter stimmen nicht überein.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'email' => 'E-Mail-Adresse',
        'password' => 'Passwort',
        'name' => 'Name',
        'title' => 'Titel',
        'content' => 'Inhalt',
        'description' => 'Beschreibung',
        'phone' => 'Telefonnummer',
        'notes' => 'Bemerkungen',
        'message' => 'Nachricht',
        'comment' => 'Kommentar',
        'body' => 'Text',
        'date' => 'Datum',
        'time' => 'Zeit',
        'location' => 'Ort',
    ],
];