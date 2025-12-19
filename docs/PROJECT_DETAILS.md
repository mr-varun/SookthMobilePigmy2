# Sookth Mobile Pigmy 2 - Project Details & Features

## 📋 Table of Contents
- [Project Overview](#project-overview)
- [System Architecture](#system-architecture)
- [User Roles & Access](#user-roles--access)
- [Core Features](#core-features)
- [Step-by-Step Workflows](#step-by-step-workflows)
- [Technical Specifications](#technical-specifications)
- [Installation Guide](#installation-guide)
- [Module Details](#module-details)
- [Security Features](#security-features)
- [Reporting System](#reporting-system)

---

## 🎯 Project Overview

**Sookth Mobile Pigmy 2** is a comprehensive web-based pigmy deposit collection and management system designed for banks, cooperative societies, and financial institutions. The system enables efficient management of daily pigmy collections, customer accounts, agent operations, and branch management.

### What is Pigmy Deposit?
Pigmy deposit is a small savings scheme where agents collect fixed daily deposits from customers at their doorstep. The system automates tracking, reporting, and management of these daily collections.

### Key Objectives
- ✅ Digitize daily pigmy collection operations
- ✅ Real-time tracking of collections and balances
- ✅ Multi-branch and multi-agent support
- ✅ Automated report generation and reconciliation
- ✅ Secure license-based access control
- ✅ Mobile-friendly interface for field agents
- ✅ Comprehensive backup and data security

---

## 🏗️ System Architecture

### Three-Tier Architecture

```
┌─────────────────────────────────────────────┐
│          Presentation Layer                 │
│  (Views - HTML/CSS/JavaScript)              │
│  - Admin Dashboard                          │
│  - Agent Mobile Interface                   │
│  - Bank Monitoring Panel                    │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│         Business Logic Layer                │
│  (Controllers & Models)                     │
│  - Request Handling                         │
│  - Data Processing                          │
│  - Validation & Security                    │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│           Data Layer                        │
│  (MySQL Database)                           │
│  - Customer Data                            │
│  - Collection Records                       │
│  - Agent & Branch Info                      │
└─────────────────────────────────────────────┘
```

### Technology Stack
- **Backend**: PHP 7.4+ (Custom MVC Framework)
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (jQuery)
- **Libraries**: 
  - DomPDF (PDF generation)
  - PHPSpreadsheet (Excel operations)
- **Server**: Apache/Nginx with mod_rewrite
- **Security**: Session-based authentication, CSRF protection

---

## 👥 User Roles & Access

### 1. 🔐 Admin (Super Admin)
**Full System Control**

**Access Rights:**
- ✅ Create, edit, delete agents
- ✅ Manage branches and assignments
- ✅ License management and validation
- ✅ System settings configuration
- ✅ Database backup and restore
- ✅ View all reports and analytics
- ✅ Agent performance monitoring

**Dashboard Features:**
- Total agents, branches, customers overview
- Daily collection summary
- License status monitoring
- Recent activity logs
- System health indicators

### 2. 📱 Agent (Field Agent)
**Collection & Customer Management**

**Access Rights:**
- ✅ Daily pigmy collection entry
- ✅ Customer account management
- ✅ View assigned customers
- ✅ Generate collection reports
- ✅ Update profile and PIN
- ✅ Resend SMS/notifications
- ❌ Cannot access other agents' data
- ❌ Cannot modify system settings

**Dashboard Features:**
- Today's collection target vs achieved
- Pending collections list
- Customer list with balances
- Quick collection entry
- Daily/monthly reports

### 3. 🏦 Bank (Bank Manager)
**Monitoring & Reporting**

**Access Rights:**
- ✅ View all agents and collections
- ✅ Branch-wise performance reports
- ✅ Consolidated financial reports
- ✅ Agent performance analysis
- ✅ Database backup operations
- ✅ Export reports (PDF/Excel)
- ❌ Cannot modify agent data
- ❌ Cannot create new accounts

**Dashboard Features:**
- Branch-wise collection summary
- Agent performance comparison
- Outstanding collections
- Monthly/yearly trends
- Export and backup options

---

## 🎯 Core Features

### 1. 🔐 Authentication System

#### Admin Login
- **URL**: `/admin/login`
- **Credentials**: Username + Password
- **Features**:
  - Session-based authentication
  - Remember me option
  - Auto-logout on inactivity
  - IP-based security (optional)

#### Agent Login
- **URL**: `/agent/login`
- **Credentials**: Agent Code + 4-digit PIN
- **Features**:
  - Mobile-optimized interface
  - Forgot PIN (SMS/Email recovery)
  - PIN change facility
  - Device fingerprinting
  - License validation on login

#### Bank Login
- **URL**: `/bank/login`
- **Credentials**: Username + Password
- **Features**:
  - Read-only dashboard
  - Multi-branch access
  - Role-based permissions

---

### 2. 👤 Agent Management (Admin)

#### Create New Agent
**Step-by-Step Process:**

1. **Navigate**: Admin Dashboard → Agents → Create New Agent
2. **Enter Details**:
   - Agent Name
   - Mobile Number
   - Email Address
   - Branch Assignment
   - Agent Code (auto-generated or manual)
   - Initial PIN (4-digit)
   - Collection Target (daily/monthly)
3. **Assign Permissions**:
   - Active/Inactive status
   - License allocation
   - Collection limits
4. **Save & Notify**:
   - Agent created in database
   - SMS/Email sent with login credentials
   - Agent ID generated

#### Edit Agent
- Update personal information
- Change branch assignment
- Modify collection targets
- Reset PIN
- Activate/Deactivate account

#### View Agent List
- **Search & Filter**:
  - By name, code, branch
  - Active/Inactive status
  - Date range filters
- **Columns Display**:
  - Agent Code, Name, Branch
  - Mobile, Email
  - Collection Target
  - Today's Collection
  - Status, Actions

#### Agent Performance
- Daily collection tracking
- Target vs Achievement
- Customer coverage ratio
- Outstanding collections
- Performance graphs

---

### 3. 🏢 Branch Management (Admin)

#### Create Branch
1. Branch Name
2. Branch Code
3. Location/Address
4. Contact Information
5. Manager Details
6. Opening Date

#### Branch Operations
- Assign agents to branches
- Transfer agents between branches
- Branch-wise reporting
- Branch performance metrics

#### Branch Dashboard
- Total agents in branch
- Active customers
- Daily collection summary
- Pending collections
- Branch targets

---

### 4. 💰 Pigmy Collection (Agent)

#### Daily Collection Entry
**Step-by-Step Workflow:**

1. **Login**: Agent logs in with Agent Code + PIN
2. **Dashboard**: View today's collection list
3. **Select Customer**:
   - Search by name or account number
   - View customer details and balance
4. **Enter Collection**:
   - Collection amount (pre-filled with default)
   - Collection date (today by default)
   - Receipt number (auto-generated)
   - Payment mode (Cash/Cheque/Online)
   - Optional notes
5. **Verify Details**: Review before saving
6. **Save Transaction**:
   - Data saved to database
   - Customer balance updated
   - Receipt generated
   - SMS notification sent (optional)
7. **Print Receipt**: Print or save PDF receipt

#### Bulk Collection Entry
- Upload CSV file
- Multiple entries at once
- Validation and error checking
- Batch processing

#### Collection Reports
- Today's collections
- Date range reports
- Customer-wise summary
- Payment mode analysis
- Export to Excel/PDF

---

### 5. 👥 Customer Management (Agent)

#### Add New Customer
1. **Personal Information**:
   - Customer Name
   - Father's/Spouse Name
   - Date of Birth
   - Gender
   - Occupation
2. **Contact Details**:
   - Mobile Number
   - Alternate Number
   - Email Address
   - Residential Address
3. **Account Details**:
   - Account Number (auto-generated)
   - Daily Deposit Amount
   - Collection Frequency (Daily/Weekly)
   - Start Date
   - Maturity Period
4. **KYC Documents**:
   - Aadhaar Number
   - PAN Card
   - Document Upload
5. **Nominee Details**:
   - Nominee Name
   - Relationship
   - Contact Information

#### Edit Customer
- Update personal information
- Modify collection amount
- Change collection frequency
- Update contact details
- Add/modify nominee

#### Customer List
- Search and filter
- View active/inactive customers
- Collection history
- Outstanding balances
- Account statements

#### Customer Transactions
- View all transactions
- Filter by date range
- Transaction details
- Print statements
- Export reports

---

### 6. 📊 Reporting System

#### Admin Reports
1. **Agent Performance Report**
   - Agent-wise collection summary
   - Target vs Achievement
   - Date range analysis
   - Branch comparison
   - Export to Excel/PDF

2. **Branch Report**
   - Branch-wise collections
   - Agent count and performance
   - Customer statistics
   - Outstanding amounts
   - Trend analysis

3. **Financial Report**
   - Daily collection summary
   - Monthly consolidated report
   - Yearly financial statements
   - Revenue analysis
   - Payment mode distribution

4. **License Report**
   - Active licenses
   - Expiring licenses
   - License utilization
   - Revenue from licenses

#### Agent Reports
1. **Daily Collection Report**
   - Today's collections
   - Customer-wise breakdown
   - Total amount collected
   - Pending collections

2. **Monthly Report**
   - Month-wise summary
   - Customer collection history
   - Outstanding amounts
   - Performance metrics

3. **Customer Statement**
   - Individual customer report
   - All transactions
   - Balance details
   - Account summary

#### Bank Reports
1. **Consolidated Report**
   - All branches summary
   - All agents performance
   - Total collections
   - Outstanding analysis

2. **Branch Comparison**
   - Branch-wise performance
   - Growth analysis
   - Regional statistics

3. **Audit Report**
   - Transaction logs
   - User activity
   - System changes
   - Security events

---

### 7. 🔑 License Management (Admin)

#### License System
The system operates on a license-based model to control access and monetization.

#### License Types
1. **Agent License**
   - Per agent subscription
   - Monthly/Yearly plans
   - Feature-based tiers
   - Auto-renewal option

2. **Branch License**
   - Per branch activation
   - Unlimited agents
   - Custom pricing

3. **Enterprise License**
   - Full system access
   - All features unlocked
   - Custom terms

#### License Operations

**Add New License**:
1. Select licensee (Agent/Branch)
2. Choose license type
3. Set validity period
4. Enter license key (auto-generated)
5. Activate license

**License Validation**:
- On agent login
- Daily automatic check
- Warning before expiry (7 days)
- Auto-disable on expiry
- Grace period option

**License Renewal**:
- Manual renewal by admin
- Online payment integration (future)
- Email/SMS notifications
- License history tracking

**License Reports**:
- Active licenses count
- Expiring soon (7, 15, 30 days)
- Expired licenses
- Revenue tracking
- Renewal reminders

---

### 8. 💾 Backup & Restore (Admin/Bank)

#### Database Backup

**Manual Backup**:
1. Navigate to Backup section
2. Click "Create Backup"
3. Backup file generated with timestamp
4. Download backup (.sql file)
5. Stored in `storage/backups/`

**Automatic Backup**:
- Daily scheduled backups
- Configurable timing
- Retention policy (keep last 30 days)
- Email notification on completion

**Backup Features**:
- Full database backup
- Compressed files
- Timestamped filenames
- Size and date display
- Download option

#### Restore Database
1. Upload backup file
2. Verify backup integrity
3. Confirm restore operation
4. Database restored
5. Success notification

**Safety Features**:
- Confirmation dialog
- Backup before restore
- Error handling
- Rollback on failure

---

### 9. 🔧 Settings & Configuration (Admin)

#### Application Settings
- System Name
- Organization Name
- Logo Upload
- Timezone Configuration
- Date Format
- Currency Settings

#### Email Configuration
- SMTP Settings
- Email Templates
- Notification Preferences
- Test Email Function

#### SMS Configuration
- SMS Gateway Settings
- API Credentials
- SMS Templates
- Balance Checking

#### Security Settings
- Session Timeout
- Password Policy
- Login Attempts Limit
- IP Whitelisting (optional)
- Two-Factor Authentication (optional)

#### Collection Settings
- Default Collection Amount
- Collection Time Window
- Late Payment Charges
- Maturity Calculation
- Interest Rates

---

### 10. 📱 Mobile Features (Agent)

#### Mobile-Optimized Interface
- Responsive design
- Touch-friendly buttons
- Quick actions
- Offline capability (future)

#### Quick Collection
- Barcode/QR code scanning
- Voice input
- GPS location tracking
- Camera for receipts

#### Notifications
- Push notifications (PWA)
- SMS alerts
- Collection reminders
- Target notifications

---

## 🔄 Step-by-Step Workflows

### Workflow 1: Daily Collection by Agent

```
1. Agent Login
   ↓
2. View Dashboard (Today's pending collections)
   ↓
3. Search/Select Customer
   ↓
4. Enter Collection Details
   - Amount: Rs. 100 (default)
   - Date: Today
   - Payment: Cash
   ↓
5. Verify & Save
   ↓
6. Transaction Saved
   - Database updated
   - Balance calculated
   - Receipt generated
   ↓
7. Print/SMS Receipt
   ↓
8. Move to Next Customer
   ↓
9. End of Day Summary
   ↓
10. Submit Daily Report
```

### Workflow 2: New Customer Onboarding

```
1. Agent receives customer request
   ↓
2. Agent Login → Customers → Add New
   ↓
3. Fill Customer Form
   - Personal details
   - Contact info
   - Account details
   - KYC documents
   ↓
4. Upload Documents (Aadhaar, PAN, Photo)
   ↓
5. Set Collection Amount & Frequency
   ↓
6. Add Nominee Details
   ↓
7. Verify All Information
   ↓
8. Save Customer
   - Account number generated
   - Customer added to agent's list
   - Welcome SMS sent
   ↓
9. First Collection Entry
   ↓
10. Passbook/Welcome Kit Issued
```

### Workflow 3: Admin Creates New Agent

```
1. Admin Login
   ↓
2. Navigate: Agents → Create New Agent
   ↓
3. Fill Agent Details
   - Name, Mobile, Email
   - Branch Assignment
   - Agent Code
   - Initial PIN
   ↓
4. Set Permissions & Targets
   - Collection target
   - Customer limit
   - License allocation
   ↓
5. Save Agent
   - Agent created in system
   - Login credentials generated
   ↓
6. SMS/Email Sent to Agent
   - Agent Code
   - Initial PIN
   - Login URL
   ↓
7. Agent Training & Onboarding
   ↓
8. Agent First Login
   - Change PIN
   - Update profile
   ↓
9. Agent Starts Collections
```

### Workflow 4: Monthly Report Generation

```
1. Admin/Bank Login
   ↓
2. Navigate: Reports → Monthly Report
   ↓
3. Select Parameters
   - Month & Year
   - Branch (optional)
   - Agent (optional)
   ↓
4. Generate Report
   - System fetches data
   - Calculations performed
   - Report compiled
   ↓
5. View Report
   - Collection summary
   - Agent performance
   - Outstanding amounts
   - Growth analysis
   ↓
6. Export Options
   - Download PDF
   - Export to Excel
   - Email report
   ↓
7. Save/Archive Report
```

### Workflow 5: License Expiry Handling

```
1. System Checks Licenses Daily
   ↓
2. License Expiring in 7 Days
   ↓
3. Email/SMS Alert Sent
   - To Admin
   - To Agent
   ↓
4. Admin Reviews License
   ↓
5. Decision: Renew or Expire
   ↓
If Renew:
   6a. Admin → License Management
   7a. Select License → Renew
   8a. Set New Validity
   9a. License Renewed
   10a. Confirmation SMS
   
If Expire:
   6b. License Expires Automatically
   7b. Agent Login Blocked
   8b. "License Expired" Message
   9b. Agent Cannot Access System
   10b. Data Preserved (Read-only)
```

---

## 🔒 Security Features

### 1. Authentication Security
- ✅ Encrypted passwords (bcrypt/password_hash)
- ✅ Secure session management
- ✅ CSRF token validation
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS protection (input sanitization)
- ✅ Auto-logout on inactivity
- ✅ Login attempt limiting
- ✅ IP-based restrictions (optional)

### 2. Data Security
- ✅ Sensitive data encryption
- ✅ Secure file uploads
- ✅ Database backup encryption
- ✅ Audit trail logging
- ✅ User activity monitoring
- ✅ Data anonymization (for reports)

### 3. Access Control
- ✅ Role-based access control (RBAC)
- ✅ Permission-based features
- ✅ Session validation per request
- ✅ Secure password reset
- ✅ License-based access

### 4. Infrastructure Security
- ✅ HTTPS enforcement (recommended)
- ✅ Secure file permissions
- ✅ Database credential protection
- ✅ Error logging (no sensitive data)
- ✅ Regular security updates

---

## 🛠️ Technical Specifications

### System Requirements

#### Server Requirements
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Apache/Nginx**: Latest stable version
- **PHP Extensions**:
  - PDO & PDO_MySQL
  - mbstring
  - OpenSSL
  - GD or Imagick (for image processing)
  - zip/unzip
  - curl

#### Recommended Server
- **RAM**: 2GB minimum, 4GB recommended
- **Storage**: 10GB minimum (depends on data volume)
- **Processor**: 2 cores minimum
- **Bandwidth**: 100Mbps or higher

#### Client Requirements
- **Browser**: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **Screen**: 1024x768 minimum resolution
- **Internet**: Stable connection (3G or better)
- **Mobile**: Android 8+ or iOS 12+

### Database Schema Overview

**Main Tables:**
- `users` - Admin, Bank, Agent users
- `agents` - Agent details
- `branches` - Branch information
- `customers` - Customer accounts
- `collections` - Daily collection transactions
- `licenses` - License management
- `settings` - System configurations
- `audit_logs` - Activity tracking
- `backups` - Backup records

### API Endpoints (For Future Mobile App)
```
POST /api/login
GET  /api/customers
POST /api/collection
GET  /api/reports/daily
POST /api/customer/create
GET  /api/dashboard/stats
```

---

## 📥 Installation Guide

### Step 1: Server Preparation
```bash
# Check PHP version
php -v

# Check MySQL
mysql --version

# Verify extensions
php -m | grep -E 'pdo|mysql|mbstring|openssl|gd'
```

### Step 2: Download & Extract
1. Download project files
2. Extract to web directory
3. Set proper permissions:
```bash
chmod -R 755 /path/to/SookthMobilePigmy2
chmod -R 777 /path/to/SookthMobilePigmy2/storage
```

### Step 3: Database Setup
1. Create database:
```sql
CREATE DATABASE mobile_pigmy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import schema:
```bash
mysql -u root -p mobile_pigmy < database/migrations/mobile_pigmy.sql
```

### Step 4: Configuration
1. Copy and edit `config/database.php`:
```php
return [
    'host' => 'localhost',
    'database' => 'mobile_pigmy',
    'username' => 'your_username',
    'password' => 'your_password',
];
```

2. Edit `config/app.php`:
```php
return [
    'name' => 'Your Organization Name',
    'url' => 'http://your-domain.com',
    'timezone' => 'Asia/Kolkata',
];
```

### Step 5: Run Setup
1. Navigate to: `http://your-domain.com/setup.php`
2. Follow setup wizard
3. Create admin account
4. Verify system health
5. Delete `setup.php` after completion

### Step 6: First Login
1. Go to `http://your-domain.com/admin/login`
2. Login with admin credentials
3. Configure system settings
4. Create first branch
5. Create first agent
6. Start using the system

---

## 🎓 Training & Support

### User Documentation
- Admin User Guide
- Agent User Guide
- Bank User Guide
- Quick Reference Cards

### Video Tutorials
- System Overview
- Daily Operations
- Report Generation
- Troubleshooting

### Support Channels
- Email Support: support@sookthmobilepigmy.com
- Phone Support: +91-XXXXXXXXXX
- WhatsApp: +91-XXXXXXXXXX
- Documentation: docs.sookthmobilepigmy.com

---

## 🔮 Future Enhancements

### Planned Features
- [ ] Mobile App (Android & iOS)
- [ ] Offline Mode with Sync
- [ ] Biometric Authentication
- [ ] QR Code based collections
- [ ] WhatsApp Integration
- [ ] Payment Gateway Integration
- [ ] Customer Self-Service Portal
- [ ] Advanced Analytics & AI
- [ ] Multi-language Support
- [ ] Voice Commands
- [ ] Blockchain for Audit Trail
- [ ] Integration with Accounting Software

### Version Roadmap
- **v2.1**: Mobile app release
- **v2.2**: Payment gateway integration
- **v2.3**: Customer portal
- **v3.0**: AI-powered insights

---

## 📞 Contact & Credits

**Developed By**: Sookth Technologies  
**Version**: 2.0  
**Last Updated**: December 19, 2025  
**License**: Proprietary  

**For inquiries**:  
📧 Email: info@sookthtech.com  
🌐 Website: www.sookthtech.com  
📱 Phone: +91-XXXXXXXXXX

---

**© 2025 Sookth Technologies. All Rights Reserved.**
