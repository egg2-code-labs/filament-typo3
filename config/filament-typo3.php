<?php

return [

    /**
     * Migration configuration.
     */
    'migrations' => [
        'keyType' => env('FILAMENT_TYPO3_KEY_TYPE', 'id'),
        'table_prefix' => env('FILAMENT_TYPO3_TABLE_PREFIX', ''),
    ],

    /**
     * Access control default configuration.
     */
    'access' => [
        'default_hidden' => env('FILAMENT_TYPO3_DEFAULT_HIDDEN', true),
        'enable_starttime' => env('FILAMENT_TYPO3_ENABLE_STARTTIME', true),
        'enable_endtime' => env('FILAMENT_TYPO3_ENABLE_ENDTIME', true),
        'enable_nav_hide' => env('FILAMENT_TYPO3_ENABLE_NAV_HIDE', true),
    ],

    /**
     * Sidebar configuration for page tree.
     */
    'sidebar_width' => [
        'sm' => 12,
        'md' => 3,
        'lg' => 3,
        'xl' => 3,
        '2xl' => 3,
    ],

    /**
     * Cache configuration for schema checks.
     */
    'cache' => [
        'schema_check_ttl' => 86400, // 24 hours in seconds
    ],

];
