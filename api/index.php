<?php
// Vercel PHP entrypoint that boots Laravel via public/index.php

// Ensure Composer autoload is available
require __DIR__ . '/../vendor/autoload.php';

// Change working directory to project root
chdir(__DIR__ . '/..');

// Emulate the public index
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Run Laravel
require __DIR__ . '/../public/index.php';
