<?php

declare(strict_types=1);

namespace App\Utils;

class Timer
{
    private float $startTime;
    private float $endTime;

    public function start(): void
    {
        $this->startTime = microtime(true);
    }

    public function stop(): void
    {
        $this->endTime = microtime(true);
    }

    public function getElapsedTimeInSeconds(): float
    {
        return $this->endTime - $this->startTime;
    }

    public function getFormattedElapsedTime(): string
    {
        $seconds = $this->getElapsedTimeInSeconds();
        
        if ($seconds < 0.001) {
            return round($seconds * 1000000) . ' μs';
        } elseif ($seconds < 1) {
            return round($seconds * 1000, 2) . ' ms';
        } else {
            return round($seconds, 3) . ' s';
        }
    }
}
