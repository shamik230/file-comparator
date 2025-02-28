<?php

declare(strict_types=1);

if ($argc < 2 || !is_numeric($argv[1]) || (int)$argv[1] <= 0) {
    echo "Usage: php generator.php <number_of_lines>" . PHP_EOL;
    echo "Example: php generator.php 1000" . PHP_EOL;
    exit(1);
}

$numberOfLines = (int)$argv[1];

$categories = [
    'city' => [
        'Amsterdam', 'Berlin', 'Chicago', 'Dublin', 'Edinburgh',
        'Florence', 'Geneva', 'Helsinki', 'Istanbul', 'Jakarta',
        'Kyoto', 'London', 'Madrid', 'New York', 'Oslo',
        'Paris', 'Quebec', 'Rome', 'Sydney', 'Tokyo',
        'Uppsala', 'Vienna', 'Warsaw', 'Xiamen', 'York', 'Zurich'
    ],
    'fruit' => [
        'Apple', 'Banana', 'Cherry', 'Date', 'Elderberry',
        'Fig', 'Grape', 'Honeydew', 'Imbe', 'Jackfruit',
        'Kiwi', 'Lemon', 'Mango', 'Nectarine', 'Orange',
        'Papaya', 'Quince', 'Raspberry', 'Strawberry', 'Tangerine',
        'Ugli', 'Vanilla', 'Watermelon', 'Xigua', 'Yuzu', 'Zucchini'
    ],
    'animal' => [
        'Ant', 'Bear', 'Cat', 'Dog', 'Elephant',
        'Fox', 'Giraffe', 'Horse', 'Iguana', 'Jaguar',
        'Koala', 'Lion', 'Monkey', 'Newt', 'Octopus',
        'Penguin', 'Quail', 'Rabbit', 'Snake', 'Tiger',
        'Uakari', 'Vulture', 'Wolf', 'Xerus', 'Yak', 'Zebra'
    ],
    'color' => [
        'Amber', 'Blue', 'Crimson', 'Denim', 'Emerald',
        'Fuchsia', 'Green', 'Hazel', 'Indigo', 'Jade',
        'Khaki', 'Lavender', 'Magenta', 'Navy', 'Olive',
        'Purple', 'Quartz', 'Red', 'Silver', 'Teal',
        'Umber', 'Violet', 'White', 'Xanadu', 'Yellow', 'Zaffre'
    ]
];

$commonPercentage = 30;
$uniqueFile1Percentage = 35;
$uniqueFile2Percentage = 35;

$commonCount = (int)($numberOfLines * $commonPercentage / 100);
$uniqueFile1Count = (int)($numberOfLines * $uniqueFile1Percentage / 100);
$uniqueFile2Count = (int)($numberOfLines * $uniqueFile2Percentage / 100);

$totalCount = $commonCount + $uniqueFile1Count + $uniqueFile2Count;
if ($totalCount < $numberOfLines) {
    $uniqueFile1Count += $numberOfLines - $totalCount;
}

echo "Generating test files with the following distribution:" . PHP_EOL;
echo "- Common lines in both files: $commonCount" . PHP_EOL;
echo "- Unique lines in file1.txt: $uniqueFile1Count" . PHP_EOL;
echo "- Unique lines in file2.txt: $uniqueFile2Count" . PHP_EOL;
echo "- Total lines: " . ($commonCount + $uniqueFile1Count + $uniqueFile2Count) . PHP_EOL;

function generateReadableLine(array $categories, int $index): string {
    $categoryKey = array_rand($categories);
    $category = $categories[$categoryKey];
    
    $prefix = $category[rand(0, count($category) - 1)];
    $suffix = str_pad((string)($index + 1), 5, '0', STR_PAD_LEFT);
    
    $separator = ['_', '-', '.', ':', ' '][rand(0, 4)];
    return $categoryKey . $separator . $prefix . $separator . $suffix;
}

$allLines = [];

$commonLines = [];
for ($i = 0; $i < $commonCount; $i++) {
    $commonLines[] = generateReadableLine($categories, $i);
}

$uniqueFile1Lines = [];
for ($i = 0; $i < $uniqueFile1Count; $i++) {
    $uniqueFile1Lines[] = generateReadableLine($categories, $commonCount + $i);
}

$uniqueFile2Lines = [];
for ($i = 0; $i < $uniqueFile2Count; $i++) {
    $uniqueFile2Lines[] = generateReadableLine($categories, $commonCount + $uniqueFile1Count + $i);
}

sort($commonLines);
sort($uniqueFile1Lines);
sort($uniqueFile2Lines);

$file1Lines = array_merge($commonLines, $uniqueFile1Lines);
sort($file1Lines);

$file2Lines = array_merge($commonLines, $uniqueFile2Lines);
sort($file2Lines);

file_put_contents('file1.txt', implode(PHP_EOL, $file1Lines) . PHP_EOL);
file_put_contents('file2.txt', implode(PHP_EOL, $file2Lines) . PHP_EOL);

echo "Files generated successfully:" . PHP_EOL;
echo "- file1.txt: " . count($file1Lines) . " lines" . PHP_EOL;
echo "- file2.txt: " . count($file2Lines) . " lines" . PHP_EOL;
echo PHP_EOL;
echo "Sample data from file1.txt:" . PHP_EOL;
printSampleLines('file1.txt', 5);
echo PHP_EOL;
echo "Sample data from file2.txt:" . PHP_EOL;
printSampleLines('file2.txt', 5);

function printSampleLines(string $filename, int $count): void {
    $file = new SplFileObject($filename, 'r');
    $lines = 0;
    
    while (!$file->eof() && $lines < $count) {
        $line = $file->fgets();
        if ($line !== false) {
            echo trim($line) . PHP_EOL;
            $lines++;
        }
    }
}
