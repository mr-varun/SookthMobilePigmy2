# Database Directory

Place your database-related files here:

- SQL dump files
- Migration scripts
- Seed data

## Migration Files

Create migration files with timestamp prefix:
- `2024_01_01_create_users_table.sql`
- `2024_01_02_create_agents_table.sql`

## Importing Database

To import the existing database:

```bash
mysql -u root -p mobile_pigmy < your_database.sql
```

Or use phpMyAdmin/MySQL Workbench to import the SQL files from the old SMP/files directory.
