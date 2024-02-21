<?php

namespace Service\Logger;

interface LoggerInterface
{
    public static function error(string $file, int $line, string $message);

    public static function info(string $infoMessage);
}