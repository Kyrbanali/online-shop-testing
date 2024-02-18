<?php

namespace Service;

class LoggerService
{
    public static function error(string $file, int $line, string $message): void
    {
        $date = date("Y-m-d H:i:s");

        $errorContent = "File: $file\nLine: $line\nMessage: $message\nDate: $date\n\n";
        $filepath = "/app/Storage/Logs/error.txt";

        file_put_contents($filepath, $errorContent, FILE_APPEND);
    }

    public static function info(string $infoMessage): void
    {
        $date = date("Y-m-d H:i:s");

        $infoContent = "Info Message: $infoMessage\nDate: $date\n\n";
        $infoFilepath = '/app/Storage/Logs/info.txt';

        file_put_contents($infoFilepath, $infoContent, FILE_APPEND);
    }

}