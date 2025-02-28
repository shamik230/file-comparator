<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\FileComparator;
use App\Exception\ComparatorException;

try {
    if ($argc < 5) {
        echo "Usage: php index.php <input1> <input2> <output1> <output2>" . PHP_EOL;
        echo "Example: php index.php file1.txt file2.txt unique1.txt unique2.txt" . PHP_EOL;
        exit(1);
    }
    
    $fileComparator = new FileComparator();

    $fileComparator->loadFiles(
        $argv[1],
        $argv[2],
        $argv[3],
        $argv[4]
    )->compare();

    echo "Comparison completed successfully!" . PHP_EOL;
    exit(0);
} catch (ComparatorException $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
} catch (\Exception $e) {
    echo "Unexpected error: " . $e->getMessage() . PHP_EOL;
    exit(2);
}
