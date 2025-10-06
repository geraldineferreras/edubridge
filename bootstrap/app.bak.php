<?php

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Console\Kernel;
use App\Exceptions\Handler;

require __DIR__.'/../vendor/autoload.php';

$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(ConsoleKernel::class, Kernel::class);
$app->singleton(ExceptionHandler::class, Handler::class);

return $app;