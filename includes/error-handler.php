<?php

if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
}

set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }

    error_log(
        "PHP Error: {$message} in {$file} on line {$line}"
    );

    return false;
});

set_exception_handler(function (Throwable $exception) {
    error_log(
        "Uncaught Exception: {$exception->getMessage()} in " .
        "{$exception->getFile()} on line {$exception->getLine()}"
    );

    http_response_code(500);
    exit('Something went wrong. Please try again later.');
});