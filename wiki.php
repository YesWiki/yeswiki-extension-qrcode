<?php

require_once __DIR__ . '/vendor/autoload.php';

// TODO : load as facade or service like in laravel
use SimpleSoftwareIO\QrCode\Generator;

$GLOBALS['qrcode'] = new SimpleSoftwareIO\QrCode\Generator();
