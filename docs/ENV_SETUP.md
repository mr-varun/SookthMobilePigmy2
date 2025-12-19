# Environment Configuration Guide

## Overview
The application now uses `.env` file for environment-specific configuration, following industry best practices for security and deployment flexibility.

## Setup Instructions

### 1. Create .env File

Copy the example file:
```bash
# Windows (PowerShell)
Copy-Item .env.example -Destination .env

# Linux/Mac
cp .env.example .env
```

### 2. Configure Database Credentials

Edit `.env` and update your database settings:

```env
# Database Configuration
DB_HOST=localhost
DB_PORT=3308
DB_DATABASE=mobile_pigmy
DB_USERNAME=root
DB_PASSWORD=your_secure_password
```

### 3. Configure Application Settings

```env
# Application Settings
APP_NAME="Sookth Mobile Pigmy"
APP_ENV=production          # development, production, staging
APP_DEBUG=false             # true for development, false for production
APP_URL=https://yourdomain.com

# Session Configuration
SESSION_LIFETIME=120        # Minutes (2 hours)
SESSION_SECURE=true         # true if using HTTPS
SESSION_HTTP_ONLY=true      # Recommended: true

# Security
CSRF_ENABLED=true          # Recommended: true
```

## Environment Variables Reference

### Application Variables

| Variable | Description | Default | Required |
|----------|-------------|---------|----------|
| `APP_NAME` | Application name | "Sookth Mobile Pigmy" | No |
| `APP_ENV` | Environment (development/production) | development | No |
| `APP_DEBUG` | Enable debug mode | true | No |
| `APP_URL` | Application URL | http://localhost | No |

### Database Variables

| Variable | Description | Default | Required |
|----------|-------------|---------|----------|
| `DB_HOST` | Database host | localhost | Yes |
| `DB_PORT` | Database port | 3308 | Yes |
| `DB_DATABASE` | Database name | mobile_pigmy | Yes |
| `DB_USERNAME` | Database username | root | Yes |
| `DB_PASSWORD` | Database password | (empty) | Yes |
| `DB_CHARSET` | Character set | utf8mb4 | No |
| `DB_COLLATION` | Collation | utf8mb4_unicode_ci | No |

### Session Variables

| Variable | Description | Default | Required |
|----------|-------------|---------|----------|
| `SESSION_LIFETIME` | Session timeout (minutes) | 120 | No |
| `SESSION_SECURE` | Use secure cookies (HTTPS) | false | No |
| `SESSION_HTTP_ONLY` | HTTP only cookies | true | No |

### Security Variables

| Variable | Description | Default | Required |
|----------|-------------|---------|----------|
| `CSRF_ENABLED` | Enable CSRF protection | true | No |

## How It Works

### 1. Loading Process
```
index.php
  ↓
Load helpers.php (contains loadEnv() function)
  ↓
loadEnv('.env') - Loads environment variables
  ↓
Load config/database.php - Uses env() function
  ↓
Database connection with .env values
```

### 2. Helper Functions

**`loadEnv($path)`**
- Loads variables from .env file
- Sets them in `$_ENV`, `$_SERVER`, and `putenv()`
- Skips comments (lines starting with #)
- Ignores blank lines

**`env($key, $default = null)`**
- Gets environment variable
- Returns default if not found
- Converts string booleans (true/false)
- Converts null strings to actual null

### 3. Configuration Files

**config/database.php**
```php
return [
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', 3308),
    'database' => env('DB_DATABASE', 'mobile_pigmy'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
];
```

**config/app.php** (can be updated similarly)
```php
return [
    'name' => env('APP_NAME', 'Sookth Mobile Pigmy'),
    'env' => env('APP_ENV', 'development'),
    'debug' => env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost'),
];
```

## Different Environments

### Development (.env)
```env
APP_ENV=development
APP_DEBUG=true
DB_HOST=localhost
DB_PORT=3308
DB_PASSWORD=
```

### Production (.env)
```env
APP_ENV=production
APP_DEBUG=false
DB_HOST=production-db-server.com
DB_PORT=3306
DB_PASSWORD=SecureProductionPassword123!
SESSION_SECURE=true
APP_URL=https://www.yourdomain.com
```

### Staging (.env)
```env
APP_ENV=staging
APP_DEBUG=true
DB_HOST=staging-db-server.com
DB_PORT=3306
DB_PASSWORD=StagingPassword456!
APP_URL=https://staging.yourdomain.com
```

## Security Best Practices

### ✅ DO's
- ✅ **Always** add `.env` to `.gitignore`
- ✅ Use strong passwords in production
- ✅ Set `APP_DEBUG=false` in production
- ✅ Set `SESSION_SECURE=true` when using HTTPS
- ✅ Keep `.env.example` updated (without sensitive data)
- ✅ Use different credentials for each environment
- ✅ Restrict file permissions on `.env`:
  ```bash
  chmod 600 .env  # Linux/Mac
  ```

### ❌ DON'Ts
- ❌ **Never** commit `.env` to version control
- ❌ **Never** share `.env` files publicly
- ❌ **Never** use production credentials in development
- ❌ **Never** enable debug mode in production
- ❌ **Never** use default passwords (root, admin, 123456)

## Fallback Behavior

If `.env` file doesn't exist:
- Application uses default values from `config/*.php`
- Works with hardcoded values (like before)
- No errors thrown

This ensures:
- Backward compatibility
- Easy development setup
- Flexible deployment

## Troubleshooting

### Issue: Database connection failed

**Solution:**
1. Check `.env` file exists
2. Verify database credentials
3. Ensure database server is running
4. Check port number (3308 or 3306)

### Issue: .env variables not loading

**Solution:**
1. Check `.env` file syntax (KEY=VALUE)
2. No spaces around `=`
3. Remove quotes from simple values
4. Check file permissions

### Issue: Changes not taking effect

**Solution:**
1. Restart web server
2. Clear PHP opcache if enabled
3. Check for syntax errors in `.env`

## Migration from Old Setup

### Before (Hardcoded in config/database.php)
```php
return [
    'host' => 'localhost',
    'password' => 'mypassword',
];
```

### After (Using .env)
**config/database.php:**
```php
return [
    'host' => env('DB_HOST', 'localhost'),
    'password' => env('DB_PASSWORD', ''),
];
```

**.env:**
```env
DB_HOST=localhost
DB_PASSWORD=mypassword
```

### Benefits
- ✅ Credentials not in code
- ✅ Different settings per environment
- ✅ More secure
- ✅ Industry standard
- ✅ Easy deployment

## Additional Resources

- [PHP Environment Variables Best Practices](https://www.php.net/manual/en/function.getenv.php)
- [12 Factor App - Config](https://12factor.net/config)
- [Security Best Practices](https://cheatsheetseries.owasp.org/cheatsheets/Configuration_Cheat_Sheet.html)

## Support

For issues or questions:
- Check `.env.example` for correct format
- Verify file permissions
- Contact support: support@sookthtech.com

---

**Last Updated:** December 19, 2025  
**Version:** 2.0
