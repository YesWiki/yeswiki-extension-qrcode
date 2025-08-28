<?php

require_once __DIR__ . '/vendor/autoload.php';

// TODO : load as facade or service like in laravel
use F9WebLtd\QrCode\Generator;

$GLOBALS['qrcode'] = new Generator();
