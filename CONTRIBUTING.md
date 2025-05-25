# Contributing to Nepali Date Carbon

Thank you for your interest in contributing to Nepali Date Carbon! This guide will help you contribute to the project
effectively.

## Getting Started

1. Fork the repository
2. Clone your fork: `git clone https://github.com/akashpoudelnp/nepali-date-carbon.git`
3. Install dependencies: `composer install`
4. Create a new branch for your feature or fix: `git checkout -b feature-name`

## Development Guidelines

### Code Style

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards
- Use type hints for parameters and return types
- Keep methods focused on a single responsibility

### Testing

All new code must include tests:

```bash
# Run tests
./vendor/bin/pest
