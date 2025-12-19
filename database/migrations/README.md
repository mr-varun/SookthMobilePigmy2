# Database Migrations

This directory contains database migration scripts for schema changes.

## Usage

Place SQL migration files here with naming convention:
- `YYYY_MM_DD_HHMMSS_description.sql`
- Example: `2025_12_16_120000_create_agents_table.sql`

## Migration Files

Currently empty - migrations will be added as needed.

## Note

For initial setup, import the database from:
- `SMP/files/local_mobile_pigmy.sql` (for local development)
- `SMP/files/remote_mobile_pigmy.sql` (for production)
