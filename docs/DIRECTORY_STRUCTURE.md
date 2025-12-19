# Directory Structure - Sookth Mobile Pigmy 2

## Complete Directory Tree

```
SookthMobilePigmy2/                  # Root directory
│
├── index.php                        # Front controller (entry point)
├── setup.php                        # Setup script
│
├── 📁 app/                          # Application code
│   │
│   ├── 📁 Controllers/              # Controllers handle business logic
│   │   ├── CustomerController.php  # Customer controller
│   │   ├── HomeController.php      # Landing page controller
│   │   │
│   │   ├── 📁 Admin/               # Admin controllers
│   │   │   ├── AgentController.php
│   │   │   ├── BackupController.php
│   │   │   ├── BranchController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── LicenseController.php
│   │   │   └── SettingsController.php
│   │   │
│   │   ├── 📁 Agent/               # Agent controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── LicenseController.php
│   │   │   ├── PigmyController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── ReportController.php
│   │   │   └── ResendController.php
│   │   │
│   │   ├── 📁 Api/                 # API controllers
│   │   │   └── FetchController.php
│   │   │
│   │   ├── 📁 Auth/                # Authentication controllers
│   │   │   ├── AdminAuthController.php
│   │   │   ├── AgentAuthController.php
│   │   │   └── BankAuthController.php
│   │   │
│   │   └── 📁 Bank/                # Bank controllers
│   │       ├── AgentController.php
│   │       ├── BackupController.php
│   │       ├── DashboardController.php
│   │       ├── ProfileController.php
│   │       └── ReportController.php
│   │
│   ├── 📁 Middleware/               # Middleware classes
│   │   ├── AuthMiddleware.php      # Authentication middleware
│   │   └── CsrfMiddleware.php      # CSRF protection
│   │
│   ├── 📁 Models/                   # Models handle database operations
│   │   ├── Model.php               # Base model class
│   │   ├── Agent.php               # Agent model
│   │   ├── Branch.php              # Branch model
│   │   ├── Collection.php          # Collection model
│   │   └── Customer.php            # Customer model
│   │
│   └── 📁 Views/                    # Views contain HTML templates
│       ├── home.php                # Landing page
│       │
│       ├── 📁 admin/               # Admin views
│       │   ├── dashboard.php       # Admin dashboard
│       │   ├── 📁 agents/          # Agent management views
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   └── index.php
│       │   ├── 📁 backup/          # Backup views
│       │   │   └── index.php
│       │   ├── 📁 branches/        # Branch management views
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   └── index.php
│       │   ├── 📁 dashboard/       # Dashboard components
│       │   └── 📁 licenses/        # License management views
│       │
│       ├── 📁 agent/               # Agent views
│       │   ├── change-pin.php
│       │   ├── collection.php
│       │   ├── dashboard.php
│       │   ├── dashboard.php.backup
│       │   ├── license-warning.php
│       │   ├── reset-pin.php
│       │   ├── success.php
│       │   ├── 📁 dashboard/       # Dashboard components
│       │   ├── 📁 pigmy/           # Pigmy collection views
│       │   ├── 📁 profile/         # Profile views
│       │   ├── 📁 reports/         # Report views
│       │   └── 📁 resend/          # Resend views
│       │
│       ├── 📁 auth/                # Authentication views
│       │   ├── admin-login.php
│       │   ├── agent-dev-reset-link.php
│       │   ├── agent-forgot-pin.php
│       │   ├── agent-login.php
│       │   ├── agent-reset-pin-token.php
│       │   └── bank-login.php
│       │
│       ├── 📁 bank/                # Bank views
│       │   ├── dashboard.php
│       │   ├── 📁 agents/          # Agent views
│       │   ├── 📁 backup/          # Backup views
│       │   ├── 📁 dashboard/       # Dashboard components
│       │   ├── 📁 profile/         # Profile views
│       │   └── 📁 reports/         # Report views
│       │
│       ├── 📁 customer/            # Customer views
│       │   └── transactions.php
│       │
│       ├── 📁 errors/              # Error pages
│       │   ├── 404.php             # Page not found
│       │   └── 500.php             # Server error
│       │
│       └── 📁 layouts/             # Reusable layout templates
│           ├── footer.php
│           ├── main.php
│           └── navbar.php
│
├── 📁 assets/                       # Public assets
│   ├── 📁 css/                     # Stylesheets
│   │   └── style.css
│   ├── 📁 img/                     # Images
│   └── 📁 js/                      # JavaScript files
│       ├── app.js
│       └── security.js
│
├── 📁 config/                       # Configuration files
│   ├── app.php                     # Application config
│   └── database.php                # Database config
│
├── 📁 core/                         # Framework core files
│   ├── Controller.php              # Base controller
│   ├── Database.php                # Database abstraction
│   ├── helpers.php                 # Helper functions
│   ├── license-helper.php          # License helper functions
│   ├── Router.php                  # URL routing system
│   ├── Session.php                 # Session manager
│   └── View.php                    # View renderer
│
├── 📁 database/                     # Database files
│   ├── 📁 migrations/              # Migration scripts
│   │   ├── mobile_pigmy.sql
│   │   └── mobile_pigmy_backup.sql
│   └── 📁 seeds/                   # Seed data
│
├── 📁 desktop-apps/                 # Desktop applications
│   ├── 📁 MDB/                     # MS Access version
│   └── 📁 SQL/                     # SQL version
│
├── 📁 docs/                         # Documentation
│   └── DIRECTORY_STRUCTURE.md      # This file
│
├── 📁 routes/                       # Route definitions
│   └── web.php                     # Web routes (all URLs defined here)
│
├── 📁 storage/                      # Storage directory
│   ├── 📁 backups/                 # Database backups
│   │   └── backup_2025-12-19_12-15-23.sql
│   ├── 📁 cache/                   # Application cache
│   ├── 📁 logs/                    # Application logs
│   └── 📁 uploads/                 # User uploads
│
└── 📁 vendor/                       # Third-party libraries
    ├── README.md
    ├── 📁 dompdf/                  # PDF generation
    └── 📁 phpspreadsheet/          # Excel operations
```

## 📖 Directory Explanations

### `/app` - Application Code
Your business logic, views, and models live here. This is where you'll spend most of your development time.

**Controllers:**
- Handle HTTP requests (GET, POST)
- Process form data and validation
- Interact with models for data operations
- Return views or JSON responses
- Organized by user role (Admin, Agent, Bank, Auth)

**Controller Types:**
- **Admin Controllers**: Manage agents, branches, licenses, backups
- **Agent Controllers**: Handle pigmy collections, reports, profiles
- **Bank Controllers**: Monitor agents, view reports, manage profiles
- **Auth Controllers**: Handle login, logout, password reset
- **Api Controllers**: Provide API endpoints for AJAX/mobile apps

**Models:**
- Database operations (CRUD - Create, Read, Update, Delete)
- Business logic and calculations
- Data validation and sanitization
- Query builders for complex queries
- Relationships between entities

**Available Models:**
- **Agent**: Agent management and authentication
- **Branch**: Branch information and operations
- **Customer**: Customer accounts and profiles
- **Collection**: Pigmy collection transactions
- **Model**: Base model with common database methods

**Views:**
- HTML templates with minimal PHP
- Display data passed from controllers
- Forms and user interfaces
- Reusable layouts (main, navbar, footer)
- Organized by user role and feature

**View Structure:**
- **admin/**: Admin panel views
- **agent/**: Agent dashboard and collection views
- **bank/**: Bank monitoring and reporting views
- **auth/**: Login and authentication pages
- **customer/**: Customer transaction history
- **layouts/**: Shared templates (navbar, footer, main layout)
- **errors/**: Error pages (404, 500)

**Middleware:**
- Request filtering and preprocessing
- Authentication checks (session validation)
- CSRF protection for forms
- Input validation and sanitization
- Role-based access control

**Available Middleware:**
- **AuthMiddleware**: Verify user authentication and sessions
- **CsrfMiddleware**: Protect against CSRF attacks

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
- **helpers.php**: Global helper functions
- **license-helper.php**: License management helpers

### `/routes` - URL Routing
Define all your application URLs here. Clean and organized.

**Example:**
```php
$router->get('admin/dashboard', 'Admin\\DashboardController@index');
```

### `/` - Root Directory
The application entry point and assets are at the root level.

- **index.php**: Front controller - entry point for all requests
- **setup.php**: Initial setup and installation script
- **assets/**: Public assets (CSS, JavaScript, images) - directly accessible via web

### `/storage` - Storage
Files generated or uploaded by the application.

- **backups/**: Database backup files (SQL dumps)
- **uploads/**: User uploaded files
- **cache/**: Application cache files
- **logs/**: Application logs and error logs

**Important:** This directory needs write permissions (chmod 755 or 775)

### `/database` - Database
Database-related files.

- **migrations/**: SQL dump files and migration scripts
  - mobile_pigmy.sql: Main database schema
  - mobile_pigmy_backup.sql: Backup database schema
- **seeds/**: Seed data for testing and initial setup

### `/desktop-apps` - Desktop Applications
Desktop applications for offline operations.

- **MDB/**: MS Access database version
- **SQL/**: SQL Server database version

### `/vendor` - Third-party Libraries
External libraries and packages.

- **dompdf/**: PDF generation library
- **phpspreadsheet/**: Excel file operations
- **README.md**: Vendor documentation

**Note:** These are third-party dependencies for advanced features

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
- app/Models/Branch.php
```

### Middleware
```
app/Middleware/[Feature]Middleware.php

Examples:
- app/Middleware/AuthMiddleware.php
- app/Middleware/CsrfMiddleware.php
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
- Prepared statements prevent SQL injection
- CSRF protection via CsrfMiddleware
- Input validation
- Session security
- Authentication middleware

### 5. Scalability
Easy to add new features:
1. Add route in routes/web.php
2. Create controller in app/Controllers/
3. Create view in app/Views/
4. Create model if needed in app/Models/

## 🔄 Request Flow

```
1. User visits: http://yoursite.com/admin/dashboard

2. Web Server → index.php (at root)

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

## 🎯 Key Features

### Multi-Role Support
- **Admin**: Full system control, agent management, license control
- **Agent**: Daily pigmy collections, customer management, reports
- **Bank**: Monitor operations, view consolidated reports

### Security Features
- Session-based authentication
- CSRF token protection
- SQL injection prevention (prepared statements)
- Role-based access control
- PIN/password encryption

### Core Functionality
- **Pigmy Collection**: Daily deposit collection and tracking
- **Customer Management**: Customer accounts and profiles
- **Report Generation**: Daily, monthly, and custom reports
- **License Management**: Software license validation and control
- **Database Backup**: Automated and manual backup options
- **Multi-branch Support**: Handle multiple branches and agents

### Export Capabilities
- PDF report generation (dompdf)
- Excel export (phpspreadsheet)
- SQL database backups

## 📋 File Naming Standards

### Controller Files
- Format: `[Feature]Controller.php`
- Location: `app/Controllers/[Role]/`
- Example: `AgentController.php`, `DashboardController.php`

### View Files
- Format: `[feature]-[action].php` or `[feature].php`
- Location: `app/Views/[role]/[feature]/`
- Examples:
  - `dashboard.php`
  - `agent-login.php`
  - `create.php`, `edit.php`, `index.php`

### Model Files
- Format: `[Entity].php` (singular, PascalCase)
- Location: `app/Models/`
- Example: `Agent.php`, `Customer.php`

### Configuration Files
- Format: `[purpose].php` (lowercase)
- Location: `config/`
- Example: `database.php`, `app.php`

## 💡 Development Guidelines

### Adding a New Feature
1. **Define Route** in `routes/web.php`
   ```php
   $router->get('agent/new-feature', 'Agent\FeatureController@index');
   ```

2. **Create Controller** in `app/Controllers/Agent/FeatureController.php`
   ```php
   class FeatureController extends Controller {
       public function index() {
           // Logic here
       }
   }
   ```

3. **Create View** in `app/Views/agent/new-feature.php`
   ```php
   // HTML template
   ```

4. **Create Model** (if needed) in `app/Models/Feature.php`
   ```php
   class Feature extends Model {
       // Database operations
   }
   ```

### Code Organization Tips
- Keep controllers focused on request/response
- Move complex logic to models or service classes
- Use helper functions for repetitive tasks
- Implement middleware for cross-cutting concerns
- Follow PSR coding standards

## 🔧 Configuration Files

### config/app.php
```php
return [
    'name' => 'Sookth Mobile Pigmy',
    'timezone' => 'Asia/Kolkata',
    'debug' => false,
    // Other app settings
];
```

### config/database.php
```php
return [
    'host' => 'localhost',
    'database' => 'mobile_pigmy',
    'username' => 'root',
    'password' => '',
    // Database credentials
];
```

## 🗂️ Important Directories

### Write-Permissions Required
These directories need write permissions for the web server:
- `storage/backups/` - Database backup files
- `storage/uploads/` - User uploaded files
- `storage/cache/` - Application cache
- `storage/logs/` - Error and application logs

### Version Control
Exclude from Git (add to .gitignore):
- `storage/backups/*`
- `storage/cache/*`
- `storage/logs/*`
- `storage/uploads/*`
- `config/database.php` (use .env or example file)

## 🔄 Data Flow Examples

### Example 1: Agent Login
```
1. User → agent/login (POST)
2. Router → Auth\AgentAuthController@login
3. Controller validates credentials
4. Agent Model queries database
5. Session created on success
6. Redirect to agent/dashboard
```

### Example 2: Daily Collection
```
1. User → agent/pigmy/collect (POST)
2. Router → Agent\PigmyController@collect
3. Controller validates form data
4. Collection Model saves transaction
5. Customer Model updates balance
6. Success view displayed
```

### Example 3: Generate Report
```
1. User → agent/reports/daily (GET)
2. Router → Agent\ReportController@daily
3. Controller gets date range
4. Collection Model fetches data
5. View renders report table
6. Option to export PDF/Excel
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
