<?php
declare(strict_types=1);

final class Logger
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function info(string $message): void
    {
        $this->write('INFO', $message);
    }

    public function error(string $message): void
    {
        $this->write('ERROR', $message);
    }

    private function write(string $level, string $message): void
    {
        $line = sprintf(
            "[%s] %s %s%s",
            date('Y-m-d H:i:s'),
            $level,
            $message,
            PHP_EOL
        );
        file_put_contents($this->filePath, $line, FILE_APPEND);
    }
}
