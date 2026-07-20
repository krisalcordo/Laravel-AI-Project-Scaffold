<?php

return [
    // Paths whose changes require browser evidence.
    'ui_paths' => [
        '#^resources/js/#',
        '#^resources/views/#',
        '#^app/Filament/#',
        '#^app/Http/Controllers/#',
        '#^app/View/#',
        '#^public/#',
        '#^routes/web\.php$#',
    ],

    // Paths whose changed behavior must map to exact PHP test methods.
    'runtime_paths' => [
        '#^app/#',
        '#^routes/#',
        '#^database/#',
        '#^resources/#',
    ],

    'frontend_paths' => [
        '#^resources/js/#',
    ],

    'migration_paths' => [
        '#^database/migrations/#',
    ],

    // Tailor these to the project's real end-to-end safety net. Every UI PR
    // needs a completed checklist line matching each pattern.
    'critical_flows' => [
        'Authentication' => '/auth|login|sign[ -]?in/i',
        'Primary application surface' => '/dashboard|home|primary application/i',
        'Core write round-trip' => '/create|update|save|round[ -]?trip/i',
    ],

    'minimum_screenshots' => 3,
    'required_test_account_count' => 1,

    // Migration PRs must update at least one file matching every companion.
    // Remove or specialize entries when the project has a different data model.
    'schema_companions' => [
        'a factory' => '#^database/factories/#',
        'a demo seeder' => '#^database/seeders/#',
    ],
];

