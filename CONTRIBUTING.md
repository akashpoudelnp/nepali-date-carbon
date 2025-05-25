# Contributing to Nepali Date Carbon

Thank you for your interest in contributing to Nepali Date Carbon! This guide will help you contribute to the project
effectively.

## Getting Started

1. Clone this repository
2. Create a new branch for your feature or fix: `git checkout -b feature-name`
3. Commit Changes
4. Create a PR with Short and Detailed Title
5. All of the commits will be squashed and merged and PR title will be reflected as the commit message when squashed

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
