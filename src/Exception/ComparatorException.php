<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class ComparatorException extends Exception
{
    public static function fileNotFound(string $filename): self
    {
        return new self("File not found: $filename");
    }
    
    public static function fileNotReadable(string $filename): self
    {
        return new self("File is not readable: $filename");
    }
    
    public static function outputFileNotWritable(string $filename): self
    {
        return new self("Cannot write to output file: $filename");
    }
    
    public static function invalidConfiguration(string $detail): self
    {
        return new self("Invalid configuration: $detail");
    }
}
