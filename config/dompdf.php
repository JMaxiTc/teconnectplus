<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings for DomPDF
    |--------------------------------------------------------------------------
    */
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),
    'temp_dir' => sys_get_temp_dir(),
    'chroot' => realpath(base_path()),
    'allowed_protocols' => [
        'file://' => ['rules' => []],
        'http://' => ['rules' => []],
        'https://' => ['rules' => []],
    ],
    'log_output_file' => null,

    /*
    |--------------------------------------------------------------------------
    | PDF Settings
    |--------------------------------------------------------------------------
    */
    'default_paper_size' => 'letter',
    'default_font' => 'sans-serif',
    'dpi' => 96,
    'enable_php' => true,
    'enable_javascript' => true,
    'enable_remote' => true,
    'font_height_ratio' => 1.1,
    'enable_html5_parser' => true,
];
