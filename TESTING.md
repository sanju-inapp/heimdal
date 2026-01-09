# Running Tests for Heimdal

## Setup

1. **Install dependencies:**
```bash
composer install
```

## Running Tests

2. **Run all tests:**
```bash
vendor/bin/phpunit
```

3. **Run tests with detailed output:**
```bash
vendor/bin/phpunit --testdox
```

4. **Run specific test file:**
```bash
vendor/bin/phpunit tests/ResponseFactoryTest.php
```

## Test Results

✅ **All 9 tests passing:**
- ExceptionFormatter tests (3 tests)
- ExceptionHandler tests (4 tests)  
- ResponseFactory tests (2 tests)

## What the tests verify:

1. **ResponseFactory** - Creates proper JSON error responses
2. **ExceptionFormatter** - Formats exceptions correctly in debug/production mode
3. **ExceptionHandler** - Renders different exception types with correct HTTP status codes
