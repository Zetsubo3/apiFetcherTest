<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class WatchLogCommand extends Command
{
    protected $signature = 'log:watch';
    protected $description = 'Отслеживание новых записей в лог-файле в реальном времени';

    /**
     * Выполнение команды
     *
     * @return int
     */
    public function handle(): int
    {
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            $logDir = dirname($logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            touch($logFile);
            $this->info("Log file created: {$logFile}");
        }

        $this->info("Watching log file: {$logFile}");
        $this->info("Press Ctrl+C to stop");
        $this->newLine();

        $this->watchLog($logFile);

        return CommandAlias::SUCCESS;
    }

    /**
     * Отслеживание новых строк
     *
     * @param string $logFile
     * @return void
     */
    private function watchLog(string $logFile): void
    {
        $lastSize = filesize($logFile);

        while (true) {
            clearstatcache(true, $logFile);
            $currentSize = filesize($logFile);

            if ($currentSize > $lastSize) {
                $handle = fopen($logFile, 'r');
                fseek($handle, $lastSize);

                while (!feof($handle)) {
                    $line = fgets($handle);
                    if ($line !== false && $line !== '') {
                        $this->line($this->formatLine(rtrim($line)));
                    }
                }

                fclose($handle);
                $lastSize = $currentSize;
            }

            sleep(1);
        }
    }

    /**
     * Форматирование строки
     *
     * @param string $line
     * @return string
     */
    private function formatLine(string $line): string
    {
        if (stripos($line, 'ERROR') !== false || stripos($line, 'FAILED') !== false) {
            return "\033[31m{$line}\033[0m";
        }

        if (stripos($line, 'WARNING') !== false) {
            return "\033[33m{$line}\033[0m";
        }

        if (stripos($line, 'INFO') !== false || stripos($line, 'COMPLETED') !== false) {
            return "\033[32m{$line}\033[0m";
        }

        return $line;
    }
}
