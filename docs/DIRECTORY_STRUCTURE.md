# Directory Structure - Mobile Pigmy App

## Complete Directory Tree

```
mobile-pigmy-app/                    # Root directory (outside SMP folder)
│
├── 📁 app/                          # Application code
│   │
│   ├── 📁 Controllers/              # Controllers handle business logic
│   │   ├── HomeController.php      # Landing page controller
│   │   │
│   │   ├── 📁 Admin/               # Admin controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── AgentController.php
│   │   │   ├── BranchController.php
│   │   │   ├── LicenseController.php
│   │   │   └── BackupController.php
│   │   │
│   │   ├── 📁 Agent/               # Agent controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── PigmyController.php
│   │   │   ├── ReportController.php
│   │   │   └── ProfileController.php
│   │   │
│   │   ├── 📁 Bank/                # Bank controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── AgentController.php
│   │   │   ├── ReportController.php
│   │   │   ├── BackupController.php
│   │   │   └── ProfileController.php
│   │   │
│   │   └── 📁 Auth/                # Authentication controllers
│   │       ├── AdminAuthController.php
│   │       ├── AgentAuthController.php
│   │       └── BankAuthController.php
│   │
│   ├── 📁 Models/                   # Models handle database operations
│   │   ├── Model.php               # Base model class
│   │   ├── Agent.php               # Agent model
│   │   ├── Branch.php              # Branch model (to be created)
│   │   ├── Customer.php            # Customer model (to be created)
│   │   └── Collection.php          # Collection model (to be created)
│   │
│   ├── 📁 Views/                    # Views contain HTML templates
│   │   ├── home.php                # Landing page
│   │   │
│   │   ├── 📁 layouts/             # Reusable layout templates
│   │   │   ├── main.php            # Main layout
│   │   │   ├── navbar.php          # Navigation bar
│   │   │   └── footer.php          # Footer
│   │   │
│   │   ├── 📁 auth/                # Authentication views
│   │   │   ├── admin-login.php     # Admin login page
│   │   │   ├── agent-login.php     # Agent login page
│   │   │   └── bank-login.php      # Bank login page
│   │   │
│   │   ├── 📁 admin/               # Admin views
│   │   │   ├── dashboard.php       # Admin dashboard
│   │   │   ├── 📁 agents/          # Agent management views
│   │   │   ├── 📁 branches/        # Branch management views
│   │   │   └── 📁 licenses/        # License management views
│   │   │
│   │   ├── 📁 agent/               # Agent views
│   │   │   ├── dashboard.php       # Agent dashboard
│   │   │   ├── 📁 pigmy/           # Pigmy collection views
│   │   │   ├── 📁 reports/         # Report views
│   │   │   └── 📁 profile/         # Profile views
│   │   │
│   │   ├── 📁 bank/                # Bank views
│   │   │   ├── dashboard.php       # Bank dashboard
│   │   │   ├── 📁 agents/          # Agent views
│   │   │   └── 📁 reports/         # Report views
│   │   │
│   │   ├── 📁 customer/            # Customer views
│   │   │   └── transactions.php    # Transaction history
│   │   │
│   │   └── 📁 errors/              # Error pages
│   │       ├── 404.php             # Page not found
│   │       └── 500.php             # Server error
│   │
│   └── 📁 Middleware/               # Middleware classes
│       ├── AuthMiddleware.php      # Authentication middleware
│       └── CsrfMiddleware.php      # CSRF protection (to be created)
│
├── 📁 config/                       # Configuration files
│   ├── app.php                     # Application config
│   └── database.php                # Database config
│
├── 📁 core/                         # Framework core files
│   ├── Router.php                  # URL routing system
│   ├── Database.php                # Database abstraction
│   ├── Controller.php              # Base controller
│   ├── View.php                    # View renderer
│   └── Session.php                 # Session manager
│
├── 📁 routes/                       # Route definitions
│   └── web.php                     # Web routes (all URLs defined here)
│
├── 📁 public/                       # Public web root (DOCUMENT ROOT)
│   ├── index.php                   # Front controller (entry point)
│   ├── .htaccess                   # Apache rewrite rules
│   │
│   └── 📁 assets/                  # Public assets
│       ├── 📁 css/                 # Stylesheets
│       │   └── style.css           # Main stylesheet
│       │
│       ├── 📁 js/                  # JavaScript files
│       │   └── app.js              # Main JavaScript
│       │
│       └── 📁 img/                 # Images
│           └── favicon.ico         # (Copy from SMP/img/)
│
├── 📁 storage/                      # Storage directory
│   ├── 📁 backups/                 # Database backups
│   ├── 📁 uploads/                 # User uploads
│   ├── 📁 cache/                   # Application cache
│   └── 📁 logs/                    # Application logs
│
├── 📁 database/                     # Database files
│   ├── 📁 migrations/              # Migration scripts
│   ├── 📁 seeds/                   # Seed data
│   └── README.md                   # Database documentation
│
├── 📁 desktop-apps/                 # Desktop applications
│   ├── 📁 MDB/                     # MS Access version
│   └── 📁 SQL/                     # SQL version
│
├── 📁 vendor/                       # Third-party libraries
│   ├── 📁 dompdf/                  # PDF generation
│   └── 📁 phpspreadsheet/          # Excel operations
│
├── .env.example                    # Example environment file
├── .gitignore                      # Git ignore rules
├── README.md                       # Main documentation
├── MIGRATION_GUIDE.md              # Migration guide from SMP
├── QUICK_START.md                  # Quick start guide
├── DIRECTORY_STRUCTURE.md          # This file
└── setup.php                       # Setup script (delete after setup)
```

## 📖 Directory Explanations

### `/app` - Application Code
Your business logic, views, and models live here. This is where you'll spend most of your development time.

**Controllers:**
- Handle HTTP requests
- Process form data
- Interact with models
- Return views or JSON

**Models:**
- Database operations
- Business logic
- Data validation
- Query builders

**Views:**
- HTML templates
- Display data
- Forms and UI
- Minimal PHP logic

**Middleware:**
- Request filtering
- Authentication checks
- CSRF protection
- Input validation

### `/config` - Configuration
All configuration files. Easy to manage and update settings.

- **app.php**: Application settings (name, timezone, debug mode)
- **database.php**: Database connection settings

### `/core` - Framework Core
The heart of the application. These files make everything work together.

- **Router.php**: Maps URLs to controllers
- **Database.php**: Database connection and queries
- **Controller.php**: Base class for all controllers
- **View.php**: Renders PHP templates
- **Session.php**: Session management

### `/routes` - URL Routing
Define all your application URLs here. Clean and organized.

**Example:**
```php
$router->get('admin/dashboard', 'Admin\\DashboardController@index');
```

### `/public` - Web Root
**IMPORTANT:** Your web server should point here!

This is the only directory accessible from the web. Everything else is protected.

- **index.php**: Entry point - all requests go through this
- **assets/**: CSS, JavaScript, images
- **.htaccess**: URL rewriting rules

### `/storage` - Storage
Files generated or uploaded by the application.

- Needs write permissions
- Not in version control (except README files)
- Backups, uploads, cache, logs

### `/database` - Database
Database-related files.

- SQL dump files
- Migration scripts
- Seed data
- Schema documentation

### `/desktop-apps` - Desktop Applications
Python desktop applications (from SMP/Desktop).

- MDB version (MS Access)
- SQL version

### `/vendor` - Third-party Libraries
External libraries and packages.

- dompdf for PDFs
- phpspreadsheet for Excel
- Any Composer packages

## 🎯 File Organization Patterns

### Controllers
```
app/Controllers/[Section]/[Feature]Controller.php

Examples:
- app/Controllers/Admin/AgentController.php
- app/Controllers/Agent/ReportController.php
- app/Controllers/Auth/AdminAuthController.php
```

### Views
```
app/Views/[section]/[feature].php
app/Views/[section]/[feature]/[action].php

Examples:
- app/Views/admin/dashboard.php
- app/Views/admin/agents/index.php
- app/Views/admin/agents/edit.php
```

### Models
```
app/Models/[Entity].php

Examples:
- app/Models/Agent.php
- app/Models/Customer.php
- app/Models/Collection.php
```

### Routes
```php
// Pattern: HTTP_METHOD URL CONTROLLER@METHOD
$router->get('section/feature', 'SectionController@feature');
$router->post('section/feature/action', 'SectionController@action');
```

## 📐 Design Principles

### 1. Separation of Concerns
- Controllers = Logic
- Views = Display
- Models = Data
- Config = Settings

### 2. Single Responsibility
Each file/class does ONE thing well.

### 3. Don't Repeat Yourself (DRY)
Reuse code through:
- Base classes (Controller, Model)
- Helper functions
- View components
- Middleware

### 4. Security First
- Public folder separates accessible files
- Prepared statements prevent SQL injection
- CSRF protection
- Input validation
- Session security

### 5. Scalability
Easy to add new features:
1. Add route
2. Create controller
3. Create view
4. Create model (if needed)

## 🔄 Request Flow

```
1. User visits: http://yoursite.com/admin/dashboard

2. Web Server → public/index.php

3. index.php loads:
   - Configuration
   - Core classes
   - Routes
   - Initializes Router

4. Router matches: /admin/dashboard
   → Admin\DashboardController@index

5. Controller:
   - Checks authentication
   - Gets data from Model/Database
   - Loads View with data

6. View renders HTML

7. HTML sent to browser
```

## 📝 Naming Conventions

### Files
- Controllers: `PascalCase` + `Controller.php` (e.g., `AgentController.php`)
- Models: `PascalCase.php` (e.g., `Agent.php`)
- Views: `kebab-case.php` (e.g., `agent-dashboard.php`)
- Config: `lowercase.php` (e.g., `database.php`)

### Classes
- `PascalCase` (e.g., `DashboardController`)

### Methods
- `camelCase` (e.g., `getDashboardData()`)

### Routes
- `lowercase/with/slashes` (e.g., `admin/agents/edit`)

## 🎨 Best Practices

### 1. Keep Controllers Thin
Move complex logic to Models or service classes.

### 2. Keep Views Simple
Minimal PHP in views. Just display data.

### 3. Use Models for Queries
Don't write SQL in controllers.

### 4. One Route = One Action
Each route should do ONE thing.

### 5. Consistent Naming
Follow the conventions above.

## 🚀 Scaling the Structure

As your app grows, you can add:

```
app/
├── Services/           # Business logic services
├── Helpers/            # Helper functions
├── Repositories/       # Data repositories
├── Validators/         # Input validation
├── Events/             # Event handling
└── Traits/             # Reusable traits
```

---

**This structure is designed for:**
- ✅ Maintainability
- ✅ Scalability
- ✅ Security
- ✅ Testability
- ✅ Team collaboration
- ✅ Professional development

**Enjoy your new professional structure! 🎊**
