<?php

declare(strict_types=1);

namespace App;

use App\Exception\ComparatorException;
use SplFileObject;
use Generator;

class FileHandler
{
    /**
     * Opens a file for reading
     */
    public function openFileForReading(string $filename): SplFileObject
    {
        if (!file_exists($filename)) {
            throw ComparatorException::fileNotFound($filename);
        }
        
        if (!is_readable($filename)) {
            throw ComparatorException::fileNotReadable($filename);
        }
        
        try {
            $file = new SplFileObject($filename, 'r');
            $file->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::READ_AHEAD);
            return $file;
        } catch (\RuntimeException $e) {
            throw ComparatorException::fileNotReadable($filename);
        }
    }
    
    /**
     * Opens a file for writing
     */
    public function openFileForWriting(string $filename): SplFileObject
    {
        try {
            return new SplFileObject($filename, 'w');
        } catch (\RuntimeException $e) {
            throw ComparatorException::outputFileNotWritable($filename);
        }
    }
    
    /**
     * Reads a file line by line
     */
    public function readLines(string $filename): Generator
    {
        $file = $this->openFileForReading($filename);
        
        while (!$file->eof()) {
            $line = $file->fgets();
            if ($line !== false) {
                yield $line;
            }
        }
    }
    
    /**
     * Writes a collection of lines to a file
     */
    public function writeLines(string $filename, iterable $lines): void
    {
        $file = $this->openFileForWriting($filename);
        
        foreach ($lines as $line) {
            $file->fwrite($line . PHP_EOL);
        }
    }
}
