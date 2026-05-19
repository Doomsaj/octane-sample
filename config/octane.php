<?php

use Laravel\Octane\Contracts\OperationTerminated;
use Laravel\Octane\Events\RequestHandled;
use Laravel\Octane\Events\RequestReceived;
use Laravel\Octane\Events\RequestTerminated;
use Laravel\Octane\Events\WorkerErrorOccurred;
use Laravel\Octane\Events\WorkerStarting;
use Laravel\Octane\Events\WorkerStopping;
use Laravel\Octane\Listeners\CollectGarbage;
use Laravel\Octane\Listeners\DisconnectFromDatabases;
use Laravel\Octane\Listeners\EnsureUploadedFilesAreValid;
use Laravel\Octane\Listeners\FlushTemporaryContainerInstances;
use Laravel\Octane\Listeners\ReportException;
use Laravel\Octane\Listeners\StopWorkerIfNecessary;

return [
    'server' => env('OCTANE_SERVER', 'frankenphp'),
    'https' => env('OCTANE_HTTPS', false),

    'listeners' => [
        WorkerStarting::class => [
            EnsureUploadedFilesAreValid::class,
        ],
        RequestReceived::class => [],
        RequestHandled::class => [],
        RequestTerminated::class => [
            FlushTemporaryContainerInstances::class,
            DisconnectFromDatabases::class,
        ],
        OperationTerminated::class => [
            CollectGarbage::class,
        ],
        WorkerErrorOccurred::class => [
            ReportException::class,
            StopWorkerIfNecessary::class,
        ],
        WorkerStopping::class => [],
    ],

    'warm' => [],
    'flush' => [],
    'garbage' => 50,
    'max_execution_time' => 30,

    'frankenphp' => [
        'https' => env('OCTANE_HTTPS', false),
        'http2' => env('OCTANE_HTTP2', false),
        'workers' => env('OCTANE_WORKERS', 'auto'),
        'max_requests' => env('OCTANE_MAX_REQUESTS', 500),
    ],

    'swoole' => [
        'options' => [
            'log_file' => storage_path('logs/swoole_http.log'),
            'package_max_length' => 10 * 1024 * 1024,
        ],
    ],
];
