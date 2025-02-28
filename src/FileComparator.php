<?php

declare(strict_types=1);

namespace App;

use App\Exception\ComparatorException;
use App\Utils\Timer;
use App\FileHandler;

class FileComparator
{
    private Timer $timer;
    private FileHandler $fileHandler;
    
    private ?string $file1 = null;
    private ?string $file2 = null;
    private ?string $outputFile1 = null;
    private ?string $outputFile2 = null;
    
    public function __construct()
    {
        $this->timer = new Timer();
        $this->fileHandler = new FileHandler();
    }
    
    /**
     * Load input files and set output file paths
     */
    public function loadFiles(
        string $file1,
        string $file2,
        string $outputFile1,
        string $outputFile2
    ): self {
        $this->file1 = $file1;
        $this->file2 = $file2;
        $this->outputFile1 = $outputFile1;
        $this->outputFile2 = $outputFile2;

        return $this;
    }
    
    /**
     * Perform the file comparison
     */
    public function compare(): void
    {
        if (!$this->file1 || !$this->file2 || !$this->outputFile1 || !$this->outputFile2) {
            throw ComparatorException::invalidConfiguration(
                'Files must be loaded before comparing'
            );
        }
        
        $this->timer->start();
        
        $this->processFiles();
        
        $this->timer->stop();
        echo "Comparison completed in: " . $this->timer->getFormattedElapsedTime() . PHP_EOL;
    }
    
    /**
     * Process the files
     */
    private function processFiles(): void
    {
        $file1 = $this->fileHandler->openFileForReading($this->file1);
        $file2 = $this->fileHandler->openFileForReading($this->file2);
        $outFile1 = $this->fileHandler->openFileForWriting($this->outputFile1);
        $outFile2 = $this->fileHandler->openFileForWriting($this->outputFile2);
        
        $file1->rewind();
        $file2->rewind();
        
        $hasLine1 = !$file1->eof();
        $hasLine2 = !$file2->eof();
        
        $value1 = $hasLine1 ? $file1->current() : null;
        $value2 = $hasLine2 ? $file2->current() : null;
        
        $linesProcessed = 0;
        
        while ($hasLine1 || $hasLine2) {
            // Compare current lines
            if ($hasLine1 && (!$hasLine2 || $value1 < $value2)) {
                // Line from file1 only
                $outFile1->fwrite($value1 . PHP_EOL);
                $file1->next();
                $hasLine1 = !$file1->eof();
                $value1 = $hasLine1 ? $file1->current() : null;
            } elseif ($hasLine2 && (!$hasLine1 || $value2 < $value1)) {
                // Line from file2 only
                $outFile2->fwrite($value2 . PHP_EOL);
                $file2->next();
                $hasLine2 = !$file2->eof();
                $value2 = $hasLine2 ? $file2->current() : null;
            } else {
                // Line exists in both files (skip)
                $file1->next();
                $file2->next();
                $hasLine1 = !$file1->eof();
                $hasLine2 = !$file2->eof();
                $value1 = $hasLine1 ? $file1->current() : null;
                $value2 = $hasLine2 ? $file2->current() : null;
            }
            
            $linesProcessed++;
            if ($linesProcessed % 100000 === 0) {
                echo "Processed $linesProcessed lines" . PHP_EOL;
            }
        }
        
        echo "Total lines processed: $linesProcessed" . PHP_EOL;
    }
}
