# Version Notes

## Version 1.2.0 - December 13, 2025

### New Features

#### Excel Export Functionality
- **Export to Excel Button**: Added Excel export capability in Pigmy → Computer module
  - One-click export of transaction data to formatted Excel file
  - Professional formatting with borders and styling
  - Export button enabled/disabled based on loaded transactions

- **Comprehensive Excel Report**:
  - Header section includes:
    - Branch Name and Branch Code
    - Agent Code and Agent Name
    - Deposit Type
    - Transaction Date
    - Export Timestamp
    - Total Transaction Count
    - Total Amount Summary
  - Transaction table with all details (#, Account, Name, Date, Amount)
  - All cells have proper borders for professional appearance
  - Bold formatting for labels and table headers
  - Gray background for table header row
  - Auto-sized columns for optimal readability

- **Smart File Naming**: 
  - Auto-generated filename: `Pigmy_Transactions_[DepositType]_[Date].xlsx`
  - Easy to identify and organize exported files

### Technical Changes
- **Frontend**: 
  - Integrated `xlsx-js-style` library for full Excel styling support
  - Added export button in transaction list card UI
  - Export button state management synced with transaction loading
  - Real-time export logging in console

- **Export Features**:
  - Uses SheetJS with styling extensions for borders and formatting
  - Proper column width allocation
  - Currency formatting for amounts (₹)
  - Timestamp with locale-specific formatting

### Benefits
- **For Users**:
  - Easy backup and archival of transaction data
  - Professional reports for record-keeping
  - Share transaction summaries with stakeholders
  - Offline access to transaction data

- **For Auditing**:
  - Complete transaction trail with all metadata
  - Clear identification of source (branch, agent, date)
  - Timestamped exports for compliance
  - Easy review and verification of transactions

---

## Version 1.1.0 - December 11, 2025

### New Features

#### Auto-Update System
- **Automatic Update Detection**: Application checks for updates on startup
  - Compares local version with server version
  - Shows system notification when update is available
  - Update check available from system tray menu and main page

- **One-Click Update Installation**:
  - Download and install updates directly from the application
  - Automatic backup of current version before updating
  - Seamless restart after update installation
  - No manual download or installation required

- **Update Server Integration**:
  - Server-side version control via `version.txt`
  - Centralized update distribution from `archive.sookthsolutions.com`
  - Automatic cleanup of old versions after successful update

#### Pigmy → Computer Module
- **Date Selection Field**: Added date picker field in the Pigmy → Computer page
  - Date field appears before the Deposit Type dropdown
  - Automatically defaults to today's date
  - Allows users to select custom transaction date for posting

- **Custom Transaction Date Support**: 
  - Selected date is now used for `pigmyaccountposting.tradat` field when downloading transactions
  - Provides flexibility to backdate or post-date transactions as needed
  - Date is logged in real-time console for transparency
  - Narration includes original collection date for audit purposes

### Technical Changes
- **Update System**: 
  - Added `updater.py` module for version management
  - Integrated update checker in tray manager
  - Added `/api/update/check` and `/api/update/install` endpoints
  - Update button added to main page UI

- **Frontend**: 
  - Added date input field with automatic today's date initialization
  - Update status display with install button on main page
  - Real-time update progress notifications

- **Backend**: 
  - Modified `/api/pig-to-com/download` endpoint to accept and use `transaction_date` parameter
  - Fixed SQL parameter mismatch in pigmyaccountposting INSERT statement
  - Added comprehensive error logging for frozen executable

- **Build System**:
  - Updated PyInstaller configuration for proper module inclusion
  - Added `requests` library for update downloads
  - Fixed hidden imports for updater, tray_manager, and connection modules
  - Added debug logging for troubleshooting

### Bug Fixes
- Fixed pystray menu initialization error (function vs callable issue)
- Corrected SQL INSERT statement with proper parameter count
- Fixed module import structure for PyInstaller compilation
- Added error handling and user-friendly error messages

### Benefits
- **For Users**:
  - Always run the latest version with automatic updates
  - No technical knowledge required for updates
  - Better control over transaction posting dates
  - Simplified backdating of transactions when needed

- **For Administrators**:
  - Centralized version management
  - Easy rollout of new features and fixes
  - Reduced support burden for manual updates
  - Improved audit trail with date logging

---

## Version 1.0.0 - Initial Release - November 25, 2025

### Core Features
- License management and validation system
- User authentication with branch-level access
- Computer → Pigmy: Upload accounts to mobile pigmy system
- Pigmy → Computer: Download and post transactions to local database
- Real-time transaction logging and monitoring
- System tray integration for Windows
- Auto-backup of processed transactions
