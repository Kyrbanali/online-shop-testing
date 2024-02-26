<?php

namespace Kurbanali\MyCore\Logger;

class LoggerService
{
    private const STORAGE_PATH = "/app/Storage/Logs/";

    public static function error(string $file, int $line, string $message): void
    {
        $date = date("Y-m-d H:i:s");

        $errorContent = "File: $file\nLine: $line\nMessage: $message\nDate: $date\n\n";
        $filepath = self::STORAGE_PATH . "error.txt";

        file_put_contents($filepath, $errorContent, FILE_APPEND);
    }

    public static function info(string $infoMessage): void
    {
        $date = date("Y-m-d H:i:s");

        $infoContent = "Info Message: $infoMessage\nDate: $date\n\n";
        $infoFilepath = self::STORAGE_PATH . 'info.txt';

        file_put_contents($infoFilepath, $infoContent, FILE_APPEND);
    }

}