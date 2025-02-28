# File Comparator

## Installation

```bash
git clone https://github.com/shamik230/file-comparator.git
cd file-comparator
composer install
```

## Usage

### Basic Usage

```php
$comparator = new FileComparator();
$comparator->loadFiles('input1.txt', 'input2.txt', 'unique1.txt', 'unique2.txt')->compare();
```

### Command Line

```bash
php index.php input1.txt input2.txt unique1.txt unique2.txt
```

### Generate Test Files

```bash
php generator.php 1000  # Creates two files with 1000 lines each
```
