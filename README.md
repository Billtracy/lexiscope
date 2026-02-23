<p align="center">
  <img src="public/logo.png" alt="Lexiscope Logo" width="80" />
</p>

<h1 align="center">Lexiscope</h1>

<p align="center">
  <strong>The Interactive Constitution — Explore, Understand, Contribute.</strong>
</p>

<p align="center">
  <a href="#features">Features</a> •
  <a href="#tech-stack">Tech Stack</a> •
  <a href="#getting-started">Getting Started</a> •
  <a href="#architecture">Architecture</a> •
  <a href="#contributing">Contributing</a> •
  <a href="#license">License</a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.2+" />
  <img src="https://img.shields.io/badge/Python-3.10+-3776AB?style=flat-square&logo=python&logoColor=white" alt="Python 3.10+" />
  <img src="https://img.shields.io/badge/Gemini_AI-Powered-4285F4?style=flat-square&logo=google&logoColor=white" alt="Gemini AI" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="MIT License" />
</p>

---

## 🏛️ What is Lexiscope?

**Lexiscope** is an open-source legal-tech platform that transforms Nigeria's 1999 Constitution into an interactive, searchable, and understandable digital experience. Instead of reading dense legal jargon, citizens, students, lawyers, and researchers can explore constitutional provisions alongside:

- 📖 **Plain-English explanations** of every section
- ⚖️ **Case law references** linking to relevant judicial precedents
- 🌍 **International comparisons** showing how similar provisions exist in other countries (US, UK, India, etc.)
- 🏷️ **Searchable keywords** for quick navigation

Lexiscope is powered by an AI ingestion pipeline that uses Google's **Gemini AI** to automatically parse, structure, and enrich raw constitutional text from `.docx` or plain-text documents.

---

## ✨ Features

### Public-Facing

- **Hierarchical Constitution Browser** — Navigate chapters, sections, and subsections through a clean sidebar
- **Plain-English Translations** — Every legal provision is accompanied by an accessible explanation
- **Insights Slide-Over Panel** — View case law precedents and global constitutional comparisons in context
- **Keyword Tags** — Extracted keywords for each section for fast scanning
- **Dark Mode** — Full dark/light theme toggle
- **Responsive Design** — Works beautifully on desktop, tablet, and mobile

### Contributor Dashboard

- **Contributor Leaderboard** — Top contributors ranked by published constitution nodes
- **Draft Review System** — Admin workflow for reviewing, editing, and publishing AI-generated content
- **User Management** — Role-based access control (admin, contributor)

### AI Ingestion Pipeline

- **Gemini-Powered Parsing** — Feeds raw constitutional text to Google Gemini and receives structured JSON output
- **Batch Processing** — Automatically chunks large documents to stay within token limits
- **Structured Output** — Uses Pydantic schemas to enforce strict data integrity
- **Auto-Enrichment** — AI generates case law references, international comparisons, plain-English summaries, and keywords

### API

- **RESTful API** — Sanctum-authenticated endpoints for mobile app integration
- **Ingestion Endpoint** — `POST /api/ingest` for the Python worker to push parsed data

---

## 🛠️ Tech Stack

| Layer            | Technology                                                  |
|------------------|-------------------------------------------------------------|
| **Backend**      | Laravel 12, PHP 8.2+                                        |
| **Frontend**     | Blade Templates, Tailwind CSS, Alpine.js                    |
| **Database**     | SQLite (dev) / MySQL or PostgreSQL (prod)                   |
| **Auth**         | Laravel Breeze, Laravel Sanctum                             |
| **AI Pipeline**  | Python 3.10+, Google Gemini AI (`google-genai`), Pydantic   |
| **Build Tools**  | Vite, PostCSS                                               |
| **Testing**      | PHPUnit                                                     |

---

## 🚀 Getting Started

### Prerequisites

- **PHP** ≥ 8.2 with required extensions (`mbstring`, `xml`, `sqlite3`, etc.)
- **Composer** ≥ 2.x
- **Node.js** ≥ 18.x and **npm**
- **Python** ≥ 3.10 (for the ingestion pipeline)
- **SQLite** (default) or MySQL/PostgreSQL

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/Lexiscope.git
cd Lexiscope
```

### 2. Quick Setup (Recommended)

The project includes a one-command setup script:

```bash
composer setup
```

This will:

- Install PHP dependencies
- Copy `.env.example` → `.env`
- Generate an application key
- Run database migrations
- Install Node.js dependencies
- Build frontend assets

### 3. Manual Setup

If you prefer step-by-step:

```bash
# Install PHP dependencies
composer install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# Install Node.js dependencies and build assets
npm install
npm run build
```

### 4. Start the Development Server

```bash
composer dev
```

This starts **four services concurrently** using `concurrently`:

- 🌐 **Laravel server** (`php artisan serve`)
- ⚙️ **Queue worker** (`php artisan queue:listen`)
- 📋 **Log viewer** (`php artisan pail`)
- ⚡ **Vite dev server** (`npm run dev`)

Visit **<http://localhost:8000>** to see the app.

### 5. Set Up the Python Ingestion Pipeline (Optional)

```bash
cd python_worker

# Create and activate a virtual environment
python -m venv venv
source venv/bin/activate    # On Windows: venv\Scripts\activate

# Install dependencies
pip install -r requirements.txt

# Configure environment
cp .env.example .env
# Edit .env and add your GEMINI_API_KEY and LARAVEL_API_URL
```

#### Running the Ingestion Pipeline

```bash
# Parse a .docx or .txt file containing constitutional text
python ingest.py /path/to/constitution.docx

# Or run with sample data (no file needed)
python ingest.py
```

The pipeline will:

1. Read the document and split it into processable chunks
2. Send each chunk to Gemini AI for structured parsing
3. POST each extracted node to the Laravel API (`/api/ingest`)

---

## 🏗️ Architecture

```
Lexiscope/
├── app/
│   ├── Http/Controllers/       # Web & API controllers
│   │   ├── Admin/              # Draft review, user management
│   │   ├── Api/                # Ingestion API endpoint
│   │   ├── DashboardController # Contributor dashboard & leaderboard
│   │   └── PublicController    # Main interactive constitution view
│   ├── Models/
│   │   ├── ConstitutionNode    # Core model — chapters, sections, subsections
│   │   ├── CaseLawReference    # Linked case law precedents
│   │   ├── InternationalComparison  # Cross-country constitutional comparisons
│   │   └── User               # Users with role-based access
│   └── View/                   # View components
├── database/
│   ├── migrations/             # Schema definitions
│   └── seeders/                # Database seeders
├── python_worker/
│   ├── ingest.py               # AI-powered ingestion pipeline
│   └── requirements.txt        # Python dependencies
├── resources/views/
│   ├── public/                 # Public-facing constitution browser
│   ├── admin/                  # Admin draft review & user management views
│   ├── dashboard.blade.php     # Contributor dashboard
│   └── layouts/                # App layout templates
├── routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes (ingestion, Sanctum)
└── tests/                      # Feature & unit tests
```

### Data Flow

```
┌─────────────┐     Gemini AI      ┌──────────────┐     POST /api/ingest     ┌──────────────┐
│  .docx/.txt │ ─── Structured ──▶ │ Python Worker│ ────────────────────────▶ │   Laravel    │
│  Document   │     Parsing        │  (ingest.py) │     JSON payload          │   Backend    │
└─────────────┘                    └──────────────┘                           └──────┬───────┘
                                                                                     │
                                                                              Store in DB
                                                                                     │
                                                                              ┌──────▼───────┐
                                         Public Web UI  ◀─────────────────── │  Database    │
                                         Admin Review Panel                   │  (SQLite/    │
                                         REST API (Mobile)                    │   MySQL)     │
                                                                              └──────────────┘
```

---

## 🧪 Running Tests

```bash
# Run the full test suite
composer test

# Or directly with Artisan
php artisan test

# Run a specific test file
php artisan test --filter=ExampleTest
```

---

## 🤝 Contributing

We love contributions! Lexiscope is built by the community, for the community.

Please read our **[Contributing Guide](CONTRIBUTING.md)** for details on:

- Setting up your development environment
- Our branching and commit conventions
- How to submit pull requests
- Code style and quality standards

---

## 🔒 Security

If you discover a security vulnerability, please **do not** open a public issue. Instead, send an email to the maintainers so we can address it responsibly.

---

## 📄 License

Lexiscope is open-source software licensed under the **[MIT License](https://opensource.org/licenses/MIT)**.

---

<p align="center">
  <strong>Built with ❤️ for legal accessibility in Nigeria and beyond.</strong>
</p>
