<?php

return array(
    'fontDir' => base_path('vendor/dompdf/dompdf/lib/fonts/'),
    'fontCache' => storage_path('framework/cache/pdf-fonts'),
    'defaultFont' => 'dejavu sans',
    'tempDir' => sys_get_temp_dir(),
    'chroot'  => realpath(base_path()),
    'allowed_protocols' => [
        'file://' => ['rules' => []],
        'http://' => ['rules' => []],
        'https://' => ['rules' => []],
    ],
    'font_family' => 'dejavu sans',
    'font_cache' => storage_path('framework/cache/pdf-fonts'),
    'options' => [
        'defaultFont' => 'dejavu sans',
        'isRemoteEnabled' => true,
        'isFontSubsettingEnabled' => true,
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
        'isJavascriptEnabled' => true,
        'defaultMediaType' => 'screen',
        'defaultPaperSize' => 'a4',
        'defaultPaperOrientation' => 'portrait',
        'dpi' => 96,
    ],
);
