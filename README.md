# Lexiscope

[![CI](https://github.com/example/lexiscope/actions/workflows/ci.yml/badge.svg)](https://github.com/example/lexiscope/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

Interactive Nigerian Constitution Navigator built with Next.js. Browse, search and cross-reference constitutional provisions offline. Designed for future comparison with India and US constitutions.

![screenshot](docs/screenshot.png)

## Quickstart

```bash
pnpm i
pnpm run data:validate
pnpm run data:build-index
pnpm dev
```

## Scripts

- `data:import:text` – parse `scripts/raw/constitution_ng.txt`
- `data:import:csv` – alternative CSV import
- `data:crossrefs` – generate `crossrefs.ng.json`
- `data:build-index` – build search index into `public/index.ng.json`
- `data:validate` – validate JSON with zod

## Roadmap

- Compare mode (Nigeria vs India/US)
- Case law snippets
- PWA and offline support
- Print-friendly mode

## Contributing

Pull requests welcome! Please run lint and tests before submitting.

See [LICENSE](LICENSE) for license information.
