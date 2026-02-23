# Contributing to Lexiscope

Thank you for your interest in contributing to **Lexiscope**! 🎉 Whether you're fixing a bug, adding a feature, improving documentation, or enriching constitutional data — every contribution matters.

This guide will help you get started.

---

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Workflow](#workflow)
- [Code Style](#code-style)
- [Commit Messages](#commit-messages)
- [Pull Request Process](#pull-request-process)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)

---

## Code of Conduct

By participating in this project, you agree to maintain a respectful, inclusive, and harassment-free environment. Be kind, be constructive, and focus on the work.

---

## How Can I Contribute?

There are many ways to contribute beyond writing code:

| Contribution Type | Description |
|---|---|
| 🐛 **Bug Fixes** | Found something broken? Fix it and submit a PR |
| ✨ **New Features** | Have an idea? Discuss it in an issue first, then implement |
| 📖 **Documentation** | Improve the README, inline comments, or add guides |
| ⚖️ **Legal Content** | Review AI-generated plain-English explanations for accuracy |
| 🔍 **Case Law Research** | Add or verify case law references and international comparisons |
| 🧪 **Tests** | Write feature or unit tests to improve coverage |
| 🎨 **UI/UX Improvements** | Enhance the public-facing design or admin interface |
| 🌍 **Internationalization** | Help adapt the platform for other countries' constitutions |
| 🔒 **Security** | Report vulnerabilities privately (see [Security](#security)) |

---

## Development Setup

### Prerequisites

- PHP ≥ 8.2
- Composer ≥ 2.x
- Node.js ≥ 18.x and npm
- Python ≥ 3.10 (only if working on the ingestion pipeline)
- SQLite (default for development)

### Quick Start

```bash
# 1. Fork and clone the repository
git clone https://github.com/your-username/Lexiscope.git
cd Lexiscope

# 2. Run the automated setup
composer setup

# 3. Start the development server
composer dev
```

This starts the Laravel server, queue worker, log viewer, and Vite dev server all at once. The app will be available at **<http://localhost:8000>**.

### Python Ingestion Pipeline (Optional)

Only needed if you're working on the AI ingestion features:

```bash
cd python_worker
python -m venv venv
source venv/bin/activate
pip install -r requirements.txt
cp .env.example .env
# Add your GEMINI_API_KEY to .env
```

---

## Workflow

We follow a **fork-and-branch** workflow:

### 1. Fork the Repository

Click the **Fork** button on GitHub to create your own copy.

### 2. Create a Feature Branch

```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/brief-description
```

Use descriptive branch names. Prefix with the type of change:

| Prefix | Use for |
|---|---|
| `feature/` | New features |
| `fix/` | Bug fixes |
| `docs/` | Documentation changes |
| `refactor/` | Code refactoring (no functionality change) |
| `test/` | Adding or updating tests |
| `style/` | CSS/UI changes only |

### 3. Make Your Changes

- Keep changes focused — one feature or fix per PR
- Write or update tests where applicable
- Run the test suite before submitting:

```bash
composer test
```

### 4. Push and Open a Pull Request

```bash
git push origin feature/your-feature-name
```

Then open a PR against the `main` branch on the original repository.

---

## Code Style

### PHP

We use **[Laravel Pint](https://laravel.com/docs/pint)** for code formatting, which enforces the Laravel coding style:

```bash
# Format all PHP files
./vendor/bin/pint

# Check without modifying
./vendor/bin/pint --test
```

**General PHP guidelines:**

- Follow PSR-12 coding standards
- Use type hints for parameters and return types
- Keep controllers thin — move complex logic to services or models
- Use Eloquent relationships over raw queries

### JavaScript / Alpine.js

- Use ES module syntax (`import`/`export`)
- Keep Alpine.js components small and focused
- Use `x-data`, `x-bind`, and `x-on` consistently

### Blade Templates

- Use Blade components (`<x-component>`) over `@include` where possible
- Keep templates clean — move complex logic to controllers or View Composers
- Use Tailwind CSS utility classes; avoid custom CSS unless necessary

### Python

- Follow **PEP 8** style guidelines
- Use type hints (the project uses Pydantic for schema validation)
- Keep functions small and well-documented

---

## Commit Messages

Write clear, meaningful commit messages. We recommend the following convention:

```
type(scope): short description

Optional longer description explaining the motivation
and any important context.
```

### Types

| Type | Description |
|---|---|
| `feat` | A new feature |
| `fix` | A bug fix |
| `docs` | Documentation changes |
| `style` | Formatting, missing semicolons, etc. (no logic change) |
| `refactor` | Code restructuring without changing behavior |
| `test` | Adding or updating tests |
| `chore` | Maintenance tasks, dependency updates |

### Examples

```
feat(ingestion): add support for PDF documents
fix(sidebar): correct chapter sort order for Roman numerals
docs(readme): add Python pipeline setup instructions
test(api): add ingestion endpoint validation tests
```

---

## Pull Request Process

1. **Before submitting**, make sure:
   - [ ] Your code follows the [Code Style](#code-style) guidelines
   - [ ] You've run `./vendor/bin/pint` (PHP formatting)
   - [ ] All tests pass (`composer test`)
   - [ ] You've added tests for new functionality
   - [ ] You've updated documentation if needed

2. **In your PR description**, include:
   - A clear summary of what the PR does
   - A reference to any related issue (e.g., `Closes #42`)
   - Screenshots for UI changes
   - Any special testing steps reviewers should follow

3. **Review process**:
   - A maintainer will review your PR
   - You may be asked to make adjustments
   - Once approved, a maintainer will merge your PR

---

## Reporting Bugs

When opening a bug report, please include:

1. **Summary** — A clear, concise description of the bug
2. **Steps to Reproduce** — How can we trigger the bug?
3. **Expected Behavior** — What should happen?
4. **Actual Behavior** — What actually happens?
5. **Environment** — PHP version, OS, browser, etc.
6. **Screenshots / Logs** — If applicable

Use the **Bug Report** issue template if one is available.

---

## Suggesting Features

We welcome feature ideas! When opening a feature request:

1. **Describe the problem** your feature would solve
2. **Propose a solution** — how would it work?
3. **Consider alternatives** — have you thought about other approaches?
4. **Add context** — mockups, examples, or references to similar features elsewhere

Please check existing issues first to avoid duplicates.

---

## Security

If you discover a security vulnerability, please **do not** open a public issue. Instead, contact the maintainers directly via email so the issue can be addressed responsibly before disclosure.

---

## Questions?

If you're unsure about anything, open a **Discussion** on GitHub or comment on a related issue. We're happy to help guide your contribution.

---

**Thank you for helping make the law accessible to everyone! 🏛️**
