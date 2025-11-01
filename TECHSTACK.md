# Technology Stack Documentation

## 📋 Table of Contents
- [Programming Languages](#programming-languages)
- [Framework & Core Technologies](#framework--core-technologies)
- [Backend Technologies](#backend-technologies)
- [Frontend Technologies](#frontend-technologies)
- [Third-Party Packages](#third-party-packages)
- [Development Tools](#development-tools)
- [Infrastructure Requirements](#infrastructure-requirements)

---

## 💻 Programming Languages

| Language | Version | Purpose |
|----------|---------|---------|
| **PHP** | ^8.2 | Primary backend language for Laravel application |
| **JavaScript** | ES6+ | Frontend interactivity, charts, and dynamic features |
| **CSS** | CSS3 | Styling and responsive design |
| **SQL** | - | Database queries (SQLite/MySQL) |
| **Blade** | - | Laravel's templating language |

---

## 🚀 Framework & Core Technologies

### Laravel Framework
| Component | Version | Description |
|-----------|---------|-------------|
| **Laravel Framework** | ^12.0 | Main PHP framework for MVC architecture |
| **Laravel Sanctum** | ^4.1 | API authentication system (token-based) |
| **Laravel Tinker** | ^2.10.1 | REPL for Laravel - interactive shell |

### Core Laravel Features Used
- **Eloquent ORM** - Database abstraction and relationships
- **Blade Templates** - Server-side templating engine
- **Middleware** - Request filtering and authentication
- **Migrations** - Database version control
- **Seeders & Factories** - Database population
- **Validation** - Form and request validation
- **Session Management** - User session handling
- **CSRF Protection** - Security against cross-site request forgery
- **Routing** - Web and API route management

---

## 🔧 Backend Technologies

### Database Systems
| Technology | Version | Purpose |
|------------|---------|---------|
| **SQLite** | - | Default development database (file-based) |
| **MySQL** | 5.7+ / 8.0+ | Production database (supported) |
| **MariaDB** | 10.3+ | Alternative production database (supported) |

### ORM & Database Tools
- **Eloquent ORM** - Laravel's built-in ORM for database operations
- **Query Builder** - Fluent interface for building SQL queries
- **Database Migrations** - Version control for database schema

### Authentication & Security
| Component | Description |
|-----------|-------------|
| **Session-based Authentication** | Web guard using database sessions |
| **Password Hashing** | Bcrypt with 12 rounds |
| **CSRF Protection** | Token-based protection with custom error handling |
| **Role-based Access Control (RBAC)** | Custom middleware for Director/Manager/Employee roles |
| **Branch Access Control** | Middleware to restrict data access by branch |
| **AES-256-CBC Encryption** | Application-level encryption |

### Custom Middleware
- `CheckRole` - Validates user role permissions
- `CheckBranchAccess` - Restricts access to branch-specific data
- `HandleCsrfTokenMismatch` - Custom CSRF error handling
- `VerifyCsrfToken` - CSRF token verification

### Session & Cache
| Component | Driver | Configuration |
|-----------|--------|---------------|
| **Session Storage** | Database | 120 minutes lifetime |
| **Cache Store** | Database | Default caching mechanism |
| **Queue System** | Database | Background job processing |

---

## 🎨 Frontend Technologies

### Template Engine
| Technology | Description |
|------------|-------------|
| **Blade** | Laravel's powerful templating engine with directives |
| **Custom Blade Directives** | `@director`, `@manager`, `@employee` for role-based UI |

### CSS & Styling
| Technology | Version | Purpose |
|------------|---------|---------|
| **Custom CSS** | CSS3 | Hand-crafted responsive design |
| **Google Fonts** | - | Poppins font family (weights: 300-700) |
| **Font Awesome** | 6.4.0 | Icon library (via CDN) |

### JavaScript Libraries & Tools
| Library | Version | Purpose |
|---------|---------|---------|
| **Chart.js** | Latest (CDN) | Data visualization and charts |
| **Axios** | ^1.8.2 | HTTP client for AJAX requests |
| **Laravel Echo** | ^2.1.5 | Real-time event broadcasting (WebSockets) |
| **Pusher.js** | ^8.4.0 | WebSocket client for real-time features |

### Custom JavaScript Modules
| File | Purpose |
|------|---------|
| `resources/js/app.js` | Main application JavaScript, date picker enhancements |
| `resources/js/bootstrap.js` | Axios configuration and global setup |
| `resources/js/director-dashboard-charts.js` | Dashboard charts for director role |
| `public/js/toast.js` | Toast notification system (custom) |
| `public/js/mobile.js` | Mobile responsive enhancements |

### Build Tools
| Tool | Version | Purpose |
|------|---------|---------|
| **Vite** | ^6.2.4 | Modern frontend build tool and dev server |
| **Laravel Vite Plugin** | ^1.2.0 | Laravel integration for Vite |

### Vite Configuration
- **Entry Points**: 
  - `resources/css/app.css`
  - `resources/js/app.js`
  - `resources/js/director-dashboard-charts.js`
- **Features**: Hot Module Replacement (HMR), CSS source maps

---

## 📦 Third-Party Packages

### Composer Packages (Production)

#### PDF Generation
| Package | Version | Purpose |
|---------|---------|---------|
| **barryvdh/laravel-dompdf** | ^3.1 | Generate PDF reports and documents |
| **dompdf/dompdf** | ^3.0 | Core PDF rendering engine (dependency) |

### Composer Packages (Development)

#### Testing & Quality Assurance
| Package | Version | Purpose |
|---------|---------|---------|
| **PHPUnit** | ^11.5.3 | Unit and feature testing framework |
| **Mockery** | ^1.6 | Mocking library for tests |
| **FakerPHP/Faker** | ^1.23 | Generate fake data for testing/seeding |

#### Development Tools
| Package | Version | Purpose |
|---------|---------|---------|
| **Laravel Pint** | ^1.13 | Code style fixer (PSR-12 standard) |
| **Laravel Sail** | ^1.41 | Docker development environment |
| **Laravel Pail** | ^1.2.2 | Real-time log viewer |
| **Nunomaduro/Collision** | ^8.6 | Beautiful error reporting for CLI |

### NPM Packages

#### Production Dependencies
| Package | Version | Purpose |
|---------|---------|---------|
| **laravel-echo** | ^2.1.5 | Real-time event broadcasting client |
| **pusher-js** | ^8.4.0 | Pusher WebSocket client library |

#### Development Dependencies
| Package | Version | Purpose |
|---------|---------|---------|
| **axios** | ^1.8.2 | Promise-based HTTP client |
| **laravel-vite-plugin** | ^1.2.0 | Vite integration for Laravel |
| **vite** | ^6.2.4 | Next-generation frontend tooling |

### External CDN Resources
| Resource | Version | Purpose |
|----------|---------|---------|
| **Chart.js** | Latest | Data visualization library |
| **Font Awesome** | 6.4.0 | Icon library |
| **Google Fonts (Poppins)** | - | Typography |

---

## 🛠️ Development Tools

### Package Managers
| Tool | Purpose |
|------|---------|
| **Composer** | PHP dependency management |
| **NPM** | JavaScript package management |

### Build & Development Scripts

#### Composer Scripts
```bash
composer dev          # Run concurrent dev servers (Laravel + Vite + Queue + Logs)
composer test         # Run PHPUnit tests
```

#### NPM Scripts
```bash
npm run dev          # Start Vite dev server with HMR
npm run build        # Build production assets
```

### Development Environment
| Tool | Purpose |
|------|---------|
| **Laravel Artisan** | CLI tool for Laravel commands |
| **Laravel Sail** | Docker-based local development |
| **Concurrently** | Run multiple dev processes simultaneously |

### Code Quality Tools
| Tool | Purpose |
|------|---------|
| **Laravel Pint** | Automatic code formatting (PSR-12) |
| **PHPUnit** | Automated testing |
| **Laravel Pail** | Real-time log monitoring |

---

## 🖥️ Infrastructure Requirements

### PHP Requirements
| Requirement | Version/Value |
|-------------|---------------|
| **PHP Version** | >= 8.2 |
| **Required Extensions** | PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo |
| **Memory Limit** | 256M (recommended) |
| **Max Execution Time** | 60 seconds (recommended) |

### Database Requirements

#### SQLite (Development)
- SQLite 3.8.8+
- File-based database: `database/database.sqlite`
- Foreign key constraints enabled

#### MySQL (Production)
- MySQL 5.7+ or MySQL 8.0+
- Character Set: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`

#### MariaDB (Alternative)
- MariaDB 10.3+
- Character Set: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`

### Web Server Requirements
| Server | Configuration |
|--------|---------------|
| **Apache** | mod_rewrite enabled, .htaccess support |
| **Nginx** | URL rewriting configured |
| **PHP Built-in Server** | For development only (`php artisan serve`) |

### Node.js Requirements
| Requirement | Version |
|-------------|---------|
| **Node.js** | >= 18.0 |
| **NPM** | >= 9.0 |

### Server Configuration
| Setting | Value |
|---------|-------|
| **Document Root** | `/public` directory |
| **PHP CLI Workers** | 4 (configurable) |
| **Session Lifetime** | 120 minutes |
| **Upload Max Filesize** | 2M (default, adjustable) |
| **Post Max Size** | 8M (default, adjustable) |

### Environment Variables
Key environment variables required (see `.env.example`):
- `APP_KEY` - Application encryption key (generated via `php artisan key:generate`)
- `DB_CONNECTION` - Database driver (sqlite/mysql/mariadb)
- `DB_DATABASE` - Database name or path
- `SESSION_DRIVER` - Session storage driver (database recommended)
- `CACHE_STORE` - Cache storage driver (database default)
- `QUEUE_CONNECTION` - Queue driver (database default)

### File Permissions
| Directory | Permission |
|-----------|------------|
| `storage/` | 775 (writable) |
| `bootstrap/cache/` | 775 (writable) |
| `database/` | 775 (writable for SQLite) |

---

## 📊 Architecture Overview

### Design Patterns
- **MVC (Model-View-Controller)** - Core architectural pattern
- **Repository Pattern** - Data access abstraction (via Eloquent)
- **Service Provider Pattern** - Dependency injection and bootstrapping
- **Middleware Pattern** - Request/response filtering
- **Factory Pattern** - Database seeding and testing

### Key Features
- ✅ Multi-branch management
- ✅ Role-based access control (Director/Manager/Employee)
- ✅ Real-time notifications (Laravel Echo + Pusher)
- ✅ PDF report generation
- ✅ Data visualization with charts
- ✅ Responsive mobile-friendly design
- ✅ Toast notification system
- ✅ Session-based authentication
- ✅ CSRF protection
- ✅ Database-driven sessions and cache

---

## 📝 Notes for Developers

### Getting Started
1. Install PHP 8.2+ and Composer
2. Install Node.js 18+ and NPM
3. Clone repository and run `composer install`
4. Run `npm install`
5. Copy `.env.example` to `.env`
6. Generate app key: `php artisan key:generate`
7. Create database and run migrations: `php artisan migrate`
8. Seed database: `php artisan db:seed`
9. Build assets: `npm run build` or `npm run dev`
10. Start server: `php artisan serve`

### Development Workflow
- Use `composer dev` to run all development servers concurrently
- Use `npm run dev` for hot module replacement during frontend development
- Use `php artisan pail` for real-time log monitoring
- Run `composer test` before committing changes

### Production Deployment
- Set `APP_ENV=production` and `APP_DEBUG=false`
- Run `composer install --optimize-autoloader --no-dev`
- Run `npm run build` for optimized assets
- Configure proper database (MySQL/MariaDB)
- Set up proper web server (Apache/Nginx)
- Enable caching: `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`

---

**Last Updated**: 2025-10-31  
**Laravel Version**: 12.x  
**PHP Version**: 8.2+

