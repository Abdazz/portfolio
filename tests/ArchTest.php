<?php

arch('no Fortify references remain in application code')
    ->expect('App')
    ->not->toUse('Laravel\\Fortify');

arch('models extend Eloquent Model')
    ->expect('App\\Models')
    ->toExtend('Illuminate\\Database\\Eloquent\\Model')
    ->ignoring('App\\Models\\AuditLog');

arch('Filament resources extend the Resource base class')
    ->expect('App\\Filament\\Resources')
    ->toExtend('Filament\\Resources\\Resource')
    ->ignoring([
        'App\\Filament\\Resources\\Experiences\\Schemas',
        'App\\Filament\\Resources\\Experiences\\Tables',
        'App\\Filament\\Resources\\Experiences\\Pages',
        'App\\Filament\\Resources\\Education\\Schemas',
        'App\\Filament\\Resources\\Education\\Tables',
        'App\\Filament\\Resources\\Education\\Pages',
        'App\\Filament\\Resources\\Skills\\Schemas',
        'App\\Filament\\Resources\\Skills\\Tables',
        'App\\Filament\\Resources\\Skills\\Pages',
        'App\\Filament\\Resources\\Certifications\\Schemas',
        'App\\Filament\\Resources\\Certifications\\Tables',
        'App\\Filament\\Resources\\Certifications\\Pages',
        'App\\Filament\\Resources\\LanguageSpokens\\Schemas',
        'App\\Filament\\Resources\\LanguageSpokens\\Tables',
        'App\\Filament\\Resources\\LanguageSpokens\\Pages',
        'App\\Filament\\Resources\\Profiles\\Schemas',
        'App\\Filament\\Resources\\Profiles\\Pages',
    ]);

arch('observers live in the Observers namespace')
    ->expect('App\\Observers')
    ->toHaveSuffix('Observer');
